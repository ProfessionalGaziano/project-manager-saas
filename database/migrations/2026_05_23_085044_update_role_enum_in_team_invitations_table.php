<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
   public function up(): void
{
    // Prima aggiungi client all'enum mantenendo member
    DB::statement("ALTER TABLE team_invitations MODIFY COLUMN role ENUM('manager', 'member', 'client') DEFAULT 'client'");
    
    // Poi aggiorna i record esistenti
    DB::table('team_invitations')->where('role', 'member')->update(['role' => 'client']);
    
    // Infine rimuovi member dall'enum
    DB::statement("ALTER TABLE team_invitations MODIFY COLUMN role ENUM('manager', 'client') DEFAULT 'client'");
}
};