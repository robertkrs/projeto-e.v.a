<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'usuario_id',
        'descricao',
        'foto',
        'categoria',
        'preco',
        'estoque',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function estabelecimentos()
    {
        return $this->belongsToMany(Estabelecimento::class, 'estabelecimento_produto', 'produto_id', 'estabelecimento_id');
    }

    public function toArray()
    {
        $arrData = parent::toArray();
        $arrData['text'] = $arrData['nome'];

        return $arrData;
    }
}
