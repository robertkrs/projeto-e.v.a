@extends('layouts.basico')

@section('titulo', 'Cadastro de Usuário')

@section('conteudo')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow rounded">
                <div class="card-header bg-success text-white text-center">
                    <h4>Cadastro de Usuário</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('user.store') }}" method="POST" id="form-usuario">
                        @csrf
                        <input type="hidden" name="_method" value="{{ isset($method) ? $method : 'POST' }}">
                        <input type="hidden" name="id" value="{{ $model->id ?? '' }}">

                        {{-- Nome --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" name="name" id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $model->name ?? '') }}" 
                                   placeholder="Digite seu nome" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- E-mail --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $model->email ?? '') }}"
                                   placeholder="Digite seu e-mail"
                                   {{ isset($model->id) ? 'readonly' : '' }} required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- CPF e Telefone --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" name="cpf" id="cpf"
                                       class="form-control @error('cpf') is-invalid @enderror"
                                       value="{{ old('cpf', $model->cpf ?? '') }}"
                                       placeholder="Digite seu CPF" required>
                                @error('cpf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" name="telefone" id="telefone"
                                       class="form-control @error('telefone') is-invalid @enderror"
                                       value="{{ old('telefone', $model->telefone ?? '') }}"
                                       placeholder="Digite seu telefone" required>
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="tipo" class="form-label">Tipo de Usuário</label>
                                <select name="tipo" id="tipo" class="form-control" data-placeholder="Selecione o tipo">
                                    <option value=""></option> {{-- necessário para funcionar o "X" --}}
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        </div>

                        {{-- Senhas --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" name="senha" id="senha"
                                       class="form-control @error('senha') is-invalid @enderror"
                                       placeholder="Digite sua senha" required>
                                @error('senha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="outra-senha" class="form-label">Confirme a Senha</label>
                                <input type="password" name="outra-senha" id="outra-senha"
                                       class="form-control @error('outra-senha') is-invalid @enderror"
                                       placeholder="Repita sua senha" required>
                                @error('outra-senha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Botão --}}
                        <div class="text-center">
                            <button type="submit" class="btn btn-success w-50">Salvar</button>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-muted text-center small">
                    Contato: (33) 98886-9730 | robertreis323@gmail.com
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const $form = $('#form-usuario');

        // Máscaras
        $form.find('[name=cpf]').inputmask(['999.999.999-99', '99.999.999/9999-99']);
        $form.find('[name=telefone]').inputmask("(99) 9999-99999")
            .on('focusout', function (event) {
                const phone = event.target.value.replace(/\D/g, '');
                const $element = $(event.target);
                $element.unmask();
                if (phone.length > 10) {
                    $element.inputmask("(99) 99999-9999");
                } else {
                    $element.inputmask("(99) 9999-99999");
                }
            });

        // Inicialização do select2
        $('#tipo').select2({
            data: [
                { id: '', text: 'Selecione o tipo' },
                { id: 'produtor', text: 'Produtor' },
                { id: 'cooperativa', text: 'Cooperativa' },
                { id: 'consumidor', text: 'Consumidor' }
            ],
            placeholder: "Selecione o tipo",
            allowClear: true,
            width: '100%'
        });

        // Seleciona valor antigo, se houver
        const valorAntigo = '{{ old("tipo", $model->tipo ?? "") }}';
        if (valorAntigo) {
            $('#tipo').val(valorAntigo).trigger('change');
        }
    });
</script>
@endpush
