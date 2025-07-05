<div class="topo shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('painel.home') }}">
                <img src="{{ asset('img/logo3.png') }}" alt="Logo" style="width: 40px;" class="me-2">
                <span class="fw-bold text-dark" style="margin-left:5px;">Projeto E.V.A.</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            @if(!empty(session('email')))
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="{{ route('painel.home') }}">
                                <i class="fa fa-home me-1"></i> Home
                            </a>
                        </li>
                        @if(session('tipo') === 'Produtor')
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="{{ route('produto.index') }}">
                                    <i class="fa fa-cubes me-1"></i> Meus Produtos
                                </a>
                            </li>
                        @endif
                        @if(session('tipo') === 'Cooperativa')
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="{{ route('estabelecimento.index') }}">
                                    <i class="fa fa-industry me-1"></i> Meus Estabelecimentos
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="{{ route('painel.logout') }}">
                                <i class="fa fa-sign-out me-1"></i> Sair
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </nav>
</div>
