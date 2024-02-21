@component('mail::message')
<h1>{{ $subjectContent }}</h1>

<p>
    {{ $bodyContent }}
</p>

@component('mail::button', ['url' => \Illuminate\Support\Facades\Storage::url("invoices/{$invoice->number}.pdf")])
{{ __('invoice.mail.action') }}
@endcomponent

{{ __('invoice.mail.thanks') }},<br>
{{ config('app.name') }}
@endcomponent
