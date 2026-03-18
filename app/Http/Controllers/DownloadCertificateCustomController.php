<?php

namespace App\Http\Controllers;



use App\Models\Course;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mpdf\Mpdf;

class DownloadCertificateCustomController extends Controller
{
    private $user;
    private $course;
    private $layouts_url;
    private $layout;
    public function __construct()
    {
        parent::__construct();

        // $this->layout = 'default';
        // $this->layouts_url = asset("resources/views/custom/certificate");
    }

    public function index($course_id)
    {

        if (!Auth::check()) :
            abort(404);
        endif;
        $this->user = Auth::user();
        $this->course = Course::find($course_id);
        $certificate = Certificate::where('user_id', $this->user->id)
            ->where('course_id', $course_id)
            ->first();

        $sql = "SELECT DATE_FORMAT(completed_at, '%Y-%m-%d') AS completed_date FROM completes WHERE user_id = {$this->user->id} AND completed_course_id = {$course_id};";
        $completed = DB::select($sql);
        // $sql = "SELECT DATE_FORMAT(completed_at, '%Y-%m-%d') AS completed_date FROM completes WHERE user_id = 16 AND completed_course_id = 3;";
        // $completed = DB::select($sql);

        $signature_id = get_option('certificate.signature_id');
        $signature_src = $signature_id ? media_image_uri($signature_id)->original : null;

        $authorise_name = get_option('certificate.authorise_name');
        $company_name = get_option('certificate.company_name');

        $template_id = get_option('certificate.template');
        $template_src = $template_id ? media_image_uri($template_id)->original : null;

        $html = view('custom.certificate.certificate', [
            'user' => $this->user,
            'course' => $this->course,
            'completed' => $completed[0]->completed_date,
            'signature_src' => $signature_src,
            'authorise_name' => $authorise_name,
            'company_name' => $company_name,
            'template_src' => $template_src
        ])->render();

        $this->generateCertificatePDF($html);
    }
    private function generateCertificatePDF($html)
    {
        $autoloadUrl = app_path('CustomPlugins/vendor/autoload.php'); // Adjust the path as necessary
        require_once $autoloadUrl;

        $mpdf = new Mpdf([
            'format' => 'A4-L',
            'default_font' => 'sans-serif',
            'default_font_size' => 12,
            'setAutoTopMargin' => 'stretch',
            'setAutoBottomMargin' => 'stretch',
        ]);
        $mpdf->WriteHTML($html);
        $mpdf->Output("Certificate.pdf", "I");
    }
}
