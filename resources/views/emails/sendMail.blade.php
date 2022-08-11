@component('mail::message')
 <h1>{{ $mailData['title'] }}</h1>
 <p>{{ $mailData['body'] }}</p>
Thanks,<br>
{{ config('app.name') }}
@endcomponent


