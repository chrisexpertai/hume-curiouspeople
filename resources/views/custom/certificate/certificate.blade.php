<!-- resources/views/certificate.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate | {{ $user->name }}</title>
    <style>
        body {
            font-family: sans-serif;
        }

        p {
            padding: none !important;
            margin: none !important;
        }

        .certificate-wrap {
            width: 100%;
            text-align: center;
            position: relative;
            padding-top: 340px;
        }


        .student-name,
        .course-title-wrap,
        .issued-date {
            z-index: 3;
        }

        .signature-image-wrap {
            z-index: 3;
            margin-top: 125px;
            padding-left: 440px;
        }

        #watermark {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        #watermark img {
            width: 100%;
            height: 100%;
        }

        .students_name {
            font-family: Georgia, 'Times New Roman', Times, serif;
            font-size: 25px;
            font-weight: 50 !important;
            color: rgb(70, 69, 69);
        }

        .course-title-wrap,
        .issued-date {
            font-family: Georgia, 'Times New Roman', Times, serif;
            color: rgb(70, 69, 69);
        }

        .certificate-banner {
            z-index: 2;
            position: absolute;
            margin-top: -100px;
        }
    </style>
</head>


<body>
    @if ($course->certificate_banner)
        <div class="certificate-banner" style="">
            {{-- <img style="margin-left: 100px;" src="{{ $course->certificate_banner }}" width="80%"
                alt="{{ $course->title }} Certificate Banner" /> --}}
        </div>
    @endif
    <div class="certificate-wrap">
        <div class="student-name" style="margin-top:30px;">
            <p class="students_name">
                <strong>{{ strtoupper($user->name) }}</strong>
            </p>
        </div>
        <div class="course-title-wrap" style="margin-top:8px;font-size:30px;">
            <p><strong>{{ $course->title }}</strong></p>
        </div>
        <div class="issued-date" style="margin-top:15px;font-size:22px;">
            <p>
                <strong>
                    @php
                        echo date('d/m/Y', strtotime($completed));
                    @endphp
                </strong>
            </p>
        </div>
    </div>
    @if ($signature_src)
        <div class="signature-image-wrap">
            <img src="{{ $signature_src }}" width="20%" alt="Signature Image" />
        </div>
    @endif
    <div id="watermark">
        @if ($template_src)
            <img src="{{ $template_src }}" width="100%" height="100%" alt="Certificate of Completion" />
        @endif
    </div>
</body>

</html>
