@extends('layouts.app-without-nav')

@section('content')
    <div class="main card">
        <div class="logo"></div>
        <div class="title">{{ __('Reset Password') }}</div>
        <form id="reset-pwd-form" action="{{ route('password.update') }}" method="post">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="credentials">
                <div class="username">
                    <span><i class="fas fa-envelope"></i></span>
                    <input type="text" name="email" value="{{ $email ?? old('email') }}" placeholder="Email"
                        required="" autocomplete="off">
                </div>
                @error('email')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
                <div class="password">
                    <span><i class="fas fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="@error('password') is-invalid @enderror"
                        placeholder="Password" required="" data-id="err-pwd">
                </div>
                @error('password')
                    <span role="alert" class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <span id="err-pwd"> </span>

                <div class="password">
                    <span><i class="fas fa-lock"></i></span>
                    <input type="password" name="password_confirmation" class="@error('password') is-invalid @enderror"
                        placeholder="Confirm Password" required="" data-id="err-pwd-confirm">
                </div>
                <span id="err-pwd-confirm"></span>
            </div>
            <button type="submit" class="submit" style="margin-top:5px;">Reset Password</button>
        </form>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
        $("#reset-pwd-form").validate({
            rules: {
                email: {
                    required: true
                },
                password: {
                    required: true,
                    minlength: 8
                },
                password_confirmation: {
                    required: true,
                    minlength: 8,
                    equalTo: "#password"
                }
            },
            errorPlacement: function(error, element) {
                id = $(element).data("id");

                $(`#${id}`).append(error);
                console.log("error", error);
            }
        })
    </script>
@endpush
