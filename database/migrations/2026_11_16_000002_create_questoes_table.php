<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questoes', function (Blueprint $table) {
            $table->id();
            $table->char('chave', 40)->unique(); // sha1(tipo|enunciado): o seeder pode rodar mais de uma vez
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->string('tipo', 3);                       // mc | op | cmd
            $table->unsignedTinyInteger('nivel')->default(2); // 1 básico, 2 intermediário, 3 avançado
            $table->text('enunciado');
            $table->json('opcoes')->nullable();              // mc: lista de alternativas
            $table->unsignedTinyInteger('correta')->nullable(); // mc: índice da alternativa certa
            $table->text('explicacao')->nullable();
            $table->text('resposta_modelo')->nullable();     // op e cmd
            $table->json('respostas_aceitas')->nullable();   // cmd: formas equivalentes aceitas
            $table->timestamps();

            $table->index(['tipo', 'categoria_id', 'nivel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questoes');
    }
};
