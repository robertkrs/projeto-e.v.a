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

    public static function findByEmail($email)
    {
        $user = Self::where('email', $email)->first();

        return $user;
    }

    public static function verificaTelefoneEmailCPF($user)
    {
         return Self::query()->where(function ($q) use ($user) {
            $q->where('telefone', $user->telefone)
            ->orWhere('cpf', $user->cpf)
            ->orWhere('email', $user->email);
        });
    }

    public static function verificarDuplicidade($user)
    {
        $usuario = self::verificaTelefoneEmailCPF($user)->first();

        if (!$usuario) return null;

        $campoConflitante = null;

        if ($usuario->telefone === $user->telefone) {
            $campoConflitante = 'telefone';
        } elseif ($usuario->cpf === $user->cpf) {
            $campoConflitante = 'cpf';
        } elseif ($usuario->email === $user->email) {
            $campoConflitante = 'email';
        }

        return $campoConflitante;
    }

    public function toArray()
    {
        $arrData = parent::toArray();
        $arrData['text'] = $arrData['name'];

        return $arrData;
    }
}
