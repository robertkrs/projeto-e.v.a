<?php

namespace App\Http\Controllers;

use App\Models\Estabelecimento;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
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

        return view('painel.home', compact('estabelecimentos','user'));
    }
}
