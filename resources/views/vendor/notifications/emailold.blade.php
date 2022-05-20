@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
    {{ $line }}
@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}
@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards'),<br>{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
@lang(
    "If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser: [:actionURL](:actionURL)',
    [
        'actionText' => $actionText,
        'actionURL' => $actionUrl,
    ]
)
@endcomponent
@endisset
@endcomponent

<table class="emailer" style="width: 600px; margin: 15px auto; color: rgb(0, 0, 0); border: 1px solid rgb(230, 230, 230);">
    <tbody>
    <tr class="text-center " style=" border-bottom:1px solid #dfdfdf; background: #f3f3f3;">
        <td align="center">
            <img src="{{url('public/front/images/logo/emailer.png')}}"
                 style="width: 150px; margin: 0px auto; padding: 20px 0px;">
        </td>
    </tr>

    <tr style=" text-align:center;">
        <td style="padding:25px 0px 0px 0px; font-size: 18px;">Hey  </td>
    </tr>
    <tr align="center">
        <td style="font-family: inherit;
            font-size: 23px;
			padding:10px 0px;
            color: #212127;
            line-height: 30px;
            font-weight: 500;">You are <span style="    color: #46c8f5;
    font-weight: 500;
    font-size: 28px;">welcome</span>
        </td>
    </tr>
    <tr align="center">
        <td style="    font-family: inherit;
    font-size: 20px;
    padding: 10px 0px 8px 0px;
    color: #212127;
    line-height: 30px;
    font-weight: 500;">please verify your  <span style="">email address</span>
        </td>
    </tr>

    <tr align="center">
        <td style="

    padding: 0px 0px;
    color: #212127;

    "> 		<p>If you did not create an account, no further action is required.</p>	</td>
    </tr>

    <tr align="center">
        <td style="margin-bottom:25px; display:block; padding:10px 0px 0px 0px;"><button type="button" style="    background: #46c8f5;
    border: none;
    color: #fff;
    padding: 10px 15px;
    border-radius: 5px;
    font-weight: 500;     cursor: pointer;"  >Click here to confirm</button></td>
    </tr>

    <tr>
        <td style="     margin-bottom: 30px;
    display: block; padding:0px 25px;">
            <p style=" color:#74787e; margin-top:0;text-align:center;font-size:12px">If you’re having trouble clicking the "Verify Email Address" button, copy and paste the URL below
                into your web browser: <a href="https://dawaaghar.com/email/verify/182?expires=1630406735&amp;signature=a70693350098d27a0d6dee6d32e10e9152df553e1efae01c42b13049323817e8" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#3869d4" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://dawaaghar.com/email/verify/182?expires%3D1630406735%26signature%3Da70693350098d27a0d6dee6d32e10e9152df553e1efae01c42b13049323817e8&amp;source=gmail&amp;ust=1630491471399000&amp;usg=AFQjCNELP0iELxF_7HgwrdynfRRIfLN5hg">https://dawaaghar.com/email/<wbr>verify/182?expires=1630406735&amp;<wbr>signature=<wbr>a70693350098d27a0d6dee6d32e10e<wbr>9152df553e1efae01c42b130493238<wbr>17e8</a></p>
        </td>
    </tr>


    </tbody>
</table>
