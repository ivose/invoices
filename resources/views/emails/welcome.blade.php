@component('mail::message')
    # Welcome, {{ $name }}!

    Thanks for signing up for My App. We're excited to have you on board!

    @component('mail::button', ['url' => 'http://localhost:8080'])
        Visit My App
    @endcomponent

    Thanks,<br>
    The My App Team
@endcomponent
