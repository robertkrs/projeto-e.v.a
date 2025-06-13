<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $table = 'agenda';

    protected $fillable = [
        'titulo',
        'user_id',
        'data_inicio',
        'data_fim',
        'descricao',
    ];

    protected $dates = ['data_inicio', 'data_fim'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function toArray()
    {
        $arrData = parent::toArray();
        $arrData['text'] = $arrData['titulo'];

        return $arrData;
    }
}
