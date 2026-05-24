<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // L'utente può essere owner di molti team
    public function ownedTeams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    // L'utente appartiene a molti team
    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot('role')->withTimestamps();
    }

    // L'utente ha molti task assegnati
    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
    

    // L'utente ha molte richieste come client
    public function projectRequests()
    {
        return $this->hasMany(ProjectRequest::class, 'client_id');
    }

    // L'utente ha molte richieste assegnate come admin
    public function assignedRequests()
    {
        return $this->hasMany(ProjectRequest::class, 'assigned_admin_id');
    }
}
