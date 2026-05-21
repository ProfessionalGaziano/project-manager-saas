<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
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
