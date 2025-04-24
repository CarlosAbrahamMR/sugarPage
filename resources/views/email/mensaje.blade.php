<!DOCTYPE html>
<html class="hide-sidebar ls-bottom-footer" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>MySugarFan</title>

</head>

<body>

<div>

    <h4 style="font-size: 18px; font-family: inherit;font-weight: 500;line-height: 1.1;color: inherit;">
        Hola {{ $datos['nombre'] }}
    </h4>
    <div style="margin-bottom: 20px;background-color: #ffffff;border: 1px solid transparent;border-radius: 0;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);box-sizing: border-box;padding: 20px;">
        <div>
            <p>{{ $datos['mensaje'] }}</p>
        </div>
    </div>
</div>
</body>

</html>
