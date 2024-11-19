<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mailing</title>
    @vite('resources/sass/app.scss')
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg bg-light navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('mailing.index') }}">ʕ ᵔᴥᵔ ʔ Chatapp Mailing</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="btn btn-success" href="{{ route('mailing.create') }}">Create</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <hr>
    @yield('content')
    @vite('resources/js/app.js')
</div>
</body>
</html>
