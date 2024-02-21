@component('mail::message')
<h1>{{ $subjectContent }}</h1>

<p>
    {{ $bodyContent }}
</p>

@component('mail::button', ['url' => \Illuminate\Support\Facades\Storage::url("invoices/{$invoice->number}.pdf")])
{{ __('filament/resources/invoice.mail.action') }}
@endcomponent

{{ __('filament/resources/invoice.mail.thanks') }},<br>
{{ config('app.name') }}
@endcomponent
