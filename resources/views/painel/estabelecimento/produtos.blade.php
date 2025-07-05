@extends('layouts.basico')

@section('titulo', 'Produtos')

@section('conteudo')
<input type="hidden" id="produtoRoutesBase" value="{{ route('produto.index') }}">
<input type="hidden" id="produtoRoutesShow" value="{{ route('produto.show', ':id') }}">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center py-5">Produtos Disponíveis - {{$estabelecimento->nome}}</h2>
        <form method="GET" action="{{ route('estabelecimento.produtos', $estabelecimento->id) }}" class="row g-3 mb-4">
            <div class="col-md-6">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    class="form-control" 
                    placeholder="Pesquisar produto..."
                >
            </div>

            <div class="col-md-4">
                <select name="categoria" class="form-select">
                    <option value="">Todas as Categorias</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>
                            {{ ucfirst($categoria) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>
        
        @if(session('tipo') == 'Produtor')
            <button class="btn btn-success" id="adicionar-produto"><i class="fa fa-plus me-1"></i> Adicionar Produto</button>
        @endif
    </div>
    @if($produtos->isNotEmpty())
        <div id="carouselProdutos" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($produtos->chunk(8) as $chunkIndex => $grupo8)
                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4 mb-2">
                            @foreach ($grupo8->take(4) as $produto)
                                <div class="col">
                                    @include('painel.produto.componentes.card-produto', ['produto' => $produto])
                                </div>
                            @endforeach
                            @for ($i = $grupo8->take(4)->count(); $i < 4; $i++)
                                <div class="col invisible"><div class="h-100"></div></div>
                            @endfor
                        </div>

                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                            @foreach ($grupo8->skip(4) as $produto)
                                <div class="col">
                                    @include('painel.produto.componentes.card-produto', ['produto' => $produto])
                                </div>
                            @endforeach
                            @for ($i = $grupo8->skip(4)->count(); $i < 4; $i++)
                                <div class="col invisible"><div class="h-100"></div></div>
                            @endfor
                        </div>
                    </div>
                @endforeach

            </div>

            @if ($produtos->count() > 8)
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselProdutos" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselProdutos" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            @endif
        </div>
    @else
        <div class="d-flex justify-content-center align-items-center" style="min-height: 300px;">
            <h4 class="text-muted text-center">NÃO POSSUI PRODUTOS</h4>
        </div>
    @endif
</div>
    @if($produtos->isNotEmpty())
        <div class="modal fade" id="modalProduto" tabindex="-1" aria-labelledby="modalProdutoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProdutoLabel">Editar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body" id="produtoModalBody">
                        @include('painel.produto.form', ['method' => 'PUT'])
                </div>
                </div>
            </div>
        </div>
    @endif
    
    @if(session('tipo') == 'Produtor')
        <div class="modal fade" id="modalAddProduto" tabindex="-1" aria-labelledby="modalAddProdutoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddProdutoLabel">Adicionar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body" id="adicionarProdutoModalBody">
                        @include('painel.estabelecimento.form-produto', ['produtos' => $prodSemEstabelecimento])
                </div>
                </div>
            </div>
        </div>
    @endif
@endsection