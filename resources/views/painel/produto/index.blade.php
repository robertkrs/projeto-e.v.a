@extends('layouts.basico')

@section('titulo', 'Meus Produtos')

@section('conteudo')
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
        <h5 class="modal-title" id="modalProdutoLabel">Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body" id="produtoModalBody">
            @include('painel.produto.form')
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            var $form = $('#produto-form');
            
            $('#produtos-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: { 
                    url:'{{ route("produto.datatable") }}', 
                    data: function (d) {
                        d.search    = $('input[type="search"]').val();
                        d.categoria = $('#filtro-categoria').val();
                    }
                },
                columns: [
                    { data: 'foto', name: 'foto', orderable: false, searchable: false },
                    { data: 'nome', name: 'nome' },
                    { data: 'categoria', name: 'categoria' },
                    { data: 'preco', name: 'preco' },
                    { data: 'estoque', name: 'estoque' },
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
                $('#produtos-table').DataTable().ajax.reload(null, false);
            });

            $(document).on('click', '.editar-produto', function(e) {
                var data = getDataTableRowData($('#produtos-table').DataTable(), this)
                buscarProduto(data['id']);

                e.preventDefault();
                e.stopPropagation();
            });

            $(document).on('click', '#novo-produto', function(e){
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
                url: '{{ route("produto.index") }}/' + data['id'],
                type: 'POST',
                data: {_method: 'DELETE'},
                success: function (data) {
                    if(data.error) {
                        return false;
                    }else{
                        showAlert(data['message'] || "Remoção feita com sucesso!", 'success');
                    }
                }
            })

                e.preventDefault();
                e.stopPropagation();
            });

            function buscarProduto(id) {
                $.ajax({
                    url: `{{ route("produto.index") }}/` + id + '/edit',
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
                var $form = $('#produto-form');
                var id = data['id'] || '';

                var actionForm = '{{ route("produto.index") }}' + (id ? "/" + id : '');
                var actionTitle = (id ? 'Editar' : 'Cadastrar');
                data['_method'] = (id ? "PUT" : 'POST');

                data['id']        = data['id'] || '';
                data['nome']      = data['nome'] || '';
                data['descricao'] = data['descricao'] || '';
                data['categoria'] = data['categoria'] || '';
                data['preco']     = data['preco'] || '';
                data['estoque']   = data['estoque'] || '';

                $form.attr('action', actionForm);
                $form[0].reset();
                $form.deserialize(data);

                $('#modalProduto').modal('show');
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
                    url: '{{ route("produto.index") }}' + (data.get('id') ? '/' + data.get('id') : ''),
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    data: data,
                    success: function (response) {
                        if (response.error) {
                            // showAlert(response.message || "Não foi possível salvar o registro!", 'danger');
                        } else {
                            $('#produtos-table').DataTable().ajax.reload(null, false);
                            // showAlert(response.message || "Registro salvo com sucesso!", 'success');
                            $('#modalProduto').modal('hide');
                        }
                    },
                    error: function () {
                        // showAlert("Erro ao enviar dados para o servidor!", 'danger');
                    },
                    complete: function () {
                        $form.data('ajax', false); // Libera o form
                    }
                });
            });

            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("produto.index") }}/' + id,
                type: 'POST',
                data: {_method: 'DELETE'},
                success: function (data) {
                    if(data.error) {
                        return false;
                    }else{
                        window.location.reload(true);
                    }
                }
            })
        });
    </script>
@endpush
