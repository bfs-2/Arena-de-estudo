<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SimuladoItem extends Model
{
    public $timestamps = false;

    protected $table = 'simulado_itens';

    protected $fillable = [
        'simulado_id', 'questao_id', 'categoria_id', 'enunciado',
        'resposta', 'pct', 'acertou', 'formato',
    ];

    protected $casts = ['acertou' => 'boolean', 'pct' => 'integer'];

    public function simulado(): BelongsTo
    {
        return $this->belongsTo(Simulado::class);
    }
}
