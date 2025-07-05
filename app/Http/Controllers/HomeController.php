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
            return redirect()->to('/');
        } 

        if($user->tipo != User::COOPERATIVA){
            $estabelecimentos = Estabelecimento::query()
                ->get()
                ->groupBy(function ($estabelecimento) {
                    return 'Cooperativa: '.$estabelecimento->user->name ?? 'Cooperativa Desconhecida';
                });;
        }else{
            $estabelecimentos = Estabelecimento::with('produtos', 'user')
                ->where('usuario_id', $user->id)
                ->get()
                ->groupBy(function ($estabelecimento) {
                    return 'Cooperativa: '.$estabelecimento->user->name ?? 'Cooperativa Desconhecida';
                });
        }

        return view('painel.home', compact('estabelecimentos','user'));
    }
}
