@extends('layouts.basico')

@section('titulo', 'Meus Estabelecimentos')

@section('conteudo')
<input type="hidden" id="estabelecimentoRoutesBase" value="{{ route('estabelecimento.index') }}">
<input type="hidden" id="estabelecimentoRoutesDatatable" value="{{ route('estabelecimento.datatable') }}">
<input type="hidden" id="estabelecimentoRoutesShow" value="{{ route('estabelecimento.show', ':id') }}">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Meus Estabelecimentos</h2>
        <button class="btn btn-success" id="novo-estabelecimento"><i class="fa fa-plus me-1"></i> Novo Estabelecimento</button>
    </div>

    <div class="table-responsive">
        <table id="estabelecimentos-table" class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Foto Fachada</th>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th style="width:120px">Qnt. Produtos</th>
                    <th>Ações</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="modalEstabelecimento" tabindex="-1" aria-labelledby="modalEstabelecimentoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEstabelecimentoLabel">Estabelecimento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body" id="estabelecimentoModalBody">
            @include('painel.estabelecimento.form')
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEstabelecimentoShow" tabindex="-1" aria-labelledby="modalEstabelecimentoShowLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEstabelecimentoShowLabel">Ver Estabelecimento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body" id="modalEstabelecimentoShowBody">
      </div>
    </div>
  </div>
</div>
@endsection

