<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DataTables;
use Illuminate\Database\Eloquent\Builder;

class ProdutoController extends Controller
{
    public function index()
    {   
        /** @var User $user */
        $user       = User::findByEmail(session('email'));
        $produto    = new Produto();
        $categorias = Produto::select('categoria')->distinct()->pluck('categoria');
        
        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }else if($user->tipo != User::PRODUTOR){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        return view('painel.produto.index', compact('categorias', 'produto'));
    }

    public function datatable(Request $request)
    {
        /** @var User $user */
        $user      = User::findByEmail(session('email'));
        $categoria = $request->input('categoria', 0);

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }else if($user->tipo != User::PRODUTOR){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        $produtos = Produto::select(['id', 'nome', 'categoria', 'preco', 'estoque', 'foto']);

        if($categoria){
            $produtos->where('produtos.categoria', $categoria);
        }

        return datatables()->of($produtos)
            ->addColumn('foto', function ($produto) {
                if ($produto->foto) {
                    return '<img src="' . asset('storage/' . $produto->foto) . '" width="60" class="img-thumbnail">';
                }
                return '<span class="text-muted">Sem foto</span>';
            })
            ->addColumn('acoes', function ($produto) {
                $editar = route('produto.edit', $produto->id);
                $excluir = route('produto.destroy', $produto->id);
                return '
                    <a href="' . $editar . '" class="editar-produto btn btn-sm btn-outline-primary me-1">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <form action="' . $excluir . '" method="POST" style="display:inline-block" onsubmit="return confirm(\'Tem certeza que deseja excluir este produto?\')">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['foto', 'acoes'])
            ->filter(
                function (Builder $query) use ($request) {
                    $query->where(
                        function (Builder $query) use ($request) {
                            $search = "%" . $request->input('search') . "%";
                            $query->orWhere('produtos.nome', 'like', $search);
                            $query->orWhere('produtos.categoria', 'like', $search);
                            $query->orWhere('produtos.descricao', 'like', $search);
                        }
                    );
                },
                true
            )
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
        }else if($user->tipo != User::PRODUTOR){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        return view('painel.produto.create');
    }

    public function show(Request $request, Produto $produto)
    {
        /** @var User $user */
        $user = User::findByEmail(session('email'));

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }else if($user->tipo != User::PRODUTOR){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        return view('painel.produto.show', compact('produto'));
    }

    public function store(Request $request)
    {
        $produto  = new Produto();
        $erro     = 'success';
        $mensagem = 'Produto criado com sucesso!';

        $user = User::findByEmail(session('email'));

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }else if($user->tipo != User::PRODUTOR){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        $arrData = $this->checkData($request);

        if (isset($arrData['error'])) {
            return $arrData;
        }

        $produto->fill($arrData);
        $produto->usuario_id = $user->id;

        if ($request->hasFile('foto')) {
            $path          = $request->file('foto')->store('produtos', 'public');
            $produto->foto = $path;
        }

        if(!$produto->save()){
            $erro = 'error';
            $mensagem = 'Erro ao salvar produto!';

            session()->flash($erro, $mensagem);
            return redirect()->back(); 
        }

        session()->flash($erro, $mensagem);
        
        return redirect()->back();
    }

    public function edit(Request $request, Produto $produto)
    {
        /** @var User $user */
        $user = User::findByEmail(session('email'));

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }else if($user->tipo != User::PRODUTOR){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        if ($request->ajax()) {
            $arrDados = $produto->toArray();

            return $arrDados;
        }

        return view('painel.produto.edit', compact('produto'));
    }

    public function update(Request $request, $id)
    {
        /** @var User $user */
        $user     = User::findByEmail(session('email'));
        $erro     = 'success';
        $mensagem = 'Produto criado com sucesso!';

        if(empty($user)){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }else if($user->tipo != User::PRODUTOR){
            $erro     = 'error';
            $mensagem = 'Usuário não tem permissão!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        $produto = Produto::where('usuario_id', $user->id)->findOrFail($id);

        $arrData = $this->checkData($request);

        if (isset($arrData['error'])) {
            return $arrData;
        }

        $produto->fill($arrData);

        if ($request->hasFile('foto')) {
            if ($produto->foto) {
                Storage::disk('public')->delete($produto->foto);
            }
            $produto->foto = $request->file('foto')->store('produtos', 'public');
        }

        if(!$produto->save()){
            $erro = 'error';
            $mensagem = 'Erro ao salvar produto!';

            session()->flash($erro, $mensagem);
            return redirect()->back(); 
        }

            session()->flash($erro, $mensagem);

        return redirect()->back();
    }

    public function destroy(Request $request, Produto $produto)
    {
        /** @var User $user */
        $user     = User::findByEmail(session('email'));
        $erro     = 'success';
        $mensagem = 'Produto removido com sucesso.';


        if(empty($user) && $user->tipo != User::PRODUTOR){
            $erro     = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        if ($produto->foto) {
            Storage::disk('public')->delete($produto->foto);
        }

        if(!$produto->delete()){
            $erro     = 'error';
            $mensagem = 'Não foi possivel remover o produto!';

            session()->flash($erro, $mensagem);
            return redirect()->back();
        }
        
        session()->flash($erro, $mensagem);

        return redirect()->route('produto.index');
    }

    private function checkData(Request $request) {
        $rules = [
            'nome'      => 'required',
            'descricao' => 'required',
            'categoria' => 'required',
            'preco'     => 'required|numeric',
            'estoque'   => 'required|integer',
            'foto'      => 'nullable|image|max:2048'
        ];

        $feedback = [
            'nome.required'        => 'O campo nome é obrigatório!',
            'descricao.required'   => 'O campo descrição é obrigatório!',
            'categoria.required'   => 'O campo categoria é obrigatorio!',
            'preco.required'       => 'O campo preço é obrigatorio!',
            'estoque.required'     => 'O campo estoque é obrigatorio!',
        ];

        return $request->validate($rules, $feedback);
    }
}
