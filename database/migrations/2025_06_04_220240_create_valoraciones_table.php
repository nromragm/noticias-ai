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
        Schema::create('valoraciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('noticia_id')->constrained('noticias')->onDelete('cascade');
            $table->unsignedTinyInteger('valor')->comment('Valoración de 1 a 5');
            $table->timestamps();

            $table->unique(['user_id', 'noticia_id']); // Un usuario solo puede valorar una vez cada noticia
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valoraciones');
    }
};
