@extends($ajax ? 'layouts.empty' : 'layouts.basico')

@section('titulo', 'Gestão de Produto')

@section('conteudo')
    <div id="container-agenda">
        <div class="titulo-pagina">
            <h1>Agendamento</h1>
        </div>
            <div class="list-group" style="padding: 100px 400px">
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start ">
                    <div class="d-flex w-100 justify-content-between" style="width:50%">
                        <h5 class="mb-1">Titulo: <small>{{$model->titulo ? $model->titulo : '-'}}</small></h5>
                        
                    </div>
                    <div class="form-group">
                        <label>Data de inicio:</label>
                        <small class="form-control-static">{{$model->data_inicio ? $model->data_inicio : '-'}}</small>
                    </div>
                    <div class="form-group">
                        <label>Data de fim:</label>
                        <small class="form-control-static">{{$model->data_fim ? $model->data_fim : '-'}}</small>
                    </div>
                    <div class="form-group">
                        <label>Descrição:</label>
                        <small class="form-control-static">{!! $model->descricao ? $model->descricao : '-' !!}</small>
                    </div>
                </a>
            </div>
    </div>
@endsection