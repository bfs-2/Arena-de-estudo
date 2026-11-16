<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Questao;
use Illuminate\Database\Seeder;

class RedlabSeeder extends Seeder
{
    /**
     * Importa categorias e questões de database/seeders/data/questoes.json.
     * Pode rodar várias vezes: atualiza o que já existe (pela "chave") e cria o que falta.
     */
    public function run(): void
    {
        $dados = json_decode(file_get_contents(database_path('seeders/data/questoes.json')), true, 512, JSON_THROW_ON_ERROR);

        $ids = [];
        foreach ($dados['categorias'] as $c) {
            $ids[$c['ordem']] = Categoria::updateOrCreate(
                ['ordem' => $c['ordem']],
                ['nome' => $c['nome'], 'descricao' => $c['descricao'], 'icone' => $c['icone']],
            )->id;
        }

        foreach ($dados['questoes'] as $q) {
            Questao::updateOrCreate(
                ['chave' => $q['chave']],
                [
                    'categoria_id' => $ids[$q['categoria']],
                    'tipo' => $q['tipo'],
                    'nivel' => $q['nivel'],
                    'enunciado' => $q['enunciado'],
                    'opcoes' => $q['opcoes'] ?? null,
                    'correta' => $q['correta'] ?? null,
                    'explicacao' => $q['explicacao'] ?? null,
                    'resposta_modelo' => $q['resposta_modelo'] ?? null,
                    'respostas_aceitas' => $q['respostas_aceitas'] ?? null,
                ],
            );
        }
    }
}
