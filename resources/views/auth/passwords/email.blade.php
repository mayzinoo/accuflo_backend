@extends('layouts.app-without-nav')

@section('content')
    <div class="row" style="flex-direction: column;">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="main card">
            <div class="logo"></div>
            <div class="title">{{ __('Reset Password') }}</div>
            <form action="{{ route('password.email') }}" method="post">
                @csrf
                <div class="credentials">
                    <div class="username">
                        <span><i class="fas fa-envelope"></i></span>
                        <input type="text" name="email" value="{{ old('email') }}" placeholder="Email" required=""
                            autocomplete="off">

                    </div>
                    @error('email')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror


                </div>
                <button type="submit" class="submit" style="margin-top:5px;">{{ __('Send Password Reset Link') }}</button>
            </form>

        </div>
    </div>
@endsection
