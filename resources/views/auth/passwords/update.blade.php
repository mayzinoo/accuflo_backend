<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/updatepassword.css') }}">
</head>

<body>
   <section class="content">
      <div class="card">
         <div class="main">
            <div class="logo"></div>
            <div class="title">Change Password</div>
            <form action="{{ route('admin.password.update') }}" method="post" id="update-form">
                @csrf
                <div class="credentials">
                    <div class="password">
                        <span><i class="fas fa-lock"></i></span>
                        <input type="password" name="current_password" class="@error('password') is-invalid @enderror"
                            placeholder="Current Password" required="" data-error="#err_current_pwd">
    
                    </div>
                    @error('current_password')
                        <span role="alert" class="error">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
    
    
                    <div id="err_current_pwd"></div>
                    <div class="password">
                        <span><i class="fas fa-lock"></i></span>
                        <input type="password" name="new_password" id="new_password"
                            class="@error('password') is-invalid @enderror" placeholder="New Password" required=""
                            data-error="#err_new_pwd">
    
                    </div>
                    @error('new_password')
                        <span role="alert" class="error">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div id="err_new_pwd"></div>
                    <div class="password">
                        <span><i class="fas fa-lock"></i></span>
                        <input type="password" name="new_password_confirmation"
                            class="@error('password') is-invalid @enderror" placeholder="Confirm Password" required=""
                            data-error="#err_confirm_pwd">
    
                    </div>
                    @error('confirm_password')
                        <span role="alert" class="error">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div id="err_confirm_pwd" style="margin-bottom:20px;"></div>
                </div>
                <a href="/" class="back">Back</a>
                <button type="submit" class="save">Save</button>
            </form>
    
        </div>
      </div>
   </section>
   
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script>
        $('#update-form').validate({
            rules: {
                current_password: {
                    minlength: 8
                },
                new_password: {
                    minlength: 8
                },
                confirm_password: {
                    minlength: 8,
                    equalTo: "new_password"
                }
            },
            errorPlacement: function(error, element) {
                var placement_id = $(element).data('error');
                if (placement_id) {
                    $(placement_id).append(error);
                }
            }
        })
    </script>
</body>

</html>
