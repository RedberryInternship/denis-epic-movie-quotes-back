<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-color: #181624; color: #ffffff; font-family: sans-serif">

<div style="margin-top: 78px; margin-bottom: 72px;">
    <div style="margin: 0 auto; width: 22px">
        @include('components.assets.logo')
    </div>
    <h1 style="text-align: center; font-size: 12px; color: #DDCCAA; font-weight: 500">MOVIE QUOTES</h1>
</div>

<div style="margin-left: 10%; margin-right: 10%; margin-bottom: 100px">
    <p style="margin-bottom: 24px">Hola {{ $username }}!</p>
    <p style="margin-bottom: 40px">
        You are receiving this email because you requested a password reset for your account. Please click the button
        below to reset your password:
    </p>

    <a
        style="color: white; text-decoration: none; background-color: #E31221; border-bottom: 8px; border-top: 8px; border-left: 13px;
    border-right: 13px; border-color: #E31221; border-style: solid; border-radius: 4px; font-size: 16px"
        href="{{ $resetUrl }}"
    >
        Reset password
    </a>
    <p style="margin-bottom: 24px; margin-top: 48px">If clicking doesn't work, you can try copying and pasting it to
        your browser:</p>
    <a style="text-decoration: none; color: #DDCCAA; word-break: break-all; overflow-wrap: break-word;"
       href="{{ $resetUrl }}">{{ $resetUrl }}
    </a>
    <p style="margin-bottom: 24px; margin-top: 40px">
        If you have any problems, please contact us: support@moviequotes.ge
    </p>
    <p>MovieQuotes Crew</p>
</div>

</body>
</html>
