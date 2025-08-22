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
        Schema::create('requests', function (Blueprint $table) {
        $table->id();
        // TODO: user_id RESTRICT - impedisce cancellazione utenti con richieste attive
        $table->foreignId('user_id')->constrained()->onDelete('restrict');
        $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
        // TODO: request_type_id CASCADE - assumiamo che i tipi di richiesta non vengano mai eliminati
        // In futuro considerare aggiungere campo 'active' per disabilitare invece di eliminare
        $table->foreignId('request_type_id')->constrained()->onDelete('cascade');
        // TODO: status_id CASCADE - assumiamo che gli status non vengano mai eliminati  
        // In futuro considerare aggiungere campo 'active' per disabilitare invece di eliminare
        $table->foreignId('status_id')->constrained('request_statuses')->onDelete('cascade');
        $table->text('notes')->nullable();
        $table->timestamp('requested_at');
        $table->timestamp('approved_at')->nullable();
        $table->softDeletes(); //archiviazione logica anziché eliminazione fisica
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
