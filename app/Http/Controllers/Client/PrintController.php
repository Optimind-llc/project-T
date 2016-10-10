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
use App\Models\Inspector;
use App\Models\InspectorGroup;
use App\Models\Inspection;
use App\Models\Division;
use App\Models\Client\InspectionFamily;
use App\Models\Client\Page;
use App\Models\Client\Part;
use App\Models\Client\FailurePage;
// Exceptions
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $fpdi->SetAuthor('Mapping system');
        // $fpdi->SetTitle('TCPDF Example 009');
        // $fpdi->SetSubject('TCPDF Tutorial');
        // $fpdi->SetKeywords('TCPDF, PDF, example, test, guide');

        // // フォントを登録
        // $fontPathRegular = $this->getLibPath() . '/tcpdf/fonts/migmix-2p-regular.ttf';
        // $regularFont = $fpdi->addTTFfont($fontPathRegular, '', '', 32);

        // $fontPathBold = $this->getLibPath() . '/tcpdf/fonts/migmix-2p-bold.ttf';
        // $boldFont = $fpdi->addTTFfont($fontPathBold, '', '', 32);

        $fpdi->SetFont('kozgopromedium', '', 12);

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

        $fpdi = $this->createFPDI();
        $fpdi->AddPage(); // ページを追加


        // return $family;


        // テンプレートを1ページ目に
        $fpdi->setSourceFile('/app/web/public/pdf/template/molding-inner.pdf');
        $tplIdx = $fpdi->importPage(1);
        $fpdi->useTemplate($tplIdx, null, null, null, null, true);

        $fpdi->SetTextColor(255, 255, 255);
        $fpdi->Text(186, 2, $family['date']);

        $page = $family['pages'][0];

        $fpdi->SetFont('kozgopromedium', '', 9);
        $fpdi->SetTextColor(0, 0, 0);

        $fpdi->Text(158, 13, $family['inspectorGroup'].'　'.$family['inspector']);
        $fpdi->Text(202, 13, $family['table']);


        foreach ($page['parts'] as $part) {
            $part_type = Part::find($part['partTypeId']);
            $fpdi->Text(10, 13, $part_type['vehicle_num']);
            $fpdi->Text(32, 13, $part_type['pn']);
            $fpdi->Text(54, 13, $part_type['name']);
            $fpdi->Text(97, 13, $part['panelId']);
            $fpdi->Text(138, 13, $part['status']);
        }

        $c_numbers = ['①','②','③','④','⑤','⑥','⑦','⑧','⑨','⑩','⑪','⑫','⑬','⑭'];
        $i_failures = ['キズ', '凸', '凹', 'ワレ・ヒビ', 'ヒケ', 'シボかすれ', '異物混入'];
        $n_failures = ['白斑店','樹脂抜け','寸欠','リブ抜け','バリ残り','素材判定NG','外周仕上げNG','穴糸残り','接着剤はみ出し','接着剤付着','リテーナー欠','ほにゃらら','あいうえおかきく','その他'];

        foreach ($i_failures as $n => $failure) {
            $fpdi->RoundedRect($n*30+1, 24, 28, 6, 1, '1111');
            $fpdi->Text($n*30+2, 25, $c_numbers[$n].' '.$failure);
        }

        // $fpdi->RoundedRect(1, 24, 28, 6, 1, '1111');
        // $fpdi->Text(2, 25, ' ');
        // $fpdi->RoundedRect(31, 24, 28, 6, 1, '1111');
        // $fpdi->Text(32, 25, ' ');
        // $fpdi->RoundedRect(61, 24, 28, 6, 1, '1111');
        // $fpdi->Text(62, 25, ' ');
        // $fpdi->RoundedRect(91, 24, 28, 6, 1, '1111');
        // $fpdi->Text(92, 25, ' ');
        // $fpdi->RoundedRect(121, 24, 28, 6, 1, '1111');
        // $fpdi->Text(122, 25, ' ');
        // $fpdi->RoundedRect(151, 24, 28, 6, 1, '1111');
        // $fpdi->Text(152, 25, ' ');
        // $fpdi->RoundedRect(181, 24, 28, 6, 1, '1111');
        // $fpdi->Text(182, 25, ' ');

        $fpdi->Circle(56.5, 67, 2, 0, 360, 'F', '', [255, 255, 255]);
        $fpdi->Text(54, 65, '①');
        $html = '<p style="font-weight: bold;">①</p>';
        $fpdi->writeHTMLCell(0, 0, 54, 65, $html, 0, 0, 0, false, 'L', false);


        // $style5 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 64, 128));
        // $fpdi->SetLineStyle($style5);
        // $fpdi->SetFillColor(255, 0, 0);
        // $fpdi->Arrow(200, 280, 85, 166, 0, 5, 15);
        // $fpdi->Arrow(200, 280, 90, 163, 1, 5, 15);
        // $fpdi->Arrow(200, 280, 95, 161, 2, 5, 15);


        $fpdi->output('hoge' . '.pdf', 'D');
        return Redirect::back();
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

