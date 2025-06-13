<div class="container mt-0">
    <div class="border rounded p-4">
        <div class="card-body">
            <form action="{{ route('estabelecimento.store') }}" id="estabelecimento-form" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="{{ isset($method) ? $method : 'POST' }}" />
                <input type="hidden" name="id" id="id" value="">

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" placeholder="Nome do Estabelecimento" required>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="cep" class="form-label">CEP</label>
                        <div class="input-group">
                            <input type="text" name="cep" class="form-control" placeholder="00000-000" required>
                            <button class="btn btn-outline-secondary buscarCep" type="button" title="Buscar endereço pelo CEP">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" name="endereco" class="form-control" placeholder="Rua A" required>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-3">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text" name="numero" class="form-control" placeholder="1" required>
                    </div>
                    <div class="col-md-4">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input type="text" name="complemento" class="form-control" placeholder="Casa Azul" required>
                    </div>
                    <div class="col-md-5">
                        <label for="bairro" class="form-label">Bairro</label>
                        <input type="text" name="bairro" class="form-control" placeholder="Centro" required>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-7">
                        <label for="cidade" class="form-label">Cidade</label>
                        <input type="text" name="cidade" class="form-control" placeholder="Caratinga" required>
                    </div>
                    <div class="col-md-5">
                        <label for="estado" class="form-label">Estado</label>
                        <input type="text" name="estado" class="form-control" placeholder="MG" required>
                    </div>
                </div>

                <div class="mt-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3" placeholder="Descreva o estabelecimento" required></textarea>
                </div>

                <div class="mt-3">
                    <label for="foto_fachada" class="form-label">Foto do Estabelecimento</label>
                    <input type="file" name="foto_fachada" id="foto_fachada" class="form-control" accept="image/*">
                    <img id="preview" class="img-thumbnail mt-2 d-none" alt="Prévia da imagem" style="max-width: 200px;">
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check-circle"></i> Cadastrar Estabelecimento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
