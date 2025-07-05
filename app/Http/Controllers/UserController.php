<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return view('site.cadastro-usuario', ['model' => new User]);
    }

    private function checkData(Request $request)
    {
        $id = $request->input('id', null);

        $regras = [
            'name'      => 'required|min:3|max:50',
            'email'     => 'required_without:telefone|unique:users,email',
            'telefone'  => 'required_without:email|unique:users,telefone',
            'tipo'      => 'required',
            'cpf'       => 'required|unique:users,cpf',
            'senha'     => (!$id ? 'required|' : 'nullable|') . 'min:6',
        ];

        $feedback = [
            'name.min'         => 'O campo nome precisa ter no mínimo 3 caracteres',
            'name.max'         => 'O campo nome deve ter no máximo 50 caracteres',
            'email.unique'     => 'O email informado já está cadastrado',
            'telefone.unique'  => 'O telefone informado já está cadastrado',
            'email.email'      => 'O email informado não é válido',
            'cpf.unique'       => 'O CPF cadastrado já está cadastrado',
            'required'         => 'O campo :attribute deve ser preenchido',
            'required_without' => 'O campo :attribute deve ser preenchido',
        ];

        return $request->validate($regras, $feedback);
    }

    public function store(Request $request) 
    {
        $user = new User;
        $erro = 'success';
        $mensagem = 'Usuário criado com sucesso.';
        $arrData = $this->checkData($request);

        if (isset($arrData['error'])) {
            return $arrData;
        }
        
        if($request->input('senha') != $request->input('outra-senha')){
            $erro = 'erro';
            $mensagem = 'As senhas devem ser iguais';

            session()->flash($erro, $mensagem);
            
            return redirect()->to('/user');
        }

        $user->fill($arrData);
        $user->password = md5($request->input('senha'));
        $user->telefone = preg_replace('/[^0-9]/','', $arrData['telefone']);
        
        $conflito = User::verificarDuplicidade($user);        
        
        if(isset($conflito)){
            $erro = 'erro'; 
            $mensagem = 'O campo ' .$conflito. ' já foi cadastrado!';
        }elseif(!$user->celular && !$user->email){
            $erro = 'erro';
            $mensagem = 'Informe o e-mail ou o celular para continuar.';
        }elseif(!$user->save()){
            $erro = 'erro';
            $mensagem = 'Aconteceu um erro ao salvar o usuário';
        }

        session()->flash($erro, $mensagem);

        if($erro == 'erro'){
            return redirect()->route('user.index')->with('erro', $mensagem)->withInput();
        }

        return redirect()->route('site.login-index')->with('success', $mensagem);
    }
}
