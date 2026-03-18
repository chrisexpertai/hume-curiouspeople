<?php

namespace App\Plugins\Certificate\Http\Controllers;

use App\Models\Course;
use App\Models\Certificate;
use App\Http\Controllers\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    private $layouts_path;
    private $layouts_url;
    private $layout;
    private $layout_url;
    private $plugin_url;
    private $debug = false;
    private $user;
    private $course;

    public function __construct()
    {
        parent::__construct();

        $this->layouts_path = root_path('Plugins/CertificateAssets/layouts/');
        $this->layouts_url = asset("Plugins/CertificateAssets/layouts/");
        $this->plugin_url = asset("Plugins/CertificateAssets");

        $this->layout = 'default';

        $this->debug = true;

        $this->layouts_url = asset("Plugins/CertificateAssets/layouts/" . $this->layout);
    }


    public function generateCertificate($course_id)
    {
        if (!Auth::check()) {
            abort(404);
        }

        $this->user = Auth::user();


        $this->course = Course::find($course_id);
        if (!$this->course) {
            abort(404, 'Course Not Found');
        }

        // Check if user already has a certificate for this course
        $certificate = Certificate::where('user_id', $this->user->id)
            ->where('course_id', $course_id)
            ->first();

        // If user already has a certificate for this course, update it
        if ($certificate) {
            // Generate and update certificate PDF
            $certificateUrl = $this->generateCertificatePdf($certificate);

            // Update certificate URL in database
            $certificate->update(['certificate_url' => $certificateUrl]);

            // Redirect or return certificate URL, depending on your requirement
            return redirect()->back()->with('success', 'Certificate updated successfully');
        } else {
            // If user doesn't have a certificate for this course, create a new one
            $certificateData = [
                'user_id' => $this->user->id,
                'course_id' => $course_id,
                'certificate_url' => '', // Fill this with the URL of generated certificate
            ];

            // Create new certificate
            $certificate = Certificate::create($certificateData);

            // Generate certificate PDF
            $certificateUrl = $this->generateCertificatePdf($certificate);

            // Update certificate URL in database
            $certificate->update(['certificate_url' => $certificateUrl]);

            // Redirect or return certificate URL, depending on your requirement
            return redirect()->back()->with('success', 'Certificate generated successfully');
        }
    }



    // Separate method to generate PDF and return URL
    private function generateCertificatePdf($certificate)
    {
        // Include autoloader
        $autoloadUrl = app_path('Plugins/Certificate/vendor/autoload.php');
        require_once $autoloadUrl;

        $options = new Options(apply_filters('certificate_dompdf_options', array(
            'defaultFont' => 'sans',
            'isRemoteEnabled' => true,
            'isFontSubsettingEnabled' => true,
            // HTML5 parser requires iconv
            'isHtml5ParserEnabled' => extension_loaded('iconv') ? true : false,
        )));

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($this->content());
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        if ($this->debug) {
            $dompdf->stream("certificate.pdf", array("Attachment" => false));
            exit(0);
        }
        // Save PDF file
        $pdfFileName = 'certificate_' . $certificate->id . '.pdf';
        $pdfFilePath = storage_path('app/certificates/' . $pdfFileName);
        \file_put_contents($pdfFilePath, $dompdf->output());

        // Return URL of saved PDF
        return asset('storage/certificates/' . $pdfFileName);
    }




    public function content()
    {
        $certificate_path = $this->layouts_path . $this->layout;

        ob_start();
        include $certificate_path . '/certificate.php';
        $content = ob_get_clean();

        return $content;
    }


    public function certificateSettings()
    {
        $title = "Certificate Settings";
        return view('admin.extend.certificate.certificate_settings', compact('title'));
    }
}
