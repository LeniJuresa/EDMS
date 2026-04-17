{{-- 
This is the default login/register page, this will be later used as an account creation tool for the administrator
--}}

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
</head>
<body>

    @auth
        <p>Congrats, you are logged in as <strong>{{ Auth::user()->name }}</strong> and the id of <strong>{{ Auth::user()->id_number }}</strong></p>
        <form action="/logout" method="POST">
            @csrf
            <button>Log out</button>
        </form>
    @else
        <div style="border: 3px solid black; padding: 10px; margin-bottom: 10px;">
            <h2>Register</h2>
            <form action="/register" method="POST">
                @csrf
                <input name="name" type="text" placeholder="name" required>
                <input name="email" type="email" placeholder="email" required>
                <input name="password" type="password" placeholder="password" required>
                <button type="submit">Register</button>
            </form>
        </div>

        <div style="border: 3px solid black; padding: 10px; margin-bottom: 10px;">
            <h2>Login</h2>
                <form action="/login" method="POST">
                    @csrf
                    <input type="text" name="id_number" required placeholder="ID number">
                    <input type="password" name="password" required placeholder="password">
                    <button type="submit">Login</button>
                </form>

        <div style="border: 3px solid black; padding: 10px; margin-top: 10px;">
            <h2>Anonymous Chat</h2>
            <form action="/chat" method="GET">
                <button type="submit">Start Anonymous Chat</button>
            </form>
        </div>
        
    @endauth

</body>
</html>
