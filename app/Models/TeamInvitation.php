<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeamInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'email',
        'token',
        'role',
        'accepted_at',
        'expires_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'expires_at'  => 'datetime',
    ];

    // L'invito appartiene a un team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Il team ha molti inviti
    public function invitations()
    {
        return $this->hasMany(TeamInvitation::class);
    }
    
    // Controlla se l'invito è scaduto
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    // Controlla se l'invito è già stato accettato
    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }


}