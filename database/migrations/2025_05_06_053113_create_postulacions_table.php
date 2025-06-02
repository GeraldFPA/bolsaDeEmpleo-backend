<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('postulacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('oferta_id')->constrained()->onDelete('cascade');
            $table->string('estado')->default('pendiente'); // Estado de la postulacion (pendiente, aceptada, rechazada)
            $table->string('nombre');
            $table->string('email');
            $table->string('telefono');
            $table->text('comentario')->nullable();
            $table->string('cv_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulacions');
    }
};
