$(document).ready(function () {
    const PRODUTO_ROUTES = {
        base: $('#produtoRoutesBase').val(),
        datatable: $('#produtoRoutesDatatable').val(),
        show: $('#produtoRoutesShow').val(),
    };

    const produtosTable = $('#produtos-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: PRODUTO_ROUTES.datatable,
            data: function (d) {
                d.search = $('input[type="search"]').val();
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
        produtosTable.ajax.reload(null, false);
    });

    $(document).on('click', '#novo-produto', function (e) {
        e.preventDefault();
        showModal();
    });

    $(document).on('click', '.editar-produto', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var $btn = $(this);
        var id;

        var $table = $btn.closest('table');
        if ($table.length) {
            var datatable = $table.DataTable();
            if (datatable) {
                var $row = $btn.closest('tr');
                var rowData = datatable.row($row).data();
                if (rowData && rowData.id) {
                    id = rowData.id;
                }
            }
        }

        if (!id) {
            id = $btn.data('id');
        }

        if (!id) {
            showAlert("ID do produto não encontrado.", "danger");
            return;
        }
        console.log(id);
        buscarProduto(id);
    });

    $(document).on('click', '.exibir-produto', function (e) {
        e.preventDefault();
        const data = getDataTableRowData(produtosTable, this);
        exibirProduto(data.id);
    });

    $(document).on('click', '.remover-produto', function (e) {
        e.preventDefault();

        if (!confirm('Tem certeza que deseja excluir este produto?')) {
            return false;
        }

        const data = getDataTableRowData(produtosTable, this);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: `${PRODUTO_ROUTES.base}/${data.id}`,
            type: 'POST',
            data: { _method: 'DELETE' },
            success: function (res) {
                if (res.error) {
                    showAlert(res.message || 'Erro ao remover o produto!', 'danger');
                } else {
                    produtosTable.ajax.reload(null, false);
                    showAlert(res.message || 'Remoção feita com sucesso!', 'success');
                }
            },
            error: function () {
                showAlert('Erro ao tentar excluir o produto.', 'danger');
            }
        });
    });

    function buscarProduto(id) {
        $.ajax({
            url: `${PRODUTO_ROUTES.base}/${id}/edit`,
            type: 'GET',
            success: function (res) {
                if (res.error) {
                    showAlert(res.message || 'Não foi possível carregar o produto.', 'danger');
                } else {
                    console.log(res);
                    showModal(res);
                }
            }
        });
    }

    function exibirProduto(id) {
        $.ajax({
            url: PRODUTO_ROUTES.show.replace(':id', id),
            type: 'GET',
            success: function (res) {
                if (res.error) {
                    showAlert(res.message || 'Falha ao buscar dados!', 'danger');
                } else {
                    $('#modalProdutoShow .modal-body').html(res);
                    $('#modalProdutoShow').modal('show');
                }
            },
            error: function () {
                showAlert('Erro ao buscar os dados do produto.', 'danger');
            }
        });
    }

    function showModal(data = {}) {
        const $form = $('#produto-form').length > 0 ? $('#produto-form') : $('#produto-form-card');
        const id = data.id || '';

        const action = `${PRODUTO_ROUTES.base}${id ? '/' + id : ''}`;
        $form.attr('action', action);
        $form[0].reset();

        $form.find('[name="id"]').val(data.id || '');
        $form.find('[name="nome"]').val(data.nome || '');
        $form.find('[name="descricao"]').val(data.descricao || '');
        $form.find('[name="categoria"]').val(data.categoria || '');
        $form.find('[name="preco"]').val(data.preco || '');
        $form.find('[name="estoque"]').val(data.estoque || '');

        $('#modalProduto').modal('show');
    }

    function getDataTableRowData(datatable, element) {
        const $row = $(element).closest('tr');
        let data = datatable.row($row).data();

        if (!data) data = datatable.row(element).data();

        return data;
    }

    function showAlert(message, type = 'success') {
        const alertId = 'custom-alert';
        const alertHtml = `
            <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show fixed-top m-3" role="alert" style="z-index: 1050;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        `;

        if ($(`#${alertId}`).length) $(`#${alertId}`).remove();
        $('body').append(alertHtml);

        setTimeout(() => {
            $(`#${alertId}`).alert('close');
        }, 4000);
    }

    $('#produto-form').on('submit', function (e) {
        e.preventDefault();
        if ($(this).data('ajax')) return;

        const formData = new FormData(this);
        const id = formData.get('id');
        const method = id ? 'PUT' : 'POST';

        formData.append('_method', method);
        formData.append('ajax', true);

        $(this).data('ajax', true);

        $.ajax({
            url: `${PRODUTO_ROUTES.base}${id ? '/' + id : ''}`,
            type: 'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function (res) {
                if (res.error) {
                    showAlert(res.message || 'Não foi possível salvar o registro!', 'danger');
                } else {
                    produtosTable.ajax.reload(null, false);
                    showAlert(res.message || 'Registro salvo com sucesso!', 'success');
                    $('#modalProduto').modal('hide');
                }
            },
            error: function () {
                showAlert('Erro ao enviar dados para o servidor!', 'danger');
            },
            complete: function () {
                $('#produto-form').data('ajax', false);
            }
        });
        
    });
});
