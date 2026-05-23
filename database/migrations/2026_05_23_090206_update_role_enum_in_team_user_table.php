<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Prima aggiungi client all'enum mantenendo i valori esistenti
        DB::statement("ALTER TABLE team_user MODIFY COLUMN role ENUM('owner', 'manager', 'member', 'client') DEFAULT 'client'");
        
        // Aggiorna i record esistenti con member a client
        DB::table('team_user')->where('role', 'member')->update(['role' => 'client']);
        
        // Rimuovi member dall'enum
        DB::statement("ALTER TABLE team_user MODIFY COLUMN role ENUM('owner', 'manager', 'client') DEFAULT 'client'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE team_user MODIFY COLUMN role ENUM('owner', 'manager', 'member') DEFAULT 'member'");
    }
};