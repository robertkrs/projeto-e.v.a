$(document).ready(function () {
    const ESTABELECIMENTO_ROUTES = {
        base: $('#estabelecimentoRoutesBase').val(),
        show: $('#estabelecimentoRoutesShow').val(),
        datatable: $('#estabelecimentoRoutesDatatable').val(),
    };

    const $form = $('#estabelecimento-form');

    const estabelecimentosTable = $('#estabelecimentos-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: ESTABELECIMENTO_ROUTES.datatable,
            data: function (d) {
                d.search = $('input[type="search"]').val();
                d.produto = $('#filtro-produto').val();
            },
            dataSrc: function (json) {
                console.log(json.data);
                return json.data;
            }
        },
        columns: [
            { data: 'foto_fachada', name: 'foto_fachada', orderable: false, searchable: false },
            { data: 'nome', name: 'nome' },
            { data: 'enderecoCompleto', name: 'enderecoCompleto' },
            { data: 'qtd_produtos', name: 'qtd_produtos' },
            { data: 'acoes', name: 'acoes', orderable: false, searchable: false }
        ],
        dom:
            '<"row mb-3"<"col-md-6"l><"col-md-6"f>>' +
            '<"table-responsive"t>' +
            '<"row mt-3"<"col-md-6"i><"col-md-6"p>>',
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        }
    });

    $('#btn-filtrar').on('click', function () {
        estabelecimentosTable.ajax.reload(null, false);
    });

    $(document).on('click', '#novo-estabelecimento', function (e) {
        e.preventDefault();
        e.stopPropagation();
        showModal();
    });

    $(document).on('click', '.exibir-estabelecimento', function (e) {
        e.preventDefault();
        const data = getDataTableRowData(estabelecimentosTable, this);
        exibirEstabelecimento(data.id);
    });

    $(document).on('click', '.editar-estabelecimento', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const data = getDataTableRowData(estabelecimentosTable, this);
        buscarEstabelecimento(data.id);
    });

    $(document).on('click', '.remover-estabelecimento', function (e) {
        e.preventDefault();
        e.stopPropagation();

        if (!confirm('Tem certeza que deseja excluir este estabelecimento?')) {
            return false;
        }

        const data = getDataTableRowData(estabelecimentosTable, this);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: `${ESTABELECIMENTO_ROUTES.base}/${data.id}`,
            type: 'POST',
            data: { _method: 'DELETE' },
            success: function (res) {
                if (res.error) {
                    showAlert(res.message || "Erro ao remover o estabelecimento!", 'danger');
                } else {
                    estabelecimentosTable.ajax.reload(null, false);
                    showAlert(res.message || "Remoção feita com sucesso!", 'success');
                }
            },
            error: function () {
                showAlert('Erro ao tentar excluir o estabelecimento.', 'danger');
            }
        });
    });

    $(document).on('click', '#adicionar-produto', function(e){
        e.preventDefault();
        e.stopPropagation();

        $('#modalAddProduto').modal('show');
    });

    $('.buscarCep').on('click', function () {
        const $form = $('#estabelecimento-form');
        var cep = $form.find('[name=cep]').val().replace(/\D/g, '');

        if (cep.length !== 8) {
            alert('CEP inválido!');
            return;
        }

        $.get(`https://viacep.com.br/ws/${cep}/json/`, function (data) {
            if (data.erro) {
                alert('CEP não encontrado!');
            } else {
                $form.find('[name=endereco]').val(data.logradouro);
                $form.find('[name=bairro]').val(data.bairro);
                $form.find('[name=cidade]').val(data.localidade);
                $form.find('[name=estado]').val(data.uf);
            }
        }).fail(function () {
            alert('Erro ao consultar o CEP.');
        });
    });


    function buscarEstabelecimento(id) {
        $.ajax({
            url: `${ESTABELECIMENTO_ROUTES.base}/${id}/edit`,
            type: 'GET',
            success: function (res) {
                if (res.error) {
                    showAlert(res.message || "Não foi possível carregar o registro!", 'danger');
                } else {
                    showModal(res);
                }
            },
            error: function () {
                showAlert('Erro ao buscar dados do estabelecimento.', 'danger');
            }
        });
    }

    function exibirEstabelecimento(id) {
        $.ajax({
            url: ESTABELECIMENTO_ROUTES.show.replace(':id', id),
            type: 'GET',
            success: function (res) {
                if (res.error) {
                    showAlert(res.message || 'Falha ao buscar dados!', 'danger');
                } else {
                    $('#modalEstabelecimentoShow .modal-body').html(res);
                    $('#modalEstabelecimentoShow').modal('show');
                }
            },
            error: function () {
                showAlert('Erro ao buscar os dados do estabelecimento.', 'danger');
            }
        });
    }

    function showModal(data = {}) {
        const id = data.id || '';
        const actionUrl = `${ESTABELECIMENTO_ROUTES.base}${id ? '/' + id : ''}`;
        const method = id ? 'PUT' : 'POST';

        const $form = $('#estabelecimento-form');
        $form.attr('action', actionUrl);
        $form[0].reset();

        $form.find('[name="id"]').val(data.id || '');
        $form.find('[name="nome"]').val(data.nome || '');
        $form.find('[name="descricao"]').val(data.descricao || '');
        $form.find('[name="endereco"]').val(data.endereco || '');
        $form.find('[name="cep"]').val(data.cep || '');
        $form.find('[name="numero"]').val(data.numero || '');
        $form.find('[name="complemento"]').val(data.complemento || '');
        $form.find('[name="bairro"]').val(data.bairro || '');
        $form.find('[name="cidade"]').val(data.cidade || '');
        $form.find('[name="estado"]').val(data.estado || '');

        $('#modalEstabelecimento').modal('show');
    }

    function getDataTableRowData(datatable, element) {
        const $row = $(element).closest('tr');
        let data = datatable.row($row).data();

        if (!data) data = datatable.row(element).data();

        return data;
    }

    function showAlert(message, type = 'success') {
        const alertId = 'custom-alert-estab';
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

    $form.on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();

        if ($form.data('ajax')) return;

        const formData = new FormData(this);
        const id = formData.get('id');
        const method = id ? 'PUT' : 'POST';

        formData.append('_method', method);
        formData.append('ajax', true);

        $form.data('ajax', true);

        $.ajax({
            url: `${ESTABELECIMENTO_ROUTES.base}${id ? '/' + id : ''}`,
            type: 'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function (res) {
                if (res.error) {
                    showAlert(res.message || "Não foi possível salvar o registro!", 'danger');
                } else {
                    estabelecimentosTable.ajax.reload(null, false);
                    showAlert(res.message || "Registro salvo com sucesso!", 'success');
                    $('#modalEstabelecimento').modal('hide');
                }
            },
            error: function () {
                showAlert("Erro ao enviar dados para o servidor!", 'danger');
            },
            complete: function () {
                $form.data('ajax', false);
            }
        });
    });
});
