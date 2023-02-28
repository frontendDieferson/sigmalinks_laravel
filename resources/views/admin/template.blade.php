<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel='stylesheet' href="{{ url('assets/css/admin.template.css') }}" />
    <title>@yield('title')</title>
</head>

<body>

    <nav>
        <div class="nav--top">
            <a href="{{ url('/admin') }}">
                <img src="{{ url('assets/images/home.svg') }}" width="28" />
            </a>

        </div>



        <div class="nav--bottom">
            <a href="{{ url('/admin/logout') }}">
                <img src="{{ url('assets/images/logout.svg') }}" width="28" />
            </a>

        </div>
    </nav>


    <section class="container">
        @yield('content')
    </section>
</body>

</html>
