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

    /**
     * Get user from JWT token
     */
    public function printByTemplate(Request $request)
    {
        $family = $request->family;
        if (!$family) {
            throw new StoreResourceFailedException('JSON in Request body should contain family key');
        }

        $request = new DummyRequest;
        $controller = new InspectionController;

        // Get data from Inspection-Controller.
        $request->set($family['process'], $family['inspection'], $family['division'], $family['line']);
        $group = $controller->inspection($request)['group'];

        $fpdi = $this->createFPDI();

        /*
         * 今後は複数ページも印刷するのでループを回す
         */
        $page = $family['pages'][0];
        $pdf_path = PageType::find($page['pageTypeId'])->pdf_path;

        // Add new page from template to page 1.
        $fpdi->AddPage();
        $fpdi->setSourceFile(public_path().'/pdf/template/'.$pdf_path);
        $tplIdx = $fpdi->importPage(1);
        $fpdi->useTemplate($tplIdx, null, null, 297, 210, true);

        $fpdi->SetFont('kozgopromedium', '', 16);
        $fpdi->SetTextColor(255, 255, 255);
        $fpdi->Text(250, 3, $family['date']);

        $fpdi->SetFont('kozgopromedium', '', 14);
        $fpdi->SetTextColor(0, 0, 0);

        $fpdi->Text(223, 18, $family['inspectorGroup'].'　'.$family['inspector']);
        $fpdi->Text(286, 18, $family['table']);

        foreach ($page['parts'] as $part) {
            $part_type = PartType::find($part['partTypeId']);
            $fpdi->Text(14, 18, $part_type['vehicle_num']);
            $fpdi->Text(44, 18, $part_type['pn']);
            $fpdi->Text(78, 18, $part_type['name']);
            $fpdi->Text(144, 18, $part['panelId']);
            $fpdi->Text(196, 18, $part['status']);
        }

        // Render failures
        $i_failures = $group['failures']->filter(function ($f) {
            return $f['type'] == 1;
        })->values();

        $n_failures = $group['failures']->filter(function ($f) {
            return $f['type'] == 2;
        })->values();

        foreach ($i_failures as $i => $failure) {
            $x0 = 12;
            $y0 = 32;
            $lh = 10;
            $cw = 40;

            if ($i < 7) {
                $fpdi->RoundedRect($i*$cw+$x0-2, $y0-1, $cw-2, $lh-2, 1, '1111');
                if (intval($failure['label']) > 9) {
                    $fpdi->Text($i*$cw+$x0-1, $y0, $failure['label']);
                } else {
                    $fpdi->Text($i*$cw+$x0+0.5, $y0, $failure['label']);
                }
                $fpdi->Text($i*$cw+$x0+6, $y0, $failure['name']);
                $fpdi->Circle($i*$cw+$x0+2, $y0+3, 3.2, 0, 360, "D");
            }
        }

        foreach ($n_failures as $i => $failure) {
            $x0 = 12;
            $y0 = 186;
            $lh = 10;
            $cw = 40;

            if ($i < 7) {
                $fpdi->RoundedRect($i*$cw+$x0-2, $y0-1, $cw-2, $lh-2, 1, '1111');
                if (intval($failure['label']) > 9) {
                    $fpdi->Text($i*$cw+$x0-1, $y0, $failure['label']);
                } else {
                    $fpdi->Text($i*$cw+$x0+0.5, $y0, $failure['label']);
                }
                $fpdi->Text($i*$cw+$x0+6, $y0, $failure['name']);
                $fpdi->Circle($i*$cw+$x0+2, $y0+3, 3.2, 0, 360, "D");
            }
            elseif (7 <= $i && $i < 14) {
                $fpdi->RoundedRect(($i-7)*$cw+$x0-2, $y0+$lh-1, 38, $lh-2, 1, '1111');
                if (intval($failure['label']) > 9) {
                    $fpdi->Text(($i-7)*$cw+$x0-1, $y0+$lh, $failure['label']);
                } else {
                    $fpdi->Text(($i-7)*$cw+$x0+0.5, $y0+$lh, $failure['label']);
                }
                $fpdi->Text(($i-7)*$cw+$x0+6, $y0+$lh, $failure['name']);
                $fpdi->Circle(($i-7)*$cw+$x0+2, $y0+$lh+3, 3.2, 0, 360, "D");
            }
        }

        function coordinateTransformation($pixel) {
            $exploded = explode(',', $pixel);
            $px = $exploded[0];
            $py = $exploded[1];

            $x = $px*(273.1-23.9)/870+23.9;
            $y = $py*(180.55-43.75)/490+43.75;

            return ['x' => $x, 'y' => $y];
        }

        /********* Render failures *********/

        $client_failures = $page['failures'];

        function getFailureLabel($id, $failures) {
            return array_filter($failures, function($f) use ($id) {
                $f['id'] === $id;
            })['label'];
        }

        // Render failures
        if (isset($client_failures) && count($client_failures) !== 0 ) {
            $fpdi->SetFont('kozgopromedium', '', 12);
            $fpdi->SetTextColor(255,0,0);

            foreach ($client_failures as $cf) {
                $p = coordinateTransformation($cf['pointK']);
                $label = $group['failures']->filter(function($f) use ($cf) {
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

        $client_holes = $page['holes'];

        // Render holes
        if (isset($client_holes) && count($client_holes) !== 0 ) {
            
        }













        $pdf_path = storage_path() . '/test.pdf';
        $fpdi->output($pdf_path, 'F');

        return 'ok';
    }   




    /**
     * Get user from JWT token
     */
    public function printByHtml()
    {
        //PDF作成
        $tcpdf = new TCPDF();
        //フォント名,フォントスタイル（空文字でレギュラー）,フォントサイズ
        $tcpdf->setFont('kozminproregular','',10);
        $tcpdf->SetPrintHeader(false);
        $tcpdf->SetPrintFooter(false);
        //ページを追加
        $tcpdf->addPage();
        //viewから起こす
        $tcpdf->writeHTML(view("pdf.test")->render());
        //第一引数はファイル名、第二引数で挙動を指定（D=ダウンロード）
        $tcpdf->output('hoge' . '.pdf', 'D');
        return Redirect::back();
    }

    /**
     * Get user from JWT token
     */
    public function print()
    {
        $tcpdf = new TCPDF;
        $tcpdf->SetPrintHeader(false);
        $tcpdf->SetPrintFooter(false);
        $tcpdf->AddPage();
        $tcpdf->SetFont('kozminproregular', '', 12);
        $tcpdf->Text(10, 10, 'テストですよ');

        $tcpdf->Text(185, 249, 'Arrows');
        $style5 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 64, 128));
        $tcpdf->SetLineStyle($style5);
        $tcpdf->SetFillColor(255, 0, 0);
        $tcpdf->Arrow(200, 280, 185, 266, 0, 5, 15);
        $tcpdf->Arrow(200, 280, 190, 263, 1, 5, 15);
        $tcpdf->Arrow(200, 280, 195, 261, 2, 5, 15);
        $tcpdf->Arrow(200, 280, 200, 260, 3, 5, 15);

        $pdf_path = storage_path() . '/tcpdf-test01.pdf';
        $tcpdf->output($pdf_path, 'F');
        return \Response::download($pdf_path);
    }
}

