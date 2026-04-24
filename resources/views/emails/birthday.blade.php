<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #f0f0f0;
        }

        .banner {
            width: 100%;
            height: auto;
            border-radius: 4px;
            display: block;
        }

        .intro-text {
            font-size: 1.1em;
            margin-top: 20px;
        }

        .country-section {
            margin-top: 25px;
        }

        .company-header {
            color: #0056b3;
            font-size: 1.2em;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .employee-list {
            list-style: none;
            padding-left: 20px;
            margin: 0;
        }

        .employee-item {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .phrase-box {
            background-color: #fdf6e3;
            border-left: 5px solid #d3af37;
            padding: 20px;
            margin: 30px 0;
            font-style: italic;
        }

        .footer {
            font-size: 0.85em;
            color: #999;
            margin-top: 40px;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ $config->banner_url }}" alt="Banner Cumpleaños" class="banner">

        <p class="intro-text">
            {!! nl2br(e($config->intro_text)) !!}
        </p>

        @foreach ($data['birthdays'] as $country => $companies)
            <div class="country-section">
                @foreach ($companies as $companyName => $employees)
                    <h3 class="company-header">📍 {{ $country }} - {{ $companyName }}:</h3>
                    <ul class="employee-list">
                        @foreach ($employees as $employee)
                            <li class="employee-item">🎂 {{ $employee->Nombre }}</li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        @endforeach

        <div style="margin-top: 25px;">
            <p>{!! nl2br(e($config->main_body)) !!}</p>
            <p><strong>{{ $config->closing_text }}</strong></p>
        </div>

        <div class="phrase-box">
            <p style="margin:0; color: #856404;">"{{ $data['phrase'] }}"</p>
        </div>

        <div class="footer">
            <p>Atentamente,<br>
                <strong>{{ $config->sign_off }}</strong><br>
                &copy; {{ date('Y') }} OBGROUP
            </p>
        </div>
    </div>
</body>

</html>
