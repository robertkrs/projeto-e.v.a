@extends('layouts.basico')

@section('titulo', 'Painel de Estabelecimentos')

@section('conteudo')
<div class="container py-3 mt-4">
    <h2 class="text-center mb-5">Painel de Estabelecimentos</h2>

    @php
        $agrupados = $estabelecimentos->groupBy(function($est) {
            return $est->nome ?? 'Cooperativa Desconhecida';
        });
    @endphp

    @foreach($agrupados as $cooperativa => $grupo)
        <h4 class="mt-4 mb-3 text-primary text-center">{{ $cooperativa }}</h4>

        <div id="carousel-{{ \Illuminate\Support\Str::slug($cooperativa) }}" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($grupo->chunk(3) as $chunkIndex => $chunk)
                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                        <div class="row justify-content-center">
                            @foreach($chunk as $estabelecimento)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        @if($estabelecimento->foto)
                                            <img src="{{ asset('storage/' . $estabelecimento->foto) }}" class="card-img-top" alt="{{ $estabelecimento->nome }}">
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $estabelecimento->nome }}</h5>
                                            <p class="card-text">{{ $estabelecimento->descricao }}</p>
                                            <p class="card-text"><small class="text-muted">{{ $estabelecimento->cidade }} - {{ $estabelecimento->estado }}</small></p>
                                            <a href="{{ route('estabelecimento.produtos', [$estabelecimento->id]) }}" class="btn btn-sm btn-outline-primary">Inserir Produtos</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            @if($grupo->count() > 3)
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ \Illuminate\Support\Str::slug($cooperativa) }}" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ \Illuminate\Support\Str::slug($cooperativa) }}" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>
    @endforeach
</div>
@endsection
