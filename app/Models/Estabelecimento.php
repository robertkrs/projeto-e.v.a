<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estabelecimento extends Model
{
    protected $table = 'estabelecimento';

    protected $fillable = [
        'nome',
        'usuario_id',
        'descricao',
        'foto_fachada',
        'endereco',
        'cep',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'estabelecimento_produto', 'estabelecimento_id', 'produto_id');
    }

    public function toArray()
    {
        $arrData = parent::toArray();
        $arrData['text']             = $arrData['nome'];
        $arrData['usuario']          = $this->user->toArray();
        $arrData['produtos']         = $this->produtos->toArray();
        $arrData['enderecoCompleto'] = $this->enderecoCompleto();
        return $arrData;
    }

    public function enderecoCompleto()
    {
        $endereco = '';
        $endereco .= $this->endereco;
        $endereco .= ', ' . $this->numero;
        $endereco .= ', ' . $this->complemento;
        $endereco .= ', ' . $this->bairro;
        $endereco .= ', ' . $this->cidade;
        $endereco .= ' - ' . $this->estado;
        $endereco .= ', ' . $this->cep;
        $endereco = preg_replace('/([\s,-] )([\s,-] ){1,}/', '$1', $endereco);
        $endereco = trim($endereco, ', -');

        return $endereco;
    }

    public function getProdutos()
    {
        return $this->produtos()->get();
        // return $this->produtos->toArray();
    }
}
