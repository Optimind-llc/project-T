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
    /**
     * Get user from JWT token
     */
    public function report()
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

