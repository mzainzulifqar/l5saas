@component('mail::message')
# Please Activate Your Account

@component('mail::button', ['url' => route('acivate.account',$token)])
Activate
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
