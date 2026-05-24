<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use CrudTrait;
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'manager_id',    
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

    // Il progetto è assegnato a un manager
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

}
