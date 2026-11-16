<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Simulado extends Model
{
    protected $table = 'simulados';

    protected $fillable = ['user_id', 'modo', 'dia'];

    protected $casts = ['dia' => 'date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(SimuladoItem::class);
    }
}
