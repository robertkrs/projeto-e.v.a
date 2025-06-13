<div class="container py-5">
    <h2 class="mb-4">Cadastrar Novo Produto</h2>
    <form action="{{ route('produto.store') }}" id="produto-form" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" value="{{ isset($method) ? $method : 'POST' }}" />
        <input type="hidden" name="id" id="id" value="">

        <div class="row">
            <div class="col-sm-12 mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome', $produto->nome ? $produto->nome : null) }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4 mb-3">
                <label for="categoria" class="form-label">Categoria:</label>
                <select name="categoria" class="form-select" required>
                    <option value="">Selecione</option>
                    <option value="Hortaliças" {{$produto->categoria == 'Hortaliças' ? 'selected' : ''}}>Hortaliças</option>
                    <option value="Frutas" {{$produto->categoria == 'Frutas' ? 'selected' : ''}}>Frutas</option>
                    <option value="Laticínios" {{$produto->categoria == 'Laticínios' ? 'selected' : ''}}>Laticínios</option>
                    <option value="Grãos" {{$produto->categoria == 'Grãos' ? 'selected' : ''}}>Grãos</option>
                    <option value="Outros" {{$produto->categoria == 'Outros' ? 'selected' : ''}}>Outros</option>
                </select>
            </div>

            <div class="col-sm-4 mb-3">
                <label for="preco" class="form-label">Preço (R$):</label>
                <input type="number" name="preco" step="0.01" class="form-control" value="{{ old('preco', $produto->preco ? $produto->preco : null) }}" required>
            </div>

            <div class="col-sm-4 mb-3">
                <label for="estoque" class="form-label">Quantidade em Estoque:</label>
                <input type="number" name="estoque" class="form-control" value="{{ old('estoque', $produto->estoque ? $produto->estoque : null) }}" required>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea name="descricao" class="form-control" rows="4" required>{{$produto->descricao ? $produto->descricao : ''}}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 mb-3">
                <label for="foto" class="form-label">Foto do Produto:</label>
                <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
            </div>
        </div>

        <img id="preview" src="#" alt="Prévia da imagem" style="display:none; max-width: 200px; margin-top: 10px;">

        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-success">Salvar</button>
        </div>
    </form>
</div>