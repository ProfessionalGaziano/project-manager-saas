<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'name',
        'description',
        'status',
        'deadline',
    ];

    // Il progetto appartiene a un team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Il progetto ha molti task
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Il progetto ha molte fatture
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

}
