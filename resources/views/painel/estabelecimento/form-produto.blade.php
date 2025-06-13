<div class="container mt-0">
    <div class="border rounded p-4">
        <div class="card-body">
            <form action="{{ route('estabelecimento.adicionar-produto',$estabelecimento->id)}}" id="estabelecimento-produto-form" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="{{ isset($method) ? $method : 'POST' }}" />
                <input type="hidden" name="id" id="id" value="">

                <div class="row">
                    <div class="col-sm-12">
                        <select id="filtro-produto" name="produto_id" class="form-select form-select-sm">
                            <option value="">Selecione</option>
                            @foreach ($produtos as $produto)
                                <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                            @endforeach
                        </select> 
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check-circle"></i> Adicionar Produto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
