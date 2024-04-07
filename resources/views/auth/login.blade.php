<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/auth/login.css') }}">
    <title>Document</title>
</head>
<body>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form_wrapper">
        <div class="form_container">
            <h1>XpertGlow</h1>
            <h2>Login</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form_item">
                    <div class="form_item_i">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <input id="email" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                </div>
                <div class="form_item">
                    <div class="form_item_i">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input id="password" type="password" name="password" placeholder="Password">
                    <button class="show_password" type="button" onclick="ShowPassword()">
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
                
                <input type="submit" value="Login">
                
                <a href="./register.html">Already have an account? Login here</a>
            </form>
        </div>
    </div>


    <script src="{{ asset('assets/js/auth/login.js') }}"></script>
</body>
</html>