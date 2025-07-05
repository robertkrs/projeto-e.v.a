@extends('layouts.basico')

@section('titulo', 'Login')

@section('conteudo')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow rounded">
                <div class="card-header text-center bg-success text-white">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('site.login') }}" method="POST" id="login-form">
                        @csrf
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuário</label>
                            <input 
                                type="text" 
                                name="usuario" 
                                id="usuario" 
                                class="form-control @error('usuario') is-invalid @enderror" 
                                value="{{ old('usuario') }}" 
                                placeholder="Digite seu usuário"
                                required
                            >
                            @error('usuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="senha" class="form-label">Senha</label>
                            <input 
                                type="password" 
                                name="senha" 
                                id="senha" 
                                class="form-control @error('senha') is-invalid @enderror" 
                                placeholder="Digite sua senha"
                                required
                            >
                            @error('senha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Acessar</button>
                            <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">Cadastrar-se</a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center small text-muted">
                    <span>Precisa de ajuda? Contato: (33) 98886-9730 | robertreis323@gmail.com</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
