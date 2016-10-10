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

        return $fpdi;
    }

    /**
     * Get user from JWT token
     */
    public function printByTemplate(Request $request)
    {
        $fpdi = $this->createFPDI();
        $fpdi->AddPage(); // ページを追加

        $group_id = 1;

        // テンプレートを読み込み
        $fpdi->setSourceFile('/app/web/public/pdf/template/molding-inner.pdf');

        // 読み込んだPDFの1ページ目のインデックスを取得
        $tplIdx = $fpdi->importPage(1);

        // 読み込んだPDFの1ページ目をテンプレートとして使用
        $fpdi->useTemplate($tplIdx, null, null, null, null, true);

        $fpdi->SetFont('kozminproregular', '', 12);
        // 1. Arial Uni CID0 (arialunicid0)
        // 2. 小塚ゴシックPro M (kozgopromedium)
        // 3. 小塚明朝Pro M (kozminproregular)
        // 4. HYSMyeongJoStd-Medium (hysmyeongjostdmedium)
        // 5. MSungStd-Light (msungstdlight)
        // 6. STSongStd-Light (stsongstdlight)


        $fpdi->SetTextColor(255, 255, 255);
        $fpdi->Text(70, 2, '成型工程　ライン１　インナー検査結果記録票');

        $fpdi->SetFont('kozminproregular', '', 9);
        $fpdi->SetTextColor(0, 0, 0);

        $fpdi->Text(10, 13, '680A');
        $fpdi->Text(32, 13, '67149');
        $fpdi->Text(54, 13, 'バックドアインナ');
        $fpdi->Text(94, 13, '16/12/12');
        $fpdi->Text(127, 13, '161001YA001');
        $fpdi->Text(165, 13, '黄直　佐々木');
        $fpdi->Text(202, 13, 'A');




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

