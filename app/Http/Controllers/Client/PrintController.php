<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use TCPDF;
use FPDI;
// Models
use App\Models\Process;
use App\Models\PartType;
use App\Models\PageType;
use App\Models\InspectorGroup;
use App\Models\Client\Part;
use App\Models\Client\FailurePosition;
// Exceptions
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// Others
use App\Http\Controllers\Client\InspectionController;
use Database\Seeding\DummyRequest;

/**
 * Class PrintController
 * @package App\Http\Controllers
 */
class PrintController extends Controller
{
    protected function createFPDI()
    {
        $fpdi = new FPDI();

        $fpdi->SetMargins(0, 0, 0);
        $fpdi->setPrintHeader(false);
        $fpdi->setPrintFooter(false);
        $fpdi->SetMargins(0, 0, 0);
        $fpdi->SetCellPadding(0);
        $fpdi->SetAutoPageBreak(false);

        // $fpdi->SetAuthor('Mapping system');
        // $fpdi->SetTitle('TCPDF Example 009');
        // $fpdi->SetSubject('TCPDF Tutorial');
        // $fpdi->SetKeywords('TCPDF, PDF, example, test, guide');

        // // フォントを登録
        // $fontPathRegular = $this->getLibPath() . '/tcpdf/fonts/migmix-2p-regular.ttf';
        // $regularFont = $fpdi->addTTFfont($fontPathRegular, '', '', 32);

        // $fontPathBold = $this->getLibPath() . '/tcpdf/fonts/migmix-2p-bold.ttf';
        // $boldFont = $fpdi->addTTFfont($fontPathBold, '', '', 32);

        // 小塚ゴシックPro M (kozgopromedium)
        // 小塚明朝Pro M (kozminproregular)
        // HYSMyeongJoStd-Medium (hysmyeongjostdmedium)
        // MSungStd-Light (msungstdlight)
        // STSongStd-Light (stsongstdlight)
        return $fpdi;
    }

    protected function renderFailureButtons($fpdi, $failures, $reference, $ws = false)
    {
        $ref = explode('/', $reference);
        $xi0 = $ref[0];
        $yi0 = $ref[1];
        $xn0 = $ref[2];
        $yn0 = $ref[3];
        $h = 10;
        $w = 40;
        $t = [0.5, -1];

        if ($ws) {
            $failures = $failures->filter(function ($f){
                return $f['name'] == '水漏れ';
            });
        }

        $grouped = $failures->groupBy('type');

        // Render important failures
        if (isset($grouped[1])) {
            foreach ($grouped[1] as $i => $failure) {
                if ($i < 7) {
                    $ii = $i;
                    $yy = $yi0;
                }
                elseif (7 <= $i && $i < 14) {
                    $ii = $i-7;
                    $yy = $yi0+$h;
                }
                intval($failure['label']) < 10 ? $tx = $t[0] : $tx = $t[1];

                $fpdi->RoundedRect($ii*$w+$xi0-2, $yy-1, $w-2, $h-2, 1, '1111');
                $fpdi->Text($ii*$w+$xi0+$tx, $yy, $failure['label']);
                $fpdi->Text($ii*$w+$xi0+6, $yy, $failure['name']);
                $fpdi->Circle($ii*$w+$xi0+2, $yy+3, 3.2, 0, 360, "D");
            }
        }


        // Render nomal failures
        if (isset($grouped[2])) {
            foreach ($grouped[2] as $i => $failure) {
                if ($i < 7) {
                    $ii = $i;
                    $yy = $yn0;
                }
                elseif (7 <= $i && $i < 14) {
                    $ii = $i-7;
                    $yy = $yn0+$h;
                }

                intval($failure['label']) < 10 ? $tx = $t[0] : $tx = $t[1];

                $fpdi->RoundedRect($ii*$w+$xn0-2, $yy-1, $w-2, $h-2, 1, '1111');
                $fpdi->Text($ii*$w+$xn0+$tx, $yy, $failure['label']);
                $fpdi->Text($ii*$w+$xn0+6, $yy, $failure['name']);
                $fpdi->Circle($ii*$w+$xn0+2, $yy+3, 3.2, 0, 360, "D");
            }
        }
    }

    protected function renderCommentButtons($fpdi, $comments, $reference)
    {
        $fpdi->SetFont('kozgopromedium', '', 14);
        $fpdi->SetTextColor(0, 0, 0);

        $ref = explode('/', $reference);
        $x0 = $ref[4];
        $y0 = $ref[5];
        $h = 10;
        $w = 36;
        $t = [0.5, -1];

        // Render comments
        foreach ($comments as $i => $comment) {
            if ($i < 8) {
                $ii = $i;
                $yy = $y0;
            }
            elseif (8 <= $i && $i < 16) {
                $ii = $i-7;
                $yy = $y0+$h;
            }

            intval($comment['label']) < 10 ? $tx = $t[0] : $tx = $t[1];

            $fpdi->RoundedRect($ii*$w+$x0-2, $yy-1, $w-2, $h-2, 1, '1111', 'D', ['color' => [0, 0, 0]]);
            $fpdi->Text($ii*$w+$x0+$tx, $yy, $comment['sort']);
            $fpdi->Text($ii*$w+$x0+6, $yy, $comment['message']);
            $fpdi->RoundedRect($ii*$w+$x0-1, $yy, 6, 6, 1, '1111', 'D', ['color' => [78, 143, 12]]);
        }
    }

    /**
     * Get user from JWT token
     */
    public function printByTemplate(Request $request)
    {
        $family = $request->family;
        if (!$family) {
            throw new StoreResourceFailedException('JSON in Request body should contain family key');
        }

        $fpdi = $this->createFPDI();

        /*
         * 今後は複数ページも印刷するのでループを回す
         */
        $page = $family['pages'][0];

        $pageModel = PageType::find($page['pageTypeId']);
        $ws = $page['pageTypeId'] == 16;
        $pdf = $pageModel->pdf;

        // Add new page from template to page 1.
        $fpdi->AddPage();
        $fpdi->setSourceFile(public_path().'/pdf/template/'.$pdf->path);
        $tplIdx = $fpdi->importPage(1);
        $fpdi->useTemplate($tplIdx, null, null, 297, 210, true);

        $fpdi->SetFont('kozgopromedium', '', 16);
        $fpdi->SetTextColor(255, 255, 255);
        $fpdi->Text(250, 3, $family['date']);

        $fpdi->SetFont('kozgopromedium', '', 14);
        $fpdi->SetTextColor(0, 0, 0);

        $numOfParts = count($page['parts']);
        $fpdi->Text(223, 18+($numOfParts-1)*5, $family['inspectorGroup'].' '.$family['inspector']);
        if (isset($family['table'])) {
            $fpdi->Text(286, 18+($numOfParts-1)*5, $family['table']);
        }

        foreach ($page['parts'] as $i => $part) {
            $part_type = PartType::find($part['partTypeId']);
            $fpdi->Text(14, $i*10+18, $part_type['vehicle_num']);
            $fpdi->Text(44, $i*10+18, $part_type['pn']);
            $fpdi->Text(77, $i*10+18, $part_type['name']);
            $fpdi->Text(151, $i*10+18, $part['panelId']);
            if ($part['status'] == 1) {
                $s = '○';
            } elseif ($part['status'] == 0) {
                $s = '×';
            } else {
                $s = '';
            }
            $fpdi->Text(197, $i*10+18, $s);
        }

        /*
         * For Matuken
         */
        function coordinateTransformationK($pixel, $figure_area) {
            $area = explode('/', $figure_area);
            $x1 = floatval($area[0]);
            $y1 = floatval($area[1]);
            $x2 = floatval($area[2]);
            $y2 = floatval($area[3]);
            $w = intval($area[4]);
            $h = intval($area[5]);

            // $x1 = 23.9;
            // $y1 = 45.2;
            // $x2 = 273.1;
            // $y2 = 170.9;
            // $w = 1740;
            // $h = 900;

            $exploded = explode(',', $pixel);
            $px = $exploded[0];
            $py = $exploded[1];

            $x = $px*($x2-$x1)/($w/2)+$x1;
            $y = $py*($y2-$y1)/($h/2)+$y1;

            return ['x' => $x, 'y' => $y];
        }

        function coordinateTransformation($pixel, $figure_area) {
            $area = explode('/', $figure_area);
            $x1 = floatval($area[0]);
            $y1 = floatval($area[1]);
            $x2 = floatval($area[2]);
            $y2 = floatval($area[3]);
            $w = intval($area[4]);
            $h = intval($area[5]);

            // $x1 = 23.9;
            // $y1 = 87;
            // $x2 = 273.1;
            // $y2 = 201.6;
            // $w = 1740;
            // $h = 820;

            $exploded = explode(',', $pixel);
            $px = $exploded[0];
            $py = $exploded[1];

            $x = $px*($x2-$x1)/$w+$x1;
            $y = $py*($y2-$y1)/$h+$y1;

            return ['x' => $x, 'y' => $y];
        }

        function getFailureLabel($id, $failures) {
            return array_filter($failures, function($f) use ($id) {
                $f['id'] === $id;
            })['label'];
        }

        /********* Render failures *********/
        if (array_key_exists('failures', $page) && count($page['failures']) !== 0 ) {
            $client_failures = $page['failures'];
            $failures = $pageModel->group->inspection->process->failures
                ->map(function($f) {
                    return [
                        'id' => $f->id,
                        'name' => $f->name,
                        'label' => $f->sort,
                        'type' => $f->pivot->type
                    ];
                });

            $this->renderFailureButtons($fpdi, $failures, $pdf->reference, $ws);

            $fpdi->SetFont('kozgopromedium', '', 12);
            $fpdi->SetTextColor(255,0,0);

            foreach ($client_failures as $cf) {
                $p = coordinateTransformationK($cf['pointK'], $pdf->area);
                $label = $failures->filter(function($f) use ($cf) {
                    return $f['id'] == $cf['id'];
                })->first()['label'];

                $fpdi->Circle($p['x'], $p['y'], 3.1, 0, 360, 'F', '', [255, 0, 0]);
                $fpdi->Circle($p['x'], $p['y'], 2.9, 0, 360, 'F', '', [255, 255, 255]);

                if ($label > 9) {
                    $fpdi->Text($p['x']-2.6, $p['y']-2.5, $label);
                } else {
                    $fpdi->Text($p['x']-1.5, $p['y']-2.5, $label);
                }
            }
        }

        /********* Render holes *********/
        if (array_key_exists('holes', $page) && count($page['holes']) !== 0 ) {
            $fpdi->SetFont('kozgopromedium', '', 10);
            $holes = $pageModel->figure->holes;
            $client_holes = array_column($page['holes'], 'status', 'id');

            foreach ($holes as $hole) {
                $fpdi->SetTextColor(0, 0, 0);
                $p = coordinateTransformation($hole->point, $pdf->area);

                $lx = $p['x'];
                $ly = $p['y'];
                $lw = 6;
                $lh = 5;
                $d = 6;

                if (array_key_exists($hole->id, $client_holes)) {
                    switch ($client_holes[$hole->id]) {
                        case 0: $label = '×'; break;
                        case 1: $label = '○'; break;
                        case 2: $label = '△'; break;
                    }
                    $fpdi->Circle($p['x'], $p['y'], 2.4, 0, 360, 'F', '', [255, 255, 255]);
                    $fpdi->Text($p['x']-1.7, $p['y']-2.2, $label);                    
                }

                switch ($hole->direction) {
                    case 'left': $lx = $lx-$d; break;
                    case 'right': $lx = $lx+$d; break;
                    case 'top': $ly = $ly-$d; break;
                    case 'bottom': $ly = $ly+$d; break;
                }

                $colorcode = $hole->color;
                $rgb[0] = hexdec(substr($colorcode, 0, 2));
                $rgb[1] = hexdec(substr($colorcode, 2, 2));
                $rgb[2] = hexdec(substr($colorcode, 4, 2));

                if ($hole->border == 'dotted') {
                    $borderStyle = ['LTRB' => [
                        'dash' => '2',
                        'color' => [0, 0, 0]
                    ]];
                } else {
                    $borderStyle = ['LTRB' => [
                        'color' => [0, 0, 0]
                    ]];
                }

                if ($hole->shape == 'square') {
                    $fpdi->Rect($lx-($lw/2), $ly-($lh/2), $lw, $lh, 'F', $borderStyle, $rgb);
                }
                elseif ($hole->shape == 'circle') {
                    $fpdi->Circle($lx, $ly, 3, 0, 360, 'F', '', [0, 0, 0]);
                    $fpdi->Circle($lx, $ly, 2.9, 0, 360, 'F', '', $rgb);
                }

                if ($rgb[0]+$rgb[1]+$rgb[2] < 255) {
                    $fpdi->SetTextColor(255, 255, 255);
                }

                if ($hole->label < 10) {
                    $fpdi->Text($lx-1, $ly-2.2, $hole->label);
                }
                elseif (9 < $hole->label && $hole->label < 100) {
                    $fpdi->Text($lx-2, $ly-2.2, $hole->label);
                }
                else {
                    $fpdi->Text($lx-3.4, $ly-2.2, $hole->label);
                }
            }
        }

        /********* Render comments *********/
        if (array_key_exists('comments', $page) && count($page['comments']) !== 0 ) {
            $client_comments = $page['comments'];
            $comments = $pageModel->group->inspection->comments;
            $this->renderCommentButtons($fpdi, $comments, $pdf->reference);

            foreach ($client_comments as $c) {
                $fp = FailurePosition::with(['failure'])->find($c['failurePositionId']);
                $p = coordinateTransformation($fp->point, $pdf->area);

                $lx = $p['x'];
                $ly = $p['y'];
                $lw = 18;
                $lh = 5;

                $fpdi->Circle($lx, $ly, 3, 0, 360, 'F', '', [78, 143, 12]);
                $fpdi->Circle($lx, $ly, 2.8, 0, 360, 'F', '', [255, 255, 255]);
                $fpdi->SetTextColor(78, 143, 12);
                $fpdi->SetFont('kozgopromedium', '', 10);

                $label = $fp->failure->sort;
                if ($label < 10) {
                    $fpdi->Text($lx-1, $ly-2.2, $label);
                }
                elseif (9 < $label && $label < 100) {
                    $fpdi->Text($lx-2, $ly-2.2, $label);
                }

                $fpdi->Rect($lx-($lw/2), $ly-($lh/2)-6, $lw, $lh, 'DF', ['LTRB' => ['color' => [78, 143, 12]]], [255,255,255]);
                $fpdi->Text($lx-6.5, $ly-8.2, '手直し'.$c['commentId']);
            }
        }

        $now = Carbon::now()->timestamp;

        $file_name = $now.'.pdf';
        $pdf_path = storage_path('pdf/'.$file_name);
        $fpdi->output($pdf_path, 'F');

        return 'ok';
    }
}

