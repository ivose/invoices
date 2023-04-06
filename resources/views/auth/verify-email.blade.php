@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">{{ __('Verify Your Email Address') }}</div>
        <div class="card-body">
            @if (session('warning'))
                <div class="alert alert-warning" role="alert">
                    {{ session('warning') }}
                </div>
            @endif
            <p>{{ __('Before proceeding, please check your email for a verification link.') }}</p>
            <p>{{ __('If you did not receive the email') }}, <a
                    href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.</p>
        </div>
    </div>
@endsection
