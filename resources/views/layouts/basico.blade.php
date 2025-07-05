<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Projeto E.V.A. - @yield('titulo')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/estilo_basico.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="d-flex flex-column min-vh-100">
    @include('layouts.partials.topo')

    @if(session('tipo') === 'Consumidor')
        <div id="cart-sidebar" class="position-fixed top-0 end-0 bg-white shadow p-3" style="width: 300px; transform: translateX(100%); transition: 0.3s; z-index: 1050;">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Carrinho</h5>
                <button class="btn-close" onclick="toggleCartSidebar()"></button>
            </div>

            <div id="cart-items"></div>
            <p id="cart-empty-message" class="text-center text-muted">Sem itens</p>

            <div id="finalizar-wrapper">
                <button class="btn btn-success w-100 mt-3">Finalizar Pedido</button>
            </div>
        </div>

        <div id="cart-flutuante" class="position-fixed bottom-0 end-0 m-3">
            <button class="btn btn-dark position-relative shadow" onclick="toggleCartSidebar()">
                <i class="fa fa-shopping-cart"></i>
                <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
            </button>
        </div>
    @endif

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

    <main class="flex-grow-1 container py-4" style="margin-top: 50px">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @elseif(session('success'))
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif
        @yield('conteudo')
    </main>

    <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    rel="stylesheet"
    />

    <footer class="bg-light text-center text-muted py-2 border-top mt-auto" style="font-size: 0.9rem;">
        <div class="container">
            <div class="d-flex justify-content-center gap-3 flex-wrap align-items-center">
            <div>
                <h6 class="fw-semibold mb-1">Contato</h6>
                <p class="mb-1">
                <i class="fas fa-phone-alt me-2 text-primary"></i>
                <a href="tel:+5533988869730" class="text-decoration-none text-muted">(33) 98886-9730</a>
                </p>
                <p class="mb-0">
                <i class="fas fa-envelope me-2 text-primary"></i>
                <a href="mailto:robertreis323@gmail.com" class="text-decoration-none text-muted">robertreis323@gmail.com</a>
                </p>
            </div>
            </div>
            <small class="d-block mt-2">&copy; 2025 Projeto E.V.A. Todos os direitos reservados.</small>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/jquery.deserialize.min.js') }}"></script>

    <script src="{{ asset('js/painel/produto.js') }}"></script>
    <script src="{{ asset('js/painel/estabelecimento.js') }}"></script>
    <script src="{{ asset('js/painel/carrinho.js') }}"></script>

    @stack('scripts')
</body>
</html>
