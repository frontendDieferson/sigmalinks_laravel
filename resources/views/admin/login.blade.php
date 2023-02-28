<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel='stylesheet' href="{{url('assets/css/admin.login.css')}}" />
  <title>SigmaLinks - Login</title>
</head>
<body>

    <div class="loginArea">
        <h1>Tudo o que você é.
            Em um simples link.</h1>
            <div class="formContainer">
            <img src="/media/uploads/sigmalink.png" />
            @if ($error)
                <div class="error">{{$error}}</div>
            @endif
            <form method="POST">
            @csrf
            <input type="email" name="email" placeholder="Digite seu e-mail..." />

            <input type="password" name="password" placeholder="Digite sua senha..." />
        <input class="btn" type="submit"  value="Entrar"/>
        <span>ou</span>
       <button>
        <a href="{{url('/admin/register')}}">Crie sua conta gratuitamente</a>
       </button>

        </form>
    </div>
    </div>
</body>
</html>
