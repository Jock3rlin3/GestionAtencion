<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultaAtencion extends Model
{
    public const CREATED_AT = 'creado_en';

    public const UPDATED_AT = 'actualizado_en';

    protected $table = 'consultas_atencion';

    protected $fillable = [
        'tratamiento_id',
        'paciente',
        'fecha_atencion',
        'motivo',
        'observaciones',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_atencion' => 'datetime',
        ];
    }

    public function tratamiento(): BelongsTo
    {
        return $this->belongsTo(Tratamiento::class, 'tratamiento_id');
    }
}
