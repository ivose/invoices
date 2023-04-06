<!-- resources/views/auth/login.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h2>{{ __('A little statistics') }}</h2>
            <ul>
                <li>
                    <b>{{ __('Total invoices') }}:</b> {{ number_format($inv_count, 0, ',', ' ') }} {{ __('tk.') }}
                </li>
                <li>
                    <b>{{ __('Average items count') }}:</b> {{ number_format($avg_items, 2, ',', ' ') }} {{ __('tk.') }}
                </li>
                <li>
                    <b>{{ __('Average product price') }}:</b> {{ number_format($avg_price, 2, ',', ' ') }} €
                </li>
                <li>
                    <b>{{ __('Average sum of invoice') }}:</b> {{ number_format($avg_sum, 2, ',', ' ') }} €
                </li>
                <li>
                    <b>{{ __('Minimum number of items') }}:</b> {{ number_format($min_items, 0, ',', ' ') }} €
                </li>
                <li>
                    <b>{{ __('Maximum number of items') }}:</b> {{ number_format($max_items, 0, ',', ' ') }} €
                </li>
                <li>
                    <b>{{ __('The sheapest invoice') }}:</b> {{ number_format($the_seapest_inv, 2, ',', ' ') }} €
                </li>
                <li>
                    <b>{{ __('The most expensive invoice') }}:</b>
                    {{ number_format($the_most_expensive_inv, 2, ',', ' ') }} €
                </li>
                <!--.. here tens of values.-->
            </ul>

            <h2>{{ __('Invoices list') }}</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">{{ __('Nr.') }}</th>
                        <th scope="col">{{ __('Reference') }}</th>
                        <th scope="col">{{ __('Items') }}</th>
                        <th scope="col">{{ __('Total price') }}</th>
                        <th scope="col">{{ __('Creted') }}</th>
                        <th scope="col">{{ __('Updated') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $k => $invoice)
                        <tr>
                            <th scope="row">{{ $k + 1 }}</th>
                            <td><a href="{{ route('invoice', $invoice->id) }}">{{ $invoice->reference }}</a></td>
                            <td>{{ number_format($invoice->itemscount, 0, ',', ' ') }}</td>
                            <td>{{ number_format($invoice->totalprice, 2, ',', ' ') }} €</td>
                            <td>{{ date_format($invoice->created_at, 'd.m.Y') }}</td>
                            <td>{{ date_format($invoice->updated_at, 'd.m.Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
