@extends($ajax ? 'layouts.empty' : 'layouts.basico')

@section('titulo', 'Ver Estabelecimento')

@section('conteudo')
<div class="container my-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-body p-4">

            @if ($estabelecimento->foto_fachada)
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/' . $estabelecimento->foto_fachada) }}" 
                         alt="Foto da Fachada" 
                         class="img-fluid rounded shadow-sm" 
                         style="max-height: 300px;">
                </div>
            @endif

            <h3 class="text-center fw-bold mb-4">{{ $estabelecimento->nome }}</h3>

            <div class="mb-3">
                <label class="form-label">Descrição:</label>
                <div class="form-control-plaintext">{{ $estabelecimento->descricao ?? '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Endereço:</label>
                    <div class="form-control-plaintext">
                        {{ $estabelecimento->enderecoCompleto() }}
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Usuário Responsável:</label>
                    <div class="form-control-plaintext">{{ $estabelecimento->user->name ?? 'Usuário não encontrado' }}</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cadastrado em:</label>
                    <div class="form-control-plaintext">
                        {{ $estabelecimento->created_at ? $estabelecimento->created_at->format('d/m/Y H:i') : '-' }}
                    </div>
                </div>
            </div>

            {{-- BOTÃO VOLTAR --}}
            @if (empty($ajax))
                <div class="text-center mt-4">
                    <a href="{{ route('estabelecimento.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Voltar para Listagem
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
