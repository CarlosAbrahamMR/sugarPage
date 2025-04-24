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
            Hola {{ $name }}, gracias por registrarte en {{ $appName }}
        </h4>
        <div style="margin-bottom: 20px;background-color: #ffffff;border: 1px solid transparent;border-radius: 0;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);box-sizing: border-box;padding: 20px;">
            <div>
                <p>Por favor confirma tu correo electrónico. Para ello simplemente debes hacer click en el siguiente enlace:</p>
                    <a href="{{ url('/verification/register/' . $confirmation_code) }}" style="color:#ffffff; background-color:#4193d0; border-color:#4193d0;display: inline-block;margin-bottom: 0;font-weight: 500;text-align: center;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;cursor: pointer;background-image: none;border: 1px solid transparent;white-space: nowrap;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;border-radius: 4px;text-decoration:none;font-family: Helvetica, Arial, sans-serif;box-sizing: border-box;">
                        Clic para confirmar tu email
                    </a>        
            </div>
        </div>
    </div>
</body>

</html>