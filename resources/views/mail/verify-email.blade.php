<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-color: #181624; color: #ffffff; font-family: sans-serif">

<div style="padding-top: 78px;">
    <div style="margin: 0 auto; width: 22px">
        @include('components.assets.logo')
    </div>
    <h1 style="text-align: center; font-size: 12px; color: #DDCCAA; font-weight: 500">MOVIE QUOTES</h1>
</div>

<div style="margin-left: 10%; margin-right: 10%; margin-bottom: 100px">
    <p style="margin-bottom: 24px">{{ __('mail.hi') }} {{ $username }}!</p>
    <p style="margin-bottom: 40px">
        @if($is_secondary_email)
            {{ __('mail.verify_new_email') }}
        @else
            {{ __('mail.register_thanks') }}
        @endif
    </p>

    <a
        style="color: white; text-decoration: none; background-color: #E31221; border-bottom: 8px; border-top: 8px; border-left: 13px;
    border-right: 13px; border-color: #E31221; border-style: solid; border-radius: 4px; font-size: 16px"
        href="{{ $verification_url }}"
    >
        {{ __('mail.verify') }}
    </a>
    <p style="margin-bottom: 24px; margin-top: 48px">{{ __('mail.copy_instruction') }}</p>
    <a style="text-decoration: none; color: #DDCCAA; word-break: break-all; overflow-wrap: break-word;"
       href="{{ $verification_url }}">{{ $verification_url }}
    </a>
    <p style="margin-bottom: 24px; margin-top: 40px">{{ __('mail.support_suggestion') }}: support@moviequotes.ge</p>
    <p style="padding-bottom: 72px">MovieQuotes {{ __('mail.crew') }}</p>
</div>

</body>
</html>
