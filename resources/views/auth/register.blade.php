<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/auth/register.css') }}">
    <title>XpertGlow | REGISTER</title>
</head>
<body>

    <div class="form_wrapper">
        <div class="form_container">
            <h1>XpertGlow</h1>
            <h2>Register</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form_item">
                    <div class="form_item_i">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Name" required autocomplete="name">
                </div>

                <div class="form_item">
                    <div class="form_item_i">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email">
                </div>
                
                <div class="form_item">
                    <div class="form_item_i">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input id="password" type="password" name="password" placeholder="Password" required autocomplete="new-password">
                    <button class="show_password" type="button" onclick="ShowPassword('password', 'eyeIcon')">
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>

                <div class="form_item">
                    <div class="form_item_i">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input id="confirm_password" type="password" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                    <button class="show_password" type="button" onclick="ShowPassword('confirm_password', 'confirm_eyeIcon')">
                        <i id="confirm_eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>

                <input type="submit" value="Create Account">
                <a href="{{ route('login') }}">New Customer?Create your account</a>
            </form>
        </div>
    </div>

<script src="{{ asset('assets/js/auth/register.js') }}"></script>
</body>
</html>