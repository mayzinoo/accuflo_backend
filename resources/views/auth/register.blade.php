@extends('layouts.app-without-nav')

@section('content')
    <section class="content">
        <div class="card">
            <div class="main">
                <div class="logo"></div>
                <div class="title">Register</div>
                <form action="{{ route('register') }}" method="post">
                    @csrf
                    <div class="credentials">
                        <div class="username">
                            <span><i class="fas fa-user"></i></span>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Name"
                                required="" autocomplete="off">

                        </div>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="email">
                            <span><i class="fas fa-envelope"></i></span>
                            <input type="text" name="email" value="{{ old('email') }}" placeholder="Email Address"
                                autocomplete="off" required="">
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                        <div class="password">
                            <span><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" placeholder="Password" required="">
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="password">
                            <span><i class="fas fa-lock"></i></span>
                            <input type="password" name="password_confirmation" placeholder="Confirm Password"
                                required="">
                        </div>
                    </div>
                    <input name="role" type="hidden" value="Admin">
                    <input name="phone" type="hidden" value="12345678">
                    <button type="submit" class="submit" style="margin-top:5px;">Register</button>
                </form>
                <div class="link1">
                    <a href="#">Already a member</a> &nbsp <a href="{{ route('login') }}">Login</a>
                </div>
            </div>
        </div>
    </section>
@endsection
