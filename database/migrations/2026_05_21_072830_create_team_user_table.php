<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('team_user', function (Blueprint $table) {
           $table->id();
           $table->foreignId('team_id')->constrained('teams');  // team collegato
           $table->foreignId('user_id')->constrained('users');  // utente collegato
           $table->enum('role', ['owner', 'manager', 'member'])->default('member'); // ruolo nel team
           $table->timestamps();

           $table->unique(['team_id', 'user_id']); // un utente non può essere nel stesso team due volte
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_user');
    }
};
