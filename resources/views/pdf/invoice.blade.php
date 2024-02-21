<html lang="en">
<head>
    <title>Invoice</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Inter", sans-serif;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .text-capitalize {
            text-transform: capitalize;
        }
    </style>
</head>
<body>

<div class="px-2 py-8 max-w-xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div class="flex">
            <img src="{{ asset('images/mocatec_logo.png') }}" alt="Logo" class="max-w-[50%] object-cover">
        </div>
        <div class="text-gray-700 w-1/2 text-right">
            <div class="font-semibold text-base">{{ __('filament/resources/invoice.label') }} #{{ $data->number }}</div>
            <div class="text-sm">{{ __('filament/resources/invoice.date') }}: {{ $data->due->format('Y-m-d') }}</div>
        </div>
    </div>
    <div class="pb-2 mb-2">
        <div class="text-gray-700 text-sm">
            <span class="font-semibold text-sm">{{ __('filament/resources/invoice.client') }}: </span> {{ $data->client->name }}
        </div>
        <div class="text-gray-700 text-sm">
            <span class="font-semibold text-sm">{{ __('filament/resources/invoice.contact') }}: </span> {{ $data->client->contact }}
        </div>
        <div class="text-gray-700 text-sm">
            <span class="font-semibold text-sm">{{ __('filament/resources/invoice.address') }}: </span> {{ $data->client->address }}
        </div>
        <div class="text-gray-700 text-sm">
            <span class="font-semibold text-sm">{{ __('filament/resources/invoice.phone') }}: </span> {{ $data->client->phone }}
        </div>
    </div>
    <div class="pb-2 mb-2">
        <div class="text-gray-700 text-sm">
            <span class="font-semibold text-sm">{{ __('filament/resources/invoice.beneficiary') }}: </span> {{ auth()->user()->name }}
        </div>
        <div class="text-gray-700 text-sm">
            <span class="font-semibold text-sm">{{ __('filament/resources/invoice.project') }}: </span> {{ $data->project }}
        </div>
    </div>
    <div class="-mx-4 mt-8 flow-root sm:mx-0">
        <table class="min-w-full">
            <colgroup>
                <col class="w-full sm:w-1/2">
                <col class="sm:w-1/6">
                <col class="sm:w-1/6">
                <col class="sm:w-1/6">
            </colgroup>
            <thead class="border-b border-gray-300 text-gray-900">
            <tr class="text-capitalize">
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                    {{ __('filament/resources/invoice.tasks.description') }}</th>
                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">{{ __('filament/resources/invoice.tasks.hours') }}</th>
                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">{{ __('filament/resources/invoice.tasks.rate') }}</th>
                <th scope="col" class="py-3.5 pl-3 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">{{ __('filament/resources/invoice.tasks.price') }}
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($data->items as $item)
                <tr class="border-b border-gray-200">
                    <td class="px-3 py-5 text-left text-sm text-gray-500">{{ $item->description }}</td>
                    <td class="px-3 py-5 text-right text-sm text-gray-500 text-center">{{ $item->hours }}</td>
                    <td class="px-3 py-5 text-right text-sm text-gray-500">{{ \App\Enums\Currency::symbol($data->currency->value) }} {{ $item->rate }}</td>
                    <td class="py-5 pl-3 pr-4 text-right text-sm text-gray-500 sm:pr-0">{{ \App\Enums\Currency::symbol($data->currency->value) }} {{ $item->price }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th scope="row" colspan="3"
                    class="hidden pl-4 pr-3 pt-6 text-right text-sm font-normal text-gray-500 sm:table-cell sm:pl-0 text-capitalize">
                    {{ __('filament/resources/invoice.subtotal') }}
                </th>
                <th scope="row" class="pl-4 pr-3 pt-6 text-left text-sm font-normal text-gray-500 sm:hidden text-capitalize">{{ __('filament/resources/invoice.subtotal') }}
                </th>
                <td class="pl-3 pr-4 pt-6 text-right text-sm text-gray-500 sm:pr-0">{{ $data->subtotal->formatted }}</td>
            </tr>
            <tr>
                <th scope="row" colspan="3"
                    class="hidden pl-4 pr-3 pt-4 text-right text-sm font-normal text-gray-500 sm:table-cell sm:pl-0">{{ __('filament/resources/invoice.tax') }}
                </th>
                <th scope="row" class="pl-4 pr-3 pt-4 text-left text-sm font-normal text-gray-500 sm:hidden text-capitalize">{{ __('filament/resources/invoice.tax') }}</th>
                <td class="pl-3 pr-4 pt-4 text-right text-sm text-gray-500 sm:pr-0">{{ $data->tax->formatted ?? '0%' }}</td>
            </tr>
            <tr>
                <th scope="row" colspan="3"
                    class="hidden pl-4 pr-3 pt-4 text-right text-sm font-semibold text-gray-900 sm:table-cell sm:pl-0">
                    {{ __('filament/resources/invoice.total') }}
                </th>
                <th scope="row" class="pl-4 pr-3 pt-4 text-left text-sm font-semibold text-gray-900 sm:hidden text-capitalize">{{ __('filament/resources/invoice.total') }}
                </th>
                <td class="pl-3 pr-4 pt-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">{{ $data->total->formatted }}</td>
            </tr>
            </tfoot>
        </table>
    </div>

    @if($data->bank)
        <div class="pt-8 mb-8">
            <div class="text-gray-700 text-base mb-2 font-semibold">{{ __('filament/resources/invoice.bank.label') }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('filament/resources/invoice.bank.location') }}: {{ $data->bank->location }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('filament/resources/invoice.bank.name') }}: {{ $data->bank->name }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('filament/resources/invoice.bank.address') }}: {{ $data->bank->address }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('filament/resources/invoice.bank.swift') }}
                : {{ $data->bank->swift }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('filament/resources/invoice.bank.iban') }}: {{ $data->bank->iban }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('filament/resources/invoice.bank.beneficiary.name') }}: {{ $data->bank->beneficiary_name }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('filament/resources/invoice.bank.beneficiary.address') }}: {{ $data->bank->beneficiary_address }}</div>
        </div>
    @endif
</div>

</body>
</html>
