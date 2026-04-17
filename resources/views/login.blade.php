<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
  <header>
    <div class="header-content">
      <h1>EMERGENCY SERVICES</h1>
      <p class="emergency-number"style="font-size:35px"><b>112</b></p>
      <p class="header-second"></p>
    </div>
  </header>

  <main>
    @auth
      <h1>------------------------------- DEBUG PAGE -------------------------------</h1>
      <p><b>DEBUG!</b> you are logged in as <strong>{{ Auth::user()->name }} and the id of {{ Auth::user()->id_number }}</strong></p>

      <form action="/logout" method="POST">
          @csrf
          <button>Log out</button>
      </form>
    @else
      <div class="login-background">
        <div class="overlay">
          <form class="login-form" action="/login" method="POST">
              @csrf
              <h2>LOG IN</h2>
              <label for="id-number" style="color: rgb(240, 248, 255, 0.5);">ID NUMBER</label>
              <input type="text" id="id-number" name="id_number" required placeholder="ID number" />

              <label for="password" style="color: rgb(240, 248, 255, 0.5);">PASSWORD</label>
              <input type="password" id="password" name="password" required placeholder="password"/>

              <button type="submit">LOG IN</button>
          </form>
        </div>
      </div>
    @endauth

  </main>
</body>
</html>
