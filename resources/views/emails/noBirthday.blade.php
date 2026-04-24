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

        .intro-text {
            font-size: 1.1em;
            margin-top: 10px;
            color: #444;
        }

        .phrase-box {
            background-color: #f4f7fa;
            border-top: 3px solid #0056b3;
            padding: 30px;
            margin: 35px 0;
            text-align: center;
            border-radius: 0 0 8px 8px;
        }

        .phrase-title {
            font-weight: bold;
            color: #0056b3;
            display: block;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        .phrase-text {
            font-size: 1.3em;
            color: #333;
            font-style: italic;
            font-weight: 300;
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
        <p class="intro-text">
            {!! nl2br(e($config->intro_text)) !!}
        </p>

        <div class="phrase-box">
            <span class="phrase-title">{{ $config->main_body }}</span>
            <p class="phrase-text">"{{ $data['phrase'] }}"</p>
        </div>

        <p>{!! nl2br(e($config->closing_text)) !!}</p>

        <div class="footer">
            <p>Atentamente,<br>
                <strong>{{ $config->sign_off }}</strong><br>
                &copy; {{ date('Y') }} OBGROUP
            </p>
        </div>
    </div>
</body>

</html>
