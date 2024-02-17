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
</head>
<body>

<div class="px-2 py-8 max-w-xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div class="flex">
            <img src="{{ asset('images/mocatec_logo.png') }}" alt="Logo" class="max-w-[50%] object-cover">
        </div>
        <div class="text-gray-700 w-1/2 text-right">
            <div class="font-semibold text-base">Invoice #{{ $data->number }}</div>
            <div class="text-sm">Date: {{ $data->due->format('Y-m-d') }}</div>
        </div>
    </div>
    <div class="pb-2 mb-2">
        <div class="text-gray-700 text-sm">
            <span class="font-semibold text-sm">{{ __('Client') }}: </span> {{ $data->client->name }}
        </div>
        <div class="text-gray-700 text-sm">
            <span class="font-semibold text-sm">{{ __('Contact') }}: </span> {{ $data->client->contact }}
        </div>
        <div class="text-gray-700 text-sm">
            <span class="font-semibold text-sm">{{ __('Address') }}: </span> {{ $data->client->address }}
        </div>
        <div class="text-gray-700 text-sm">
            <span class="font-semibold text-sm">{{ __('Phone') }}: </span> {{ $data->client->phone }}
        </div>
    </div>
    <div class="pb-2 mb-2">
        <div class="text-gray-700 text-sm">
            <span class="font-semibold text-sm">{{ __('Beneficiary') }}: </span> {{ auth()->user()->name }}
        </div>
        <div class="text-gray-700 text-sm">
            <span class="font-semibold text-sm">{{ __('Activity') }}: </span> {{ $data->subject }}
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
            <tr>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Description</th>
                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Hours</th>
                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Rate</th>
                <th scope="col" class="py-3.5 pl-3 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">Price
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($data->items as $item)
                <tr class="border-b border-gray-200">
                    <td class="px-3 py-5 text-left text-sm text-gray-500">{{ $item['description'] }}</td>
                    <td class="px-3 py-5 text-right text-sm text-gray-500 text-center">{{ $item['hours'] }}</td>
                    <td class="px-3 py-5 text-right text-sm text-gray-500">{{ $item['rate'] }}</td>
                    <td class="py-5 pl-3 pr-4 text-right text-sm text-gray-500 sm:pr-0">{{ $item['price'] }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th scope="row" colspan="3"
                    class="hidden pl-4 pr-3 pt-6 text-right text-sm font-normal text-gray-500 sm:table-cell sm:pl-0">
                    Subtotal
                </th>
                <th scope="row" class="pl-4 pr-3 pt-6 text-left text-sm font-normal text-gray-500 sm:hidden">Subtotal
                </th>
                <td class="pl-3 pr-4 pt-6 text-right text-sm text-gray-500 sm:pr-0">${{ number_format($data->total, 2) }}</td>
            </tr>
            <tr>
                <th scope="row" colspan="3"
                    class="hidden pl-4 pr-3 pt-4 text-right text-sm font-normal text-gray-500 sm:table-cell sm:pl-0">Tax
                </th>
                <th scope="row" class="pl-4 pr-3 pt-4 text-left text-sm font-normal text-gray-500 sm:hidden">Tax</th>
                <td class="pl-3 pr-4 pt-4 text-right text-sm text-gray-500 sm:pr-0">{{ $data->tax ?? '0%' }}</td>
            </tr>
            <tr>
                <th scope="row" colspan="3"
                    class="hidden pl-4 pr-3 pt-4 text-right text-sm font-semibold text-gray-900 sm:table-cell sm:pl-0">
                    Total
                </th>
                <th scope="row" class="pl-4 pr-3 pt-4 text-left text-sm font-semibold text-gray-900 sm:hidden">Total
                </th>
                <td class="pl-3 pr-4 pt-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">${{ number_format($data->total, 2) }}</td>
            </tr>
            </tfoot>
        </table>
    </div>

    @if($data->bank)
        <div class="pt-8 mb-8">
            <div class="text-gray-700 text-base mb-2 font-semibold">{{ __('Bank Data') }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('Beneficiary bank location') }}: {{ $data->bank->location }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('Beneficiary bank name') }}: {{ $data->bank->name }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('Beneficiary bank address') }}: {{ $data->bank->address }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('Beneficiary bank code / SWIFT address') }}
                : {{ $data->bank->swift }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('Beneficiary bank number / IBAN') }}: {{ $data->bank->iban }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('Beneficiary name') }}: {{ $data->bank->beneficiary_name }}</div>
            <div class="text-gray-700 text-sm mb-2">{{ __('Beneficiary address') }}: {{ $data->bank->beneficiary_address }}</div>
        </div>
    @endif
</div>

</body>
</html>
