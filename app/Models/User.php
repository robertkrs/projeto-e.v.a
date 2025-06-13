<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const PRODUTOR    = 'Produtor';
    const COOPERATIVA = 'Cooperativa';
    const CONSUMIDOR  = 'Consumidor';

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'telefone',
        'tipo',
        'cpf',
        'password',
    ];

    public function agenda()
    {
        return $this->hasMany(Agenda::class, 'user_id');
    }

    public static function findByEmail($email)
    {
        $user = Self::where('email', $email)->first();

        return $user;
    }

    public function toArray()
    {
        $arrData = parent::toArray();
        $arrData['text'] = $arrData['name'];

        return $arrData;
    }
}
