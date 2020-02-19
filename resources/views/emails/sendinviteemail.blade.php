@component('mail::message')
# Wooho You got an Invitation!

@component('mail::button', ['url' => route('accept.invite.team.member',$token)])
Activate
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
