<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Questao extends Model
{
    protected $table = 'questoes';

    protected $fillable = [
        'chave', 'categoria_id', 'tipo', 'nivel', 'enunciado', 'opcoes',
        'correta', 'explicacao', 'resposta_modelo', 'respostas_aceitas',
    ];

    protected $casts = [
        'opcoes' => 'array',
        'respostas_aceitas' => 'array',
        'nivel' => 'integer',
        'correta' => 'integer',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /** Formato que o JavaScript do simulado já espera. */
    public function paraFront(int $ordemCategoria): array
    {
        $base = [
            'id' => $this->id,
            'cat' => $ordemCategoria,
            'lvl' => $this->nivel,
            'q' => $this->enunciado,
        ];

        return match ($this->tipo) {
            'mc' => $base + [
                'opts' => $this->opcoes,
                'correct' => $this->correta,
                'exp' => $this->explicacao,
            ],
            'cmd' => $base + [
                'model' => $this->resposta_modelo,
                'a' => $this->respostas_aceitas ?? [$this->resposta_modelo],
                'exp' => $this->explicacao,
            ],
            default => $base + ['model' => $this->resposta_modelo],
        };
    }
}
