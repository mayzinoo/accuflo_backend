@extends('layouts.app-without-nav')
@section('content')
    <section class="content">
        <div class="card">
            <div class="main">
                <div class="logo"></div>
                <div class="title">Login</div>
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="credentials">
                        <div class="email">
                            <span><i class="fas fa-envelope"></i></span>
                            <input type="text" name="email" value="{{ old('email') }}" placeholder="Email"
                                required="" autocomplete="off">

                        </div>
                        @error('email')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror

                        <div class="password">
                            <span><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="@error('password') is-invalid @enderror"
                                placeholder="Password" required="">
                        </div>
                        @error('password')
                            <span role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        {{--  <div class="link">
                     @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">
                           {{ __('Forgot Password?') }}
                        </a>
                     @endif
                     &nbsp <a href="{{ route('register') }}">Sign up</a>
                  </div> --}}

                    </div>
                    <button type="submit" class="submit">Login</button>
                    {{-- <div class="link1">
                        <a href="{{ route('register') }}">Sign up</a>
                    </div> --}}
                </form>

            </div>
        </div>
    </section>
@endsection
