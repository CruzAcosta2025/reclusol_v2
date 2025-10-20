<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            background: #111;
        }

        .frame {
            width: 100%;
            height: 100%;
            border: 0;
            background: #222;
        }
    </style>
</head>

<body>
    <iframe class="frame" src="{{ $streamUrl }}#view=FitH"></iframe>
</body>

</html>