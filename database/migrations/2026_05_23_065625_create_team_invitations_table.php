<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->string('email');                    // email dell'invitato
            $table->string('token')->unique();          // token univoco per l'invito
            $table->enum('role', ['manager', 'member'])->default('member'); // ruolo assegnato
            $table->timestamp('accepted_at')->nullable(); // quando ha accettato
            $table->timestamp('expires_at');            // scadenza invito
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_invitations');
    }
};