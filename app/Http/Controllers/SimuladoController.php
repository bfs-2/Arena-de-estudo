<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Questao;
use App\Models\Simulado;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SimuladoController extends Controller
{
    private const LIMITE_HISTORICO = 200;

    /**
     * GET /api/simulados
     * Histórico do usuário logado, no mesmo formato que o app guardava no localStorage:
     * [{ t, d, mode, items: [{ cat, q, pct, ok, a, m }] }]
     */
    public function index(Request $request): JsonResponse
    {
        $ordens = Categoria::pluck('ordem', 'id');

        $simulados = Simulado::with('itens')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->limit(self::LIMITE_HISTORICO)
            ->get()
            ->reverse()
            ->values();

        return response()->json($simulados->map(fn (Simulado $s) => [
            't' => $s->created_at->getTimestamp() * 1000,
            'd' => $s->dia->format('Y-m-d'),
            'mode' => $s->modo,
            'items' => $s->itens->map(fn ($i) => [
                'cat' => $ordens[$i->categoria_id] ?? -1,
                'q' => $i->enunciado,
                'pct' => $i->pct,
                'ok' => $i->acertou,
                'a' => $i->resposta ?? '',
                'm' => $i->formato,
            ])->values(),
        ]));
    }

    /** POST /api/simulados: grava o resultado de um simulado finalizado. */
    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'modo' => ['required', 'in:mc,op,cmd'],
            'dia' => ['required', 'date_format:Y-m-d'],
            'itens' => ['required', 'array', 'min:1', 'max:300'],
            'itens.*.id' => ['nullable', 'integer'],
            'itens.*.cat' => ['nullable', 'integer', 'min:0', 'max:100'],
            'itens.*.q' => ['required', 'string', 'max:2000'],
            'itens.*.a' => ['nullable', 'string', 'max:5000'],
            'itens.*.pct' => ['required', 'integer', 'between:0,100'],
            'itens.*.ok' => ['required', 'boolean'],
            'itens.*.m' => ['required', 'in:mc,op'],
        ]);

        $userId = $request->user()->id;
        $categorias = Categoria::pluck('id', 'ordem');
        $questoesValidas = Questao::whereIn('id', collect($dados['itens'])->pluck('id')->filter())->pluck('id')->flip();

        $simulado = DB::transaction(function () use ($dados, $userId, $categorias, $questoesValidas) {
            $simulado = Simulado::create([
                'user_id' => $userId,
                'modo' => $dados['modo'],
                'dia' => $dados['dia'],
            ]);

            $simulado->itens()->createMany(array_map(fn ($i) => [
                'questao_id' => isset($i['id'], $questoesValidas[$i['id']]) ? $i['id'] : null,
                'categoria_id' => $categorias[$i['cat'] ?? -1] ?? null,
                'enunciado' => $i['q'],
                'resposta' => $i['a'] ?? null,
                'pct' => $i['pct'],
                'acertou' => $i['ok'],
                'formato' => $i['m'],
            ], $dados['itens']));

            // mantém só os últimos simulados do usuário
            $antigos = Simulado::where('user_id', $userId)
                ->orderByDesc('id')->offset(self::LIMITE_HISTORICO)->limit(1000)->pluck('id');
            if ($antigos->isNotEmpty()) {
                Simulado::whereIn('id', $antigos)->delete();
            }

            return $simulado;
        });

        return response()->json(['id' => $simulado->id], 201);
    }

    /** DELETE /api/simulados: "Limpar histórico de estatísticas". */
    public function destroy(Request $request): Response
    {
        Simulado::where('user_id', $request->user()->id)->delete();

        return response()->noContent();
    }
}
