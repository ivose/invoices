<!-- resources/views/auth/login.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Upload new invoice') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="pdf" class="form-label">{{ __('Select a PDF file:') }}</label>

                                <input id="pdf" type="file" accept="application/pdf"
                                    class="form-control @error('pdf') is-invalid @enderror" name="pdf"
                                    value="{{ old('pdf') }}" required autocomplete="pdf" autofocus>

                                @error('pdf')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ __('Laadi üles') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
