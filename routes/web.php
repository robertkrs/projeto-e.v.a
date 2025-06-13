<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\EstabelecimentoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\HomeController;

Route::get('/', [LoginController::class, 'index'] )->name('site.login-index');
Route::any('/login', [LoginController::class, 'login'])->name('site.login');

Route::resource('user', UserController::class)->except('show','destroy','edit');

Route::prefix('/painel')->group(function(){    
    Route::get('/home', [HomeController::class, 'index'])->name('painel.home');
    Route::get('/logout', [LoginController::class, 'logout'])->name('painel.logout');
    
    /** */
    Route::resource('agenda', AgendaController::class);

    /**Produto */
    Route::get('/produto/datatable', [ProdutoController::class, 'datatable'])->name('produto.datatable');
    Route::resource('produto', ProdutoController::class);

    /**Estabelecimento */
    Route::any('/estabelecimento/{estabelecimento}/adicionar-produto', [EstabelecimentoController::class, 'adicionarProduto'])->name('estabelecimento.adicionar-produto');
    Route::get('/estabelecimento/{estabelecimento}/produtos', [EstabelecimentoController::class, 'listagemProdutos'])->name('estabelecimento.produtos');
    Route::get('/estabelecimento/painel', [EstabelecimentoController::class, 'painelEstabelecimento'])->name('estabelecimento.painel');
    Route::get('/estabelecimento/datatable', [EstabelecimentoController::class, 'datatable'])->name('estabelecimento.datatable');
    Route::resource('estabelecimento', EstabelecimentoController::class);
});

Route::fallback(function() {
    echo 'A rota acessada não existe. <a href="'.route('painel.home').'">clique aqui</a> para ir para página inicial';
});