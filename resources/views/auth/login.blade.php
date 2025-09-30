<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Data Extraction System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📊</text></svg>">

    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
  </head>
  <body>
    <div class="login-container">
      <div class="login-box">
        <div class="login-header">

          <h1>Data Extraction System</h1>
          <p>Secure Access Portal</p>
        </div>

        @if ($errors->any())
          <div class="error-message">
            @foreach ($errors->all() as $error)
              <p>{{ $error }}</p>
            @endforeach
          </div>
        @endif

        @if (session('error'))
          <div class="error-message">
            {{ session('error') }}
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="login-form">
          @csrf
          <div class="input-group">
            <div class="input-field">
              <input type="text" name="email" value="{{ old('email') }}" required>
              <label>Email Address</label>
            </div>
            <div class="input-field">
              <input class="pswrd" type="password" name="password" required>
              <span class="show">SHOW</span>
              <label>Password</label>
            </div>
          </div>

          <div class="button">
            <div class="inner"></div>
            <button type="submit">
              <i class="fas fa-sign-in-alt"></i>
              ACCESS SYSTEM
            </button>
          </div>
        </form>

        <div class="login-footer">
          <p>© 2025 Mass-Specc Cooperative. All rights reserved.</p>
        </div>
      </div>
    </div>

    <script>
      var input = document.querySelector('.pswrd');
      var show = document.querySelector('.show');
      show.addEventListener('click', active);
      function active(){
        if(input.type === "password"){
          input.type = "text";
          show.style.color = "#1DA1F2";
          show.textContent = "HIDE";
        }else{
          input.type = "password";
          show.textContent = "SHOW";
          show.style.color = "#111";
        }
      }
    </script>
  </body>
</html>
