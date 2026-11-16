<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('modo', 3);   // mc | op | cmd
            $table->date('dia');         // dia local do usuário (as estatísticas agrupam por dia)
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        Schema::create('simulado_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('simulado_id')->constrained('simulados')->cascadeOnDelete();
            $table->foreignId('questao_id')->nullable()->constrained('questoes')->nullOnDelete();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            // cópia do enunciado/resposta: o histórico continua legível se a questão for editada ou apagada
            $table->text('enunciado');
            $table->text('resposta')->nullable();
            $table->unsignedTinyInteger('pct');   // 0 a 100
            $table->boolean('acertou');
            $table->string('formato', 2);         // mc | op (comandos são gravados como op, igual ao app atual)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulado_itens');
        Schema::dropIfExists('simulados');
    }
};
