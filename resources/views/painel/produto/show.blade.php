@extends($ajax ? 'layouts.empty' : 'layouts.basico')

@section('titulo', 'Ver Produto')

@section('conteudo')
<div class="container my-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-body p-4">

            @if ($produto->foto)
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/' . $produto->foto) }}" 
                         alt="Foto do Produto" 
                         class="img-fluid rounded shadow-sm" 
                         style="max-height: 300px;">
                </div>
            @endif

            <h3 class="text-center fw-bold mb-4">{{ $produto->nome }}</h3>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Categoria:</label>
                    <div class="form-control-plaintext">{{ $produto->categoria ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Preço:</label>
                    <div class="form-control-plaintext">R$ {{ number_format($produto->preco, 2, ',', '.') }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Estoque:</label>
                    <div class="form-control-plaintext">{{ $produto->estoque }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Produtor (Usuário):</label>
                    <div class="form-control-plaintext">{{ $produto->user->name ?? 'Usuário não encontrado' }}</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição:</label>
                <div class="form-control-plaintext">{{ $produto->descricao }}</div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Criado em:</label>
                    <div class="form-control-plaintext">
                        {{ $produto->created_at ? $produto->created_at->format('d/m/Y H:i') : '-' }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Atualizado em:</label>
                    <div class="form-control-plaintext">
                        {{ $produto->updated_at ? $produto->updated_at->format('d/m/Y H:i') : '-' }}
                    </div>
                </div>
            </div>

            @if (empty($ajax))
                <div class="text-center mt-4">
                    <a href="{{ route('produto.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Voltar para Listagem
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
