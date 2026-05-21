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
        Schema::create('invoices', function (Blueprint $table) {
           $table->id();
            $table->foreignId('team_id')->constrained('teams');      // team proprietario
            $table->foreignId('project_id')->nullable()->constrained('projects'); // progetto collegato
            $table->string('number')->unique();                      // numero fattura es. "INV-0001"
            $table->decimal('amount', 10, 2);                       // importo es. 1500.00
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue'])->default('draft'); // stato
            $table->date('issued_at');                              // data emissione
            $table->date('due_at');                                 // data scadenza pagamento
            $table->text('notes')->nullable();                      // note opzionali
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
