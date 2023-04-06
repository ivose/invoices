<!-- resources/views/auth/login.blade.php -->

@extends('layouts.app')

@section('css')
    <style>
        .tright {
            text-align: right;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <p><a href="{{ route('home') }}">{{ __('Back') }}</a></p>
            <h2>{{ __('Invoice') }}: {{ $invoice->reference }}</h2>
            <ul>
                <li>
                    <b>{{ __('Items') }}:</b> {{ number_format($cnt = $invoice->items->count(), 0, ',', ' ') }}
                    {{ __('tk.') }}
                </li>
                <li>
                    <b>{{ __('Total price') }}:</b> {{ number_format($tp = $invoice->totalPrice(), 2, ',', ' ') }} €
                </li>
                <li>
                    <b>{{ __('Average price') }}:</b> {{ number_format($cnt ? $tp / $cnt : 0, 2, ',', ' ') }} €
                </li>
            </ul>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td></td>
                        <th>{{ __('Bar Code') }}</th>
                        <th class="text-end">{{ __('Quantity') }}</th>
                        <th class="text-end">{{ __('Price') }}</th>
                        <th class="text-end">{{ __('Total price') }}</th>
                        <th class="text-end">{{ __('VAT rate') }}</th>
                        <th class="text-end">{{ __('VAT value') }}</th>
                        <th class="text-end">{{ __('Gross value') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $k => $item)
                        <tr>
                            <td>{{ $k + 1 }}</td>
                            <td colspan="7">{{ $item->name }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>{{ $item->barcode }}</td>
                            <td class="text-end">{{ number_format($item->quantity, 0, ',', ' ') }} {{ $item->unit }}
                            </td>
                            <td class="text-end">{{ number_format($item->price, 2, ',', ' ') }} €</td>
                            <td class="text-end">{{ number_format($item->price_total, 2, ',', ' ') }} €</td>
                            <td class="text-end">{{ number_format($item->vat_rate, 0, ',', ' ') }} %</td>
                            <td class="text-end">{{ number_format($item->vat_value, 2, ',', ' ') }} €</td>
                            <td class="text-end">{{ number_format($item->gross_value, 2, ',', ' ') }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
