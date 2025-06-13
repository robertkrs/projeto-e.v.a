@extends('layouts.basico')
@section('titulo', 'Editar Produto')

@section('conteudo')
    @include('painel.produto.form', ['method' => 'PUT'])
@endsection