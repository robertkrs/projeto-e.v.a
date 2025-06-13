@extends('layouts.basico')

@section('titulo', 'Dashboard')

@section('conteudo')
<div class="container py-5"> <!-- aumentei o padding vertical para mais espaço -->
    <div class="card shadow-sm mb-5">
        <div class="card-body text-center py-4"> <!-- aumentei o padding interno -->
            <h5 class="mb-3">Bem-vindo(a), <strong>{{ session('nome', 'Visitante') }}</strong>!</h5>

            @if(session('tipo') === 'Produtor')
                <p class="mb-3 small">Você está logado como <span class="badge bg-success">Produtor</span>.</p>
                <a href="{{ route('produto.index') }}" class="btn btn-primary btn-sm">Painel do Produtor</a>
            @elseif(session('tipo') === 'Cooperativa')
                <p class="mb-3 small">Você está logado como <span class="badge bg-info text-dark">Cooperativa</span>.</p>
                <a href="{{ route('estabelecimento.index') }}" class="btn btn-info btn-sm">Painel da Cooperativa</a>
            @elseif(session('tipo') === 'Consumidor')
                <p class="mb-3 small">Você está logado como <span class="badge bg-warning text-dark">Consumidor</span>.</p>
                {{-- <a href="{{ route('consumidor.index') }}" class="btn btn-warning btn-sm text-dark">Painel do Consumidor</a> --}}
            @else
                <p class="mb-3 small">Tipo de usuário desconhecido.</p>
                <a href="{{ route('site.login') }}" class="btn btn-secondary btn-sm">Fazer Login</a>
            @endif
        </div>
    </div>
</div>

<div class="container pb-4">
    <h5 class="text-center mb-4">Estabelecimentos</h5>

    @php
        $agrupados = $estabelecimentos->groupBy('estabelecimento.id');
    @endphp

    @foreach($agrupados as $cooperativa => $grupo)
        <h6 class="mb-3 text-primary text-center">{{ $cooperativa ?? 'Cooperativa Desconhecida' }}</h6>
        <div id="carousel-{{ \Illuminate\Support\Str::slug($cooperativa ?? 'desconhecida') }}" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($grupo->chunk(3) as $chunkIndex => $chunk)
                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                        <div class="row justify-content-center">
                            @foreach($chunk as $estabelecimento)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        @if($estabelecimento->foto_fachada)
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $estabelecimento->foto_fachada) }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $estabelecimento->nome }}">
                                                
                                                {{-- Tag Produtos Cadastrados --}}
                                                <div class="position-absolute bottom-0 end-0 m-2 bg-success text-white px-2 py-1 rounded-pill d-flex align-items-center" style="font-size: 0.8rem;">
                                                    <i class="fa fa-box me-1"></i> {{ $estabelecimento->produtos->count() }}
                                                </div>
                                            </div>
                                        @endif
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-1">{{ $estabelecimento->nome }}</h6>
                                            <p class="card-text small">{{ $estabelecimento->descricao }}</p>
                                            <p class="card-text"><small class="text-muted">{{ $estabelecimento->cidade }} - {{ $estabelecimento->estado }}</small></p>
                                            <div class="d-flex justify-content-center mt-2">
                                                <a href="{{ route('estabelecimento.produtos', [$estabelecimento->id]) }}" class="btn btn-sm btn-outline-primary">Exibir Produtos</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            @if($grupo->count() > 3)
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ \Illuminate\Support\Str::slug($cooperativa ?? 'desconhecida') }}" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ \Illuminate\Support\Str::slug($cooperativa ?? 'desconhecida') }}" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            @endif
        </div>
    @endforeach
</div>

@endsection
