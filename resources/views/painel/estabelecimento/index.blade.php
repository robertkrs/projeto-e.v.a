@extends('layouts.basico')

@section('titulo', 'Meus Estabelecimentos')

@section('conteudo')
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
                    <th>Qnt. Produtos</th>
                    <th>Ações</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal Estabelecimento -->
<div class="modal fade" id="modalEstabelecimento" tabindex="-1" aria-labelledby="modalEstabelecimentoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEstabelecimentoLabel">Estabelecimento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body" id="estabelecimentoModalBody">
        <!-- Formulário será carregado aqui via AJAX -->
            @include('painel.estabelecimento.form')
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            var $form = $('#estabelecimento-form');
            
            $('#estabelecimentos-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: { 
                    url:'{{ route("estabelecimento.datatable") }}', 
                    data: function (d) {
                        d.search  = $('input[type="search"]').val();
                        d.produto = $('#filtro-produto').val();
                    },
                    dataSrc: function (json) {
                        var data = json.data;
                        console.log(data);
                        for (var row in data) {

                        }

                        return data;
                    }
                },
                columns: [
                    { data: 'foto_fachada', name: 'foto_fachada', orderable: false, searchable: false },
                    { data: 'nome', name: 'nome' },
                    { data: 'enderecoCompleto', name: 'enderecoCompleto' },
                    { data: 'qtd_produtos', name: 'qtd_produtos'},
                    { data: 'acoes', name: 'acoes', orderable: false, searchable: false }
                ],
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>' +
                    '<"table-responsive"t>' +
                    '<"row mt-3"<"col-md-6"i><"col-md-6"p>>',
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                }
            });

            $('#btn-filtrar').on('click', function () {
                $('#estabelecimentos-table').DataTable().ajax.reload(null, false);
            });

            // Evento para botão de edição
            $(document).on('click', '.editar-estabelecimento', function(e) {
                var data = getDataTableRowData($('#estabelecimentos-table').DataTable(), this)
                buscarEstabelecimento(data['id']);

                e.preventDefault();
                e.stopPropagation();
            });

            $(document).on('click', '#novo-estabelecimento', function(e){
                e.preventDefault();
                e.stopPropagation();

                showModal();
            });

            $(document).on('click', '.remover-estabelecimento', function(e){
                var data = getDataTableRowData($('#estabelecimentos-table').DataTable(), this)

                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route("estabelecimento.index") }}/' + data['id'],
                    type: 'POST',
                    data: {_method: 'DELETE'},
                    success: function (data) {
                        if(data.error) {
                            return false;
                        }else{
                            showAlert(data['message'] || "Remoção feita com sucesso!", 'success');
                        }
                    }
                });

                e.preventDefault();
                e.stopPropagation();
            });

            function buscarEstabelecimento(id) {
                $.ajax({
                    url: `{{ route("estabelecimento.index") }}/` + id + '/edit',
                    type: 'GET',
                    success: function (data) {
                        if (data.error) {
                            showAlert(data['message'] || "Não foi possível salvar registro!", 'danger');
                        }else{
                            showModal(data);
                        }
                    },
                });
            }

            function showModal(data) {
                data = data || {};
                var $form = $('#estabelecimento-form');
                var id = data['id'] || '';

                var actionForm = '{{ route("estabelecimento.index") }}' + (id ? "/" + id : '');
                var actionTitle = (id ? 'Editar' : 'Cadastrar');
                data['_method'] = (id ? "PUT" : 'POST');

                data['id']          = data['id'] || '';
                data['nome']        = data['nome'] || '';
                data['descricao']   = data['descricao'] || '';
                data['endereco']    = data['endereco'] || '';
                data['cep']         = data['cep'] || '';
                data['numero']      = data['numero'] || '';
                data['complemento'] = data['complemento'] || '';
                data['bairro']      = data['bairro'] || '';
                data['cidade']      = data['cidade'] || '';
                data['estado']      = data['estado'] || '';

                $form.attr('action', actionForm);
                $form[0].reset();
                $form.deserialize(data);

                $('#modalEstabelecimento').modal('show');
            }

            function getDataTableRowData(datatable, child) {
                var $parent = $(child).closest('tr');
                var data = datatable.row($parent).data();

                if (!data) {
                    data = datatable.row(child).data();
                }

                if (!data) {
                    data = datatable.fnGetData($parent);
                }

                if (!data) {
                    data = datatable.fnGetData(child);
                }

                return data;
            };

            $form.on('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Previne envio duplicado
                if ($form.data('ajax')) return;

                var data = new FormData(this);
                var method = data.get('id') ? 'PUT' : 'POST';
                console.log(method);
                data.append('ajax', true);
                data.append('_method', method)

                $form.data('ajax', true); // Marca como processando

                $.ajax({
                    url: '{{ route("estabelecimento.index") }}' + (data.get('id') ? '/' + data.get('id') : ''),
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    data: data,
                    success: function (response) {
                        if (response.error) {
                            // showAlert(response.message || "Não foi possível salvar o registro!", 'danger');
                        } else {
                            $('#estabelecimentos-table').DataTable().ajax.reload(null, false);
                            // showAlert(response.message || "Registro salvo com sucesso!", 'success');
                            $('#modalEstabelecimento').modal('hide');
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText)
                        // showAlert("Erro ao enviar dados para o servidor!", 'danger');
                    },
                    complete: function () {
                        $form.data('ajax', false); // Libera o form
                    }
                });
            });

        });
    </script>
@endpush
