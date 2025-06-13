@extends('layouts.basico')
@section('titulo', 'Editar Estabelecimento')

@section('conteudo')
    @include('painel.estabelecimento.form', ['method' => 'PUT'])
@endsection