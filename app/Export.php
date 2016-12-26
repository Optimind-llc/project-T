<?php

namespace App;

use Carbon\Carbon;
// Models
use App\Models\PartType;
use App\Models\InspectionGroup;
use App\Models\Client\Part;

class Export
{
    protected $date;

    protected function getDetails($partId, $partTypeId, $itionGId) {
        $part = Part::with([
            'partType',
            'pages' => function($q) use ($itionGId) {
                $q->join('inspection_families as if', function ($join) use ($itionGId) {
                    $join->on('pages.family_id', '=', 'if.id')
                        ->where('if.inspection_group_id', '=', $itionGId);
                })
                ->orderBy('if.inspected_at')
                ->select(['pages.*', 'if.inspection_group_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at', 'if.status']);
            },
            'pages.failurePositions' => function ($q) use($partId) {
                $q->where('part_id', '=', $partId)
                    ->select(['id','page_id', 'part_id', 'failure_id']);
            },
            'pages.comments',
            'pages.comments.modification',
            'pages.comments.failurePosition',
            // 'pages.holePages' => function($q) use($partTypeId) {
            //     $q->join('holes', 'hole_page.hole_id', '=', 'holes.id')
            //         ->where('holes.part_type_id', '=', $partTypeId)
            //         ->select('hole_page.*')
            //         ->get();
            // },
            // 'pages.holePages.hole' => function($q) use($partTypeId) {
            //     $q->select(['id', 'label']);
            // },
            // 'pages.holePages.holeModification' => function($q) {
            //     $q->select(['hole_modifications.id', 'name']);
            // },
            'pages.inlines'
        ])
        ->find($partId);

        if ($part->pages->count() == 0) {
            return null;
        }

        $merged_page = $part->pages->reduce(function($carry, $page) {
            $carry->put('tyoku', $page->inspector_group);
            $createdBy = explode(',', $page->created_by);
            $carry->put('createdBy', array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
            $carry->put('updatedBy', $page->updated_by ? explode(',', $page->updated_by)[1] : '');
            $carry->put('failures', $page->failurePositions->merge($carry->has('failures') ? $carry['failures'] : []));
            $carry->put('modifications', $page->comments->merge($carry->has('modifications') ? $carry['modifications'] : []));
            $carry->put('holes', $page->holePages->merge($carry->has('holes') ? $carry['holes'] : []));
            $carry->put('inlines', $page->inlines);
            $carry->put('createdAt', $page->created_at->format('Y/m/d H:i:s'));
            $carry->put('updatedAt', $page->updated_at->format('Y/m/d H:i:s'));
            $carry->put('status', $page->pivot->status);
            return $carry;
        }, collect([]));

        return [
            'vehicle' => $part->partType->vehicle_num,
            'pn' => $part->partType->pn,
            'name' => $part->partType->name,
            'panelId' => $part->panel_id,
            'tyoku' => $merged_page['tyoku'],
            'createdBy' => $merged_page['createdBy'],
            'updatedBy' => $merged_page['updatedBy'],
            'createdAt' => $merged_page['createdAt'],
            'updatedAt' => $merged_page['updatedAt'],
            'status' => $merged_page['status'] == 1 ? 0 : 1,
            'failures' => array_count_values($merged_page['failures']->map(function($f) {
                return $f->failure_id;
            })
            ->toArray()),
            'modifications' => array_count_values($merged_page['modifications']->map(function($m) {
                return $m->modification->id;
            })
            ->toArray()),
            // 'holes' => $merged_page['holes']->map(function($h) {
            //     $m = null;
            //     if ($h->holeModification->count() != 0) {
            //         $m['id'] = $h->holeModification->first()->id;
            //         $m['name'] = $h->holeModification->first()->name;
            //     };

            //     return [
            //         'label' => $h->hole->label,
            //         'status' => $h->status,
            //         'm' => $m,
            //     ];
            // })
            // ->toArray(),
            'inlines' => $merged_page['inlines']
        ];
    }

    public function exportCSV($panelId, $partTypeId, $itionGId)
    {
        $partId = Part::where('panel_id', '=', $panelId)
            ->where('part_type_id', '=', $partTypeId)
            ->first()
            ->id;
        $part_type = PartType::find($partTypeId);

        $details = $this->getDetails($partId, $partTypeId, $itionGId);

        $now = Carbon::now();
        $choku = new Choku($now);
        $choku_num = $choku->getChoku();

        $dir_path = config('path.'.env('SERVER').'.output');

        switch ($itionGId) {
            case 1:
                $export = [$part_type->pn,$part_type->pn2,'A',substr($panelId, 1,7),'成形','001','680A'];
                $file_name = 'M001_'.$part_type->pn.'_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Seikei'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 2:
                $export = [$part_type->pn,$part_type->pn2,'A',substr($panelId, 1,7),'成形','002','680A'];
                $file_name = 'M002_'.$part_type->pn.'_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Seikei'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 5:
                $export = [$part_type->pn,$part_type->pn2,'A',substr($panelId, 1,7),'成形','001','680A'];
                $file_name = 'M001_'.$part_type->pn.'_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Seikei'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 6:
                $export = [$part_type->pn,$part_type->pn2,'A',substr($panelId, 1,7),'成形','002','680A'];
                $file_name = 'M002_'.$part_type->pn.'_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Seikei'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 10:
                $export = [$part_type->pn,$part_type->pn2,'A',substr($panelId, 1,7),'接着','止水','680A'];
                $file_name = 'J_shisui_'.$part_type->pn.'_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 11:
                $export = [$part_type->pn,$part_type->pn2,'A',substr($panelId, 1,7),'接着','仕上','680A'];
                $file_name = 'J_shiage_'.$part_type->pn.'_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 12:
                $export = [$part_type->pn,$part_type->pn2,'A',substr($panelId, 1,7),'接着','検査','680A'];
                $file_name = 'J_kensa_'.$part_type->pn.'_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 13:
                $export = [$part_type->pn,$part_type->pn2,'A',substr($panelId, 1,7),'接着','特検','680A'];
                $file_name = 'J_tokken_'.$part_type->pn.'_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 14:
                $export = [$part_type->pn,$part_type->pn2,'A',substr($panelId, 1,7),'接着','手直し','680A'];
                $file_name = 'J_tenaoshi_'.$part_type->pn.'_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 16:
                $export = [$part_type->pn,$part_type->pn2,'A',substr($panelId, 1,7),'接着','簡易CF','680A'];
                $file_name = 'J_kanicf_'.$part_type->pn.'_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
        }

        if (!isset($file_path)) {
            return false;
        }

        $by = $details['updatedBy'] == '' ? $details['createdBy'] : $details['updatedBy'];
        $export = array_merge($export, [
            $details['tyoku'],
            $choku_num,
            $by,
            $details['status']
        ]);

        $failureTypes = InspectionGroup::find($itionGId)->inspection->failures->map(function($f) {
            return [
                'id' => $f->id,
                'label' => $f->label,
                'name' => $f->name,
                'type' => $f->pivot->type,
                'sort' => $f->pivot->sort
            ];
        })->toArray();

        foreach( $failureTypes as $key => $row ) {
            $f_type_array[$key] = $row['type'];
            $f_label_array[$key] = $row['label'];
            $f_sort_array[$key] = $row['sort'];
        }

        array_multisort($f_type_array, $f_sort_array, $f_label_array, $failureTypes);

        foreach ($failureTypes as $ft) {
            if (array_key_exists($ft['id'], $details['failures'])) {
                $f_sum = $details['failures'][$ft['id']];
            }
            else {
                $f_sum = '';
            }
            array_push($export, $f_sum);
        }

        // Push modification result
        if (array_key_exists('modifications', $details) && count($details['modifications'])) {
            $modificationTypes = InspectionGroup::find($itionGId)->inspection->modifications->map(function($m) {
                return [
                    'id' => $m->id,
                    'label' => intval($m->label),
                    'name' => $m->name,
                    'type' => $m->pivot->type,
                    'sort' => $m->pivot->sort
                ];
            })->toArray();

            foreach( $modificationTypes as $key => $row ) {
                $m_type_array[$key] = $row['type'];
                $m_label_array[$key] = $row['label'];
                $m_sort_array[$key] = $row['sort'];
            }

            if (count($modificationTypes) !== 0 ) {
                array_multisort($m_type_array, $m_sort_array, $m_label_array, $modificationTypes);
            }

            foreach ($modificationTypes as $mt) {
                if (array_key_exists($mt['id'], $details['modifications'])) {
                    $modi_sum = $details['modifications'][$mt['id']];                 
                }
                else {
                    $modi_sum = '';
                }

                array_push($export, $modi_sum);
            }
        }

        array_push($export, $now->format('Y/m/d H:i:s'));

        if(touch($file_path)){
            $file = new \SplFileObject($file_path, 'w');

            foreach( $export as $key => $val ){
                $export_raw[] = mb_convert_encoding($val, 'SJIS-win');
            }

            $file->fputcsv($export_raw);
        }

        return true;
    }
}
