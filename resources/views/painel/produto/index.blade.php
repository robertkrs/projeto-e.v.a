@extends('layouts.basico')

@section('titulo', 'Meus Produtos')

@section('conteudo')
<input type="hidden" id="produtoRoutesBase" value="{{ route('produto.index') }}">
<input type="hidden" id="produtoRoutesDatatable" value="{{ route('produto.datatable') }}">
<input type="hidden" id="produtoRoutesShow" value="{{ route('produto.show', ':id') }}">

<div class="container py-5">
    <div class="row justify-content-center mb-4">
        <div class="col-md-3">
            <select id="filtro-categoria" class="form-select form-select-sm">
                <option value="">Todas as categorias</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria }}">{{ $categoria }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button id="btn-filtrar" class="btn btn-sm btn-primary w-100">
                <i class="fa fa-filter me-1"></i> Filtrar
            </button>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Meus Produtos</h2>
        <button class="btn btn-success" id="novo-produto"><i class="fa fa-plus me-1"></i> Novo Produto</button>
    </div>

    <div class="table-responsive">
        <table id="produtos-table" class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="modalProduto" tabindex="-1" aria-labelledby="modalProdutoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalProdutoLabel">Cadastrar Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body" id="produtoModalBody">
            @include('painel.produto.form', ['ajax' => true])
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalProdutoShow" tabindex="-1" aria-labelledby="modalProdutoShowLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalProdutoShowLabel">Ver Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body" id="modalProdutoShowBody">
      </div>
    </div>
  </div>
</div>
@endsection

