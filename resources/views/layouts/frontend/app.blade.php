<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Perpustakaan Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        /* RESET TOTAL */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Poppins', sans-serif;

            /* FULL BACKGROUND GAMBAR */
            background: url('{{ asset('images/bg.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        .content {
            padding: 80px 20px 60px 20px;
            min-height: 100vh;

            /* PASTIIN TRANSPARAN */
            background: transparent !important;
        }
    </style>
</head>
<body>

    @include('layouts.frontend.navbar')

    <div class="content">
        @yield('content')
    </div>

    @include('layouts.frontend.footer')

</body>
</html>