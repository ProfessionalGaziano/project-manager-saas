<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Aggiunge employee a team_invitations
        DB::statement("ALTER TABLE team_invitations MODIFY COLUMN role ENUM('manager', 'client', 'employee') DEFAULT 'client'");
        
        // Aggiunge employee a team_user
        DB::statement("ALTER TABLE team_user MODIFY COLUMN role ENUM('owner', 'manager', 'client', 'employee') DEFAULT 'client'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE team_invitations MODIFY COLUMN role ENUM('manager', 'client') DEFAULT 'client'");
        DB::statement("ALTER TABLE team_user MODIFY COLUMN role ENUM('owner', 'manager', 'client') DEFAULT 'client'");
    }
};