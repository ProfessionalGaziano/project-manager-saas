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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects'); // progetto collegato
            $table->foreignId('assigned_to')->nullable()->constrained('users'); // utente assegnato
            $table->string('title');                                  // titolo task
            $table->text('description')->nullable();                 // descrizione opzionale
            $table->enum('status', ['todo', 'in_progress', 'review', 'done'])->default('todo'); // stato
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium'); // priorità
            $table->date('due_date')->nullable();                    // scadenza opzionale
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
