<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CarrinhoController extends Controller
{
    public function adicionar(Request $request)
    {
        $produtoId = $request->input('produto_id');
        $quantidade = $request->input('quantidade');

        $produto = \App\Models\Produto::findOrFail($produtoId);

        if ($quantidade > $produto->estoque) {
            return response()->json(['erro' => 'Estoque insuficiente'], 422);
        }

        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$produtoId])) {
            $carrinho[$produtoId]['quantidade'] += $quantidade;
        } else {
            $carrinho[$produtoId] = [
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'quantidade' => $quantidade,
            ];
        }

        session()->put('carrinho', $carrinho);

        return response()->json(['itens' => array_values($carrinho)]);
}

}