<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Questao;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestaoController extends Controller
{
    private const TIPOS = ['mc', 'op', 'cmd'];

    /**
     * GET /api/questoes/contagem
     * Categorias e quantidade de questões por tipo, categoria e nível.
     * É o que monta a tela de escolha (cards de categoria, níveis e totais).
     */
    public function contagem(): JsonResponse
    {
        $categorias = Categoria::orderBy('ordem')->get();

        $contagem = [];
        foreach (self::TIPOS as $tipo) {
            foreach ($categorias as $c) {
                $contagem[$tipo][$c->ordem] = ['all' => 0, '1' => 0, '2' => 0, '3' => 0];
            }
        }

        $linhas = Questao::query()
            ->join('categorias', 'categorias.id', '=', 'questoes.categoria_id')
            ->selectRaw('questoes.tipo as tipo, categorias.ordem as ordem, questoes.nivel as nivel, count(*) as n')
            ->groupBy('questoes.tipo', 'categorias.ordem', 'questoes.nivel')
            ->get();

        foreach ($linhas as $l) {
            $contagem[$l->tipo][$l->ordem][(string) $l->nivel] += $l->n;
            $contagem[$l->tipo][$l->ordem]['all'] += $l->n;
        }

        return response()->json([
            'categorias' => $categorias->map(fn ($c) => [
                'ordem' => $c->ordem,
                'nome' => $c->nome,
                'descricao' => $c->descricao,
                'icone' => $c->icone,
            ])->values(),
            'contagem' => $contagem,
            'total' => Questao::count(),
        ]);
    }

    /**
     * GET /api/questoes?modo=mc&cats=0,1&nivel=2&qtd=10
     * Sorteia as questões do simulado. qtd pode ser um número ou "Todas".
     */
    public function index(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'modo' => ['required', 'in:mc,op,cmd'],
            'cats' => ['required', 'regex:/^\d+(,\d+)*$/'],
            'nivel' => ['nullable', 'in:all,1,2,3'],
            'qtd' => ['nullable', 'string', 'max:10'],
        ]);

        $ordens = array_map('intval', explode(',', $dados['cats']));

        $consulta = Questao::query()
            ->with('categoria:id,ordem')
            ->where('tipo', $dados['modo'])
            ->whereHas('categoria', fn ($c) => $c->whereIn('ordem', $ordens));

        if (in_array($dados['nivel'] ?? 'all', ['1', '2', '3'], true)) {
            $consulta->where('nivel', (int) $dados['nivel']);
        }

        $consulta->inRandomOrder();

        $qtd = $dados['qtd'] ?? '';
        if (ctype_digit($qtd) && (int) $qtd > 0) {
            $consulta->limit(min((int) $qtd, 500));
        }

        return response()->json(
            $consulta->get()->map(fn (Questao $q) => $q->paraFront($q->categoria->ordem))->values()
        );
    }
}
