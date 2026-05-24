<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users');           // chi fa la richiesta
            $table->foreignId('assigned_admin_id')->nullable()->constrained('users'); // admin che la prende
            $table->string('title');                                         // default: "Richiesta di Nome Cognome"
            $table->text('description');                                     // descrizione del problema/progetto
            $table->date('desired_deadline');                                // data entro cui vuole la soluzione
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();                    // motivazione se rifiutata
            $table->foreignId('converted_to_project_id')->nullable()->constrained('projects'); // progetto creato
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_requests');
    }
};