<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'assigned_admin_id',
        'title',
        'description',
        'desired_deadline',
        'status',
        'budget',                  // aggiunto
        'rejection_reason',
        'converted_to_project_id',
    ];

    protected $casts = [
        'desired_deadline' => 'date',
    ];

    // La richiesta appartiene a un client
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // La richiesta è assegnata a un admin
    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }

    // La richiesta è stata convertita in un progetto
    public function convertedProject()
    {
        return $this->belongsTo(Project::class, 'converted_to_project_id');
    }

    // Controlla se la richiesta è ancora in attesa
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    // Controlla quante richieste ha fatto questo client in passato
    public function clientRequestsCount(): int
    {
        return self::where('client_id', $this->client_id)->count();
    }

    // Badge fedeltà cliente
    public function clientLoyaltyBadge(): string
    {
        $count = $this->clientRequestsCount();

        if ($count === 1) return '🆕 Nuovo cliente';
        if ($count <= 3) return '⭐ Cliente attivo';
        if ($count <= 10) return '⭐⭐ Cliente fidelizzato';
        return '⭐⭐⭐ Cliente VIP';
    }
}