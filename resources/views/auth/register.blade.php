<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <h2>Register</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name">Name</label><br>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">
            @error('name')
            <span>{{ $message }}</span>
        @enderror
        </div>

        <div>
            <label for="email">Email Address</label><br>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')
            <span>{{ $message }}</span>
        @enderror
        </div>

        <div>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required autocomplete="new-password">
            @error('password')
            <span>{{ $message }}</span>
        @enderror
        </div>

        <div>
            <label for="password_confirmation">Confirm Password</label><br>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
        </div>

        <div>
            <button type="submit">Register</button>
        </div>
    </form>

</body>
</html>