@component('mail::message')
Hi,<br>
<strong>{{ $inviter->name }}</strong> has invited you to create an account on **{{ config('app.name') }}**, a powerful web app to annotate student responses. To get started, simply click on the link below and it will take you to the registration page.

@component('mail::button', ['url' => route('register')])
<strong>Register</strong>
@endcomponent

Please copy the following invitation code as you will need it to authenticate your invitation.

@component('mail::panel')
`Invitation Code: {{ $invitation_code }}`
@endcomponent

If you have any questions or concerns, you can reply directly to this email to contact {{ $inviter->name }}.

Thank you for considering this invitation.

Best regards,<br>
The {{ config('app.name') }} Team
@endcomponent
