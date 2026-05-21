<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'project_id',
        'number',
        'amount',
        'status',
        'issued_at',
        'due_at',
        'notes',
    ];

    // La fattura appartiene a un team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // La fattura appartiene a un progetto
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
