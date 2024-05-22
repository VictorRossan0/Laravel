<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Gestão de Atestados</title>
    <link rel="icon" href="{{ asset('storage/images/global_hitss_logo.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- Aqui está o style que precisa adicionar -->
    <style>
        .sidebar {
            width: 25%;
            height: auto;
            background-color: lightgray;
            display: flex;
        }

        .home {
            color: gray;
            cursor: pointer;
            transition: transform 0.5s ease;
            transform-origin: left;
            font-size: 17px;

        }

        .home:hover {
            color: black;
            transform: scaleX(1.02);
        }
    </style>
    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script async src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
</head>

<body>

    @include('layouts.header')

    <div class="container border-top mt-5">
        <footer class="footer">
            <div class="footer-copyright text-center py-1">
                © Propriedade Global Hitss 2023 Desenvolvido por  <img width="20px" src="{{ asset('storage/images/global_hitss_logo.png') }}" /> Buffer TI
            </div>
            <div class="footer-copyright text-center py-1">
                <p>Ferramenta compatível somente com os navegadores,
                    Chrome <img width="20px" src="{{ asset('storage/images/chrome.svg') }}" />
                    - Firefox <img width="20px" src="{{ asset('storage/images/firefox.svg') }}" />
                    - Edge <img width="20px" src="{{ asset('storage/images/edge.svg') }}" />
                </p>
            </div>
        </footer>
    </div>

    <!-- Include Bootstrap scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
