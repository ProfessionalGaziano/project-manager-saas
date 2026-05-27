<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use CrudTrait;
   use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
    'due_date' => 'date',
    ];

    // Il task appartiene a un progetto
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Il task è assegnato a un utente
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
