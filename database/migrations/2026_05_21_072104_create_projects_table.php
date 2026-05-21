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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams');  // team proprietario
            $table->string('name');                               // nome progetto
            $table->text('description')->nullable();             // descrizione opzionale
            $table->enum('status', ['draft', 'active', 'completed', 'archived'])->default('draft'); // stato
            $table->date('deadline')->nullable();                // scadenza opzionale
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
