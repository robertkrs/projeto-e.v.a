<div class="card border rounded shadow-sm position-relative h-100">
    <div class="position-absolute top-0 end-0 m-2">
        <span class="badge bg-success">OFERTA</span>
    </div>

    <div class="position-absolute top-0 start-0 m-2">
        <span class="badge bg-danger">{{ $produto->categoria }}</span>
    </div>

    @if ($produto->foto)
        <img src="{{ asset('storage/' . $produto->foto) }}" class="card-img-top p-2" style="max-height: 140px; object-fit: contain;" alt="{{ $produto->nome }}">
    @endif

    <div class="card-body text-center d-flex flex-column justify-content-between">
        <h6 class="card-title">{{ $produto->nome }}</h6>

        <div class="my-2">
            <span class="fw-bold bg-dark text-white px-3 py-1 rounded-pill d-inline-block">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
        </div>

        <div class="text-muted" style="font-size: 0.85rem;">Estoque: {{ $produto->estoque }}</div>
        <div class="text-muted" style="font-size: 0.85rem;">Produtor: {{ $produto->user->name }}</div>

        @if (session('tipo') === 'Consumidor')
            <form method="POST" class="mt-3 d-flex justify-content-center align-items-center gap-1 quantidade-container">
                @csrf
                <button type="button" class="btn btn-outline-secondary btn-sm menos">-</button>
                <input type="number" name="quantidade" class="form-control form-control-sm text-center" value="1" min="1" max="{{ $produto->estoque }}" style="width: 50px;">
                <button type="button" class="btn btn-outline-secondary btn-sm mais">+</button>
                <button type="button"
                    class="btn btn-dark btn-sm adicionar-carrinho"
                    data-id="{{ $produto->id }}"
                    data-nome="{{ $produto->nome }}"
                    data-preco="{{ $produto->preco }}"
                    data-estoque="{{ $produto->estoque }}">
                <i class="fa fa-cart-plus"></i>
            </button>

            </form>
        @elseif (session('tipo') === 'Produtor')
            <a href="#" data-id="{{ $produto->id }}" class="editar-produto btn btn-outline-primary btn-sm mt-3">Editar</a>
        @endif
    </div>
</div>
