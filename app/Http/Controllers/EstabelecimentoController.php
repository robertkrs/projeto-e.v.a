<?php

namespace App\Http\Controllers;

use App\Models\Estabelecimento;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DataTables;
use Illuminate\Database\Eloquent\Builder;

class EstabelecimentoController extends Controller
{
    public function index()
    {   
        /** @var User $user */
        $user = User::findByEmail(session('email'));
        
        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }else if($user->tipo != User::COOPERATIVA){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        // $categorias = Estabelecimento::select('categoria')->distinct()->pluck('categoria');
        $produtos = [];
        return view('painel.estabelecimento.index', compact('produtos'));
    }

    public function datatable(Request $request)
    {
        /** @var User $user */
        $user = User::findByEmail(session('email'));

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }else if($user->tipo != User::COOPERATIVA){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        $estabelecimentos = Estabelecimento::select('estabelecimento.*')
            ->where('estabelecimento.usuario_id', $user->id);

        return datatables()->of($estabelecimentos)
            ->addColumn('foto_fachada', function ($estabelecimento) {
                if ($estabelecimento->foto_fachada) {
                    return '<img src="' . asset('storage/' . $estabelecimento->foto_fachada) . '" width="60" class="img-thumbnail">';
                }
                return '<span class="text-muted">Sem foto da fachada</span>';
            })
            ->addColumn('qtd_produtos', function ($estabelecimento) {
                return $estabelecimento->produtos->count() ?? 0;
            })
            ->addColumn('acoes', function ($estabelecimento) {
                $editar = route('estabelecimento.edit', $estabelecimento->id);
                $excluir = route('estabelecimento.destroy', $estabelecimento->id);
                return '
                    <a href="' . $editar . '" class="editar-estabelecimento btn btn-sm btn-outline-primary me-1">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <form action="' . $excluir . '" method="POST" style="display:inline-block" onsubmit="return confirm(\'Tem certeza que deseja excluir este estabelecimento?\')">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button class="btn btn-sm btn-outline-danger remover-estabelecimento">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['foto_fachada', 'acoes'])
            ->filter(function (Builder $query) use ($request) {
                $search = "%" . $request->input('search') . "%";
                $query->where(function (Builder $query) use ($search) {
                    $query->orWhere('estabelecimento.nome', 'like', $search)
                        ->orWhere('estabelecimento.endereco', 'like', $search)
                        ->orWhere('estabelecimento.descricao', 'like', $search)
                        ->orWhere('estabelecimento.cep', 'like', $search)
                        ->orWhere('estabelecimento.numero', 'like', $search)
                        ->orWhere('estabelecimento.complemento', 'like', $search)
                        ->orWhere('estabelecimento.bairro', 'like', $search)
                        ->orWhere('estabelecimento.cidade', 'like', $search)
                        ->orWhere('estabelecimento.estado', 'like', $search);
                });
            }, true)
            ->make(true);
    }

    public function create(Request $request)
    {
        /** @var User $user */
        $user = User::findByEmail(session('email'));

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }else if($user->tipo != User::COOPERATIVA){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }
        
        return view('painel.estabelecimento.create');
    }

    public function show(Request $request, Estabelecimento $estabelecimento)
    {
        /** @var User $user */
        $user = User::findByEmail(session('email'));

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }else if($user->tipo != User::COOPERATIVA){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        return view('painel.estabelecimento.show', compact('estabelecimento'));
    }

    public function store(Request $request)
    {
        $estabelecimento  = new Estabelecimento();
        $erro     = 'success';
        $mensagem = 'Estabelecimento criado com sucesso!';

        $user = User::findByEmail(session('email'));

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }else if($user->tipo != User::COOPERATIVA){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        $arrData = $this->checkData($request);

        if (isset($arrData['error'])) {
            return $arrData;
        }

        $estabelecimento->fill($arrData);
        $estabelecimento->usuario_id = $user->id;
        
        if ($request->hasFile('foto_fachada')) {
            $path                          = $request->file('foto_fachada')->store('estabelecimentos', 'public');
            $estabelecimento->foto_fachada = $path;
        }

        if(!$estabelecimento->save()){
            $erro     = 'error';
            $mensagem = 'Erro ao salvar estabelecimento!';

            session()->flash($erro, $mensagem);
            return redirect()->back(); 
        }

        session()->flash($erro, $mensagem);
        
        return redirect()->route('estabelecimento.index');
    }

    public function edit(Request $request, Estabelecimento $estabelecimento)
    {
        /** @var User $user */
        $user = User::findByEmail(session('email'));

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }else if($user->tipo != User::COOPERATIVA){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        if ($request->ajax()) {
            $arrDados = $estabelecimento->toArray();

            return $arrDados;
        }

        return view('painel.estabelecimento.edit', compact('estabelecimento'));
    }

    public function update(Request $request, $id)
    {
        /** @var User $user */
        $user     = User::findByEmail(session('email'));
        $erro     = 'success';
        $mensagem = 'Estabelecimento criado com sucesso!';

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }else if($user->tipo != User::COOPERATIVA){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        $estabelecimento = Estabelecimento::where('usuario_id', $user->id)->findOrFail($id);

        $arrData = $this->checkData($request);

        if (isset($arrData['error'])) {
            return $arrData;
        }

        $estabelecimento->fill($arrData);

        if ($request->hasFile('foto_fachada')) {
            if ($estabelecimento->foto_fachada) {
                Storage::disk('public')->delete($estabelecimento->foto_fachada);
            }
            $estabelecimento->foto_fachada = $request->file('foto_fachada')->store('estabelecimentos', 'public');
        }

        if(!$estabelecimento->save()){
            $erro = 'error';
            $mensagem = 'Erro ao salvar estabelecimento!';

            session()->flash($erro, $mensagem);
            return redirect()->back(); 
        }

            session()->flash($erro, $mensagem);

        return redirect()->route('estabelecimento.index');
    }

    public function destroy(Request $request, Estabelecimento $estabelecimento)
    {
        /** @var User $user */
        $user     = User::findByEmail(session('email'));
        $erro     = 'success';
        $mensagem = 'Estabelecimento removido com sucesso.';


        if(empty($user) && $user->tipo != User::COOPERATIVA){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        if ($estabelecimento->foto_fachada) {
            Storage::disk('public')->delete($estabelecimento->foto_fachada);
        }

        if(!$estabelecimento->delete()){
            $erro     = 'error';
            $mensagem = 'Não foi possivel remover o estabelecimento!';

            session()->flash($erro, $mensagem);
            return redirect()->back();
        }
        
        session()->flash($erro, $mensagem);

        return redirect()->route('estabelecimento.index');
    }

    public function painelEstabelecimento(Request $request)
    {
        /** @var User $user */
        $user     = User::findByEmail(session('email'));

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        } 

        if($user->tipo != User::COOPERATIVA){
            $estabelecimentos = Estabelecimento::query()->get();
        }else{
            $estabelecimentos = Estabelecimento::query()->where('usuario_id', $user->id)->get();
        }

        return view('painel.estabelecimento.painel', compact('estabelecimentos','user'));
    }

    public function listagemProdutos(Request $request, Estabelecimento $estabelecimento)
    {
        $prodSemEstabelecimento = null;
        $produtos               = null;
        $categoria              = $request->input('categoria');
        $categorias             = Produto::select('categoria')->distinct()->pluck('categoria');

        /** @var User $user */
        $user = User::findByEmail(session('email'));

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        } 

        if($user->tipo == User::PRODUTOR){
            $estabelecimentoId = $estabelecimento->id;
            
            $produtos = Produto::query()
                ->join('estabelecimento_produto', 'estabelecimento_produto.produto_id', 'produtos.id')
                ->where('estabelecimento_produto.estabelecimento_id', $estabelecimento->id)
                ->where('usuario_id', $user->id)
                ->orderBy('usuario_id');
                
            $prodSemEstabelecimento = Produto::where('usuario_id', $user->id)
                ->whereDoesntHave('estabelecimentos', function ($query) use ($estabelecimentoId) {
                    $query->where('estabelecimento.id', $estabelecimentoId);
                })
                ->get();

        }else{
             $produtos = Produto::query()
                ->join('estabelecimento_produto', 'estabelecimento_produto.produto_id', 'produtos.id')
                ->where('estabelecimento_produto.estabelecimento_id', $estabelecimento->id)
                ->orderBy('usuario_id');
        }

        if($categoria){
            $produtos->where('produtos.categoria', $categoria);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';

            $produtos->where(function($q) use ($search) {
                $q->where('nome', 'like', $search)
                ->orWhere('categoria', 'like', $search)
                ->orWhere('descricao', 'like', $search);
            });
        }

       $produtos = $produtos->get();

        return view('painel.estabelecimento.produtos', compact('estabelecimento','produtos','user','prodSemEstabelecimento','categorias'));
    }

    public function adicionarProduto(Request $request, Estabelecimento $estabelecimento)
    {
         /** @var User $user */
        $user = User::findByEmail(session('email'));

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        $produtoId = $request->input('produto_id');
        
        if (!$estabelecimento->produtos()->where('produto_id', $produtoId)->exists()) {
            $estabelecimento->produtos()->attach($produtoId);
        }

        session()->flash('success', 'Produto adicionado ao estabelecimento com sucesso!');
        
        return redirect()->back();
    }

    private function checkData(Request $request) {
        $rules = [
            'nome'         => 'required',
            'descricao'    => 'required',
            'endereco'     => 'required',
            'cep'          => 'required',
            'numero'       => 'required',
            'complemento'  => 'required',
            'bairro'       => 'required',
            'cidade'       => 'required',
            'estado'       => 'required',
            'foto_fachada' => 'nullable|image|max:2048'
        ];

        $feedback = [
            'nome.required'        => 'O campo nome é obrigatório!',
            'descricao.required'   => 'O campo descrição é obrigatório!',
            'categoria.required'   => 'O campo categoria é obrigatorio!',
            'endereco.required'    => 'O campo endereçoo é obrigatorio!',
            'cep.required'         => 'O campo cep é obrigatorio!',
            'numero.required'      => 'O campo numero é obrigatorio!',
            'complemento.required' => 'O campo complemento é obrigatorio!',
            'bairro.required'      => 'O campo bairro é obrigatorio!',
            'cidade.required'      => 'O campo cidade é obrigatorio!',
            'estado.required'      => 'O campo estado é obrigatorio!',
        ];

        return $request->validate($rules, $feedback);
    }
}
