$(document).ready(function () {
    carregarCarrinho();
    atualizarCarrinho();

    $(document).on('click', '.mais', function () {
        const input = $(this).closest('form').find('input[name="quantidade"]');
        const max = parseInt(input.attr('max')) || 99;
        let valor = parseInt(input.val()) || 1;
        if (valor < max) input.val(valor + 1);
    });

    $(document).on('click', '.menos', function () {
        const input = $(this).closest('form').find('input[name="quantidade"]');
        let valor = parseInt(input.val()) || 1;
        if (valor > 1) input.val(valor - 1);
    });

    $(document).on('click', '.remover-item', function () {
        const id = $(this).closest('.cart-item').data('id');
        delete carrinho[id];
        salvarCarrinho();
        renderizarCarrinho();
    });

    $(document).on('click', '.adicionar-carrinho', function () {
        const $btn = $(this);
        const produtoId = parseInt($btn.data('id'));
        const nome = $btn.data('nome');
        const preco = parseFloat($btn.data('preco'));
        const estoque = parseInt($btn.data('estoque'));

        const $form = $btn.closest('.quantidade-container');
        const quantidade = parseInt($form.find('input[name="quantidade"]').val()) || 1;

        adicionarAoCarrinho(produtoId, nome, preco, quantidade, estoque);
        atualizarBotaoFinalizar();
    });
});

function toggleCartSidebar() {
    const $sidebar = $('#cart-sidebar');
    const visivel = $sidebar.hasClass('aberto');
    $sidebar.toggleClass('aberto');
    $sidebar.css('transform', visivel ? 'translateX(100%)' : 'translateX(0%)');
}

const carrinho = {};

function adicionarAoCarrinho(produtoId, nome, preco, quantidade, estoqueMax) {
    if (!carrinho[produtoId]) {
        carrinho[produtoId] = {
            nome: nome,
            preco: preco,
            quantidade: 0,
            estoque: estoqueMax
        };
    }

    const novoTotal = carrinho[produtoId].quantidade + quantidade;

    if (novoTotal > estoqueMax) {
        alert('Quantidade excede o estoque disponível.');
        return;
    }

    carrinho[produtoId].quantidade = novoTotal;

    salvarCarrinho();
    renderizarCarrinho();
}

function renderizarCarrinho() {
    const $itemsContainer = $('#cart-items');
    $itemsContainer.html('');

    let totalItens = 0;

    for (let id in carrinho) {
        const item = carrinho[id];
        const subtotal = (item.preco * item.quantidade).toFixed(2).replace('.', ',');

        $itemsContainer.append(`
            <div class="cart-item d-flex justify-content-between align-items-center mb-2" data-id="${id}">
                <div>
                    <strong>${item.nome}</strong><br>
                    <small>${item.quantidade} x R$ ${item.preco.toFixed(2).replace('.', ',')}</small>
                </div>
                <div class="text-end">
                    <span class="text-success fw-bold d-block">R$ ${subtotal}</span>
                    <button class="btn btn-link text-danger p-0 remover-item" title="Remover">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        `);

        totalItens += item.quantidade;
    }

    $('#cart-count').text(totalItens);

    atualizarCarrinho();
}

function atualizarCarrinho() {
    const cartItems = document.getElementById('cart-items');
    const finalizarWrapper = document.getElementById('finalizar-wrapper');
    const emptyMessage = document.getElementById('cart-empty-message');

    if (cartItems.innerHTML.trim() === '') {
        finalizarWrapper.style.display = 'none';
        emptyMessage.style.display = 'block';
    } else {
        finalizarWrapper.style.display = 'block';
        emptyMessage.style.display = 'none';
    }
}

function carregarCarrinho() {
    const salvo = localStorage.getItem('carrinho');
    if (salvo) {
        Object.assign(carrinho, JSON.parse(salvo));
        renderizarCarrinho();
    }
}

function salvarCarrinho() {
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
}



