<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            // posição da categoria na tela (0, 1, 2...); é o "cat" usado pelo front
            $table->unsignedTinyInteger('ordem')->unique();
            $table->string('nome');
            $table->string('descricao');
            $table->text('icone'); // atributo "d" do path SVG
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
