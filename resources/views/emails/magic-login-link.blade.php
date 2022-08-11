@component('mail::message')
    Hello, to complete the login, please click on the link below
    @component('mail::button', ['url' => $url])
        Click to login
    @endcomponent
@endcomponent
