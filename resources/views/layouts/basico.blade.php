<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Super Gestão - @yield('titulo')</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- CSS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" crossorigin="anonymous" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/estilo_basico.css') }}">
</head>

<body class="d-flex flex-column min-vh-100">
    @include('layouts.partials.topo')

    {{-- Alertas de sessão --}}
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {!! Session::get('error') !!}
        </div>
    @endif

    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {!! Session::get('success') !!}
        </div>
    @endif

    {{-- Conteúdo principal --}}
    <main class="flex-grow-1 container py-4" style="margin-top:30px">
        @yield('conteudo')
    </main>

    {{-- Rodapé fixo no final --}}
    <footer class="bg-light text-center text-muted py-3 border-top mt-auto">
        <div class="container">
            <div class="d-flex justify-content-center gap-4 flex-wrap">
                <div>
                    <h5>Contato</h5>
                    <p class="mb-1">(33) 98886-9730</p>
                    <p class="mb-0">robertreis323@gmail.com</p>
                </div>
            </div>
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/jquery.deserialize.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
