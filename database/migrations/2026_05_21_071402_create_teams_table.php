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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // nome del team/azienda
            $table->string('slug')->unique();               // URL friendly es. "acme-corp"
            $table->foreignId('owner_id')->constrained('users'); // chi ha creato il team
            $table->string('plan')->default('free');        // piano: free o pro
            $table->timestamp('trial_ends_at')->nullable(); // scadenza trial
            $table->timestamps();                           // created_at, updated_at
            $table->softDeletes();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
