<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'plan',
        'trial_ends_at',
    ];

    // Il team appartiene a un utente (owner)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Il team ha molti utenti
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    // Il team ha molti progetti
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // Il team ha molte fatture
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
