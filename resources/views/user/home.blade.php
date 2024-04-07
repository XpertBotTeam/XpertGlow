<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    @if(session()->has('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @auth
    <p>Welcome, {{ auth()->user()->name }}</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Logout</button>
    </form>

    @else
        <a href="{{ route('register') }}" ><button>Register</button></a>
        <a href="{{ route('login') }}" ><button>Login</button></a>
    @endauth

</body>
</html>