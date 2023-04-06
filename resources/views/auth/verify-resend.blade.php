@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">{{ __('Resend Verification Email') }}</div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-primary">{{ __('Resend Verification Email') }}</button>
            </form>
        </div>
    </div>
@endsection
