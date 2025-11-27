<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustodyLog extends Model
{
    protected $table = 'custody_logs';

    protected $fillable = [
        'cautela_number',
        'user_id',
        'checkout_date',
        'checkin_date',
        'term_url',
        'signed_term_url',
        'notes',
    ];

    protected $casts = [
        'checkout_date' => 'datetime',
        'checkin_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(MilitaryUser::class, 'user_id');
    }

    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'custody_assets');
    }

    /**
     * Scope para filtrar cautelas baseado no perfil do usuário
     */
    public function scopeForUser($query, MilitaryUser $user)
    {
        // Admin vê todas
        if ($user->isAdmin()) {
            return $query;
        }

        // Usuário vê apenas suas próprias cautelas
        if ($user->isUser()) {
            return $query->where('user_id', $user->id);
        }

        // Comissão vê suas próprias cautelas
        // TODO: Adicionar lógica para ver cautelas relacionadas aos inventários da comissão
        if ($user->isCommission()) {
            return $query->where('user_id', $user->id);
        }

        // Se não for nenhum dos perfis, não retorna nada
        return $query->whereRaw('1 = 0');
    }
}
