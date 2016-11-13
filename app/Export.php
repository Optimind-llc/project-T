<?php

namespace App;

use Carbon\Carbon;
// Models
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
            'status' => $merged_page['status'],
            'failures' => array_count_values($merged_page['failures']->map(function($f) {
                return $f->failure_id;
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

    public function exportCSV($partId, $partTypeId, $itionGId)
    {
        $details = $this->getDetails($partId, $partTypeId, $itionGId);


        $now = Carbon::now();
        $choku = new Choku($now);
        $choku_num = $choku->getChoku();

        $dir_path = config('path.'.env('SERVER').'.output');

        switch ($itionGId) {
            case 1:
                $export = ['67149','47060','A',substr($pId, 1,7),'成型','001'];
                $file_name = 'M001_67149_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Seikei'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 2:
                $export = ['67149','47060','A',substr($pId, 1,7),'成型','002'];
                $file_name = 'M001_67149_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Seikei'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 10:
                $export = ['67007','47120','A',substr($pId, 1,7),'接着','止水'];
                $file_name = 'J_shisui_67007_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 11:
                $export = ['67007','47120','A',substr($pId, 1,7),'接着','仕上'];
                $file_name = 'J_shiage_67007_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 12:
                $export = ['67007','47120','A',substr($pId, 1,7),'接着','検査'];
                $file_name = 'J_kensa_67007_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 13:
                $export = ['67007','47120','A',substr($pId, 1,7),'接着','特検'];
                $file_name = 'J_tokken_67007_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 14:
                $export = ['67007','47120','A',substr($pId, 1,7),'接着','手直し'];
                $file_name = 'J_tenaoshi_67007_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 16:
                $export = ['67007','47120','A',substr($pId, 1,7),'接着','簡易CF'];
                $file_name = 'J_tenaoshi_67007_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
        }

        $export = array_merge($export, ['680A',$itorG,$choku_num,$itor,$status]);

        $failures = InspectionGroup::find($gId)
            ->inspection
            ->failures()
            ->get(['id', 'label', 'name'])
            ->map(function($f) {
                return [
                    'id' => $f->id,
                    'label' => $f->label,
                    'name' => $f->name,
                    'type' => $f->pivot->type,
                    'sort' => $f->pivot->sort
                ];
            })
            ->toArray();
        foreach( $failures as $key => $row ) {
            $f_type_array[$key] = $row['type'];
            $f_label_array[$key] = $row['label'];
            $f_sort_array[$key] = $row['sort'];
        }
        array_multisort($f_type_array, $f_sort_array, $f_label_array, $failures);

        foreach ($failures as $f) {
            if (array_key_exists(intval($f['id']), $c_failures)) {
                $f_sum = $c_failures[$f['id']];
            }
            else {
                $f_sum = '';
            }
            array_push($export, $f_sum);
        }

        // push modification result
        if (isset($c_modifications)) {
            $modifications = InspectionGroup::find($gId)
                ->inspection
                ->modifications()
                ->get(['id', 'label', 'name'])
                ->map(function($f) {
                    return [
                        'id' => $f->id,
                        'label' => $f->label,
                        'name' => $f->name,
                        'type' => $f->pivot->type,
                        'sort' => $f->pivot->sort
                    ];
                })
                ->toArray();

            foreach( $modifications as $key => $row ) {
                $m_type_array[$key] = $row['type'];
                $m_label_array[$key] = $row['label'];
                $m_sort_array[$key] = $row['sort'];
            }
            array_multisort($m_type_array, $m_sort_array, $m_label_array, $modifications);

            foreach ($modifications as $m) {
                if (array_key_exists($m['id'], $c_modifications)) {
                    $modi_sum = $c_modifications[$m['id']];                 
                }
                else {
                    $modi_sum = '';
                }

                array_push($export, $modi_sum);
            }
        }

        array_push($export, $now->format('Y/m/d H:i:s'));

        if( touch($file_path) ){
            $file = new \SplFileObject($file_path, 'w');

            foreach( $export as $key => $val ){
                $export_raw[] = mb_convert_encoding($val, 'SJIS-win');
            }

            $file->fputcsv($export_raw);
        }
    }
}
