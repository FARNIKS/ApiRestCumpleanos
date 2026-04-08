<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $table = 'branches';
    protected $primaryKey = 'code'; // Definimos 'code' como PK
    public $incrementing = false;   // No es un ID auto-incremental
    protected $keyType = 'string';  // Es de tipo string
    public $timestamps = false;

    protected $fillable = [
        'code',
        'company_name', // El valor "ORBE", "SISCON" de tu imagen
        'country_id',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'Empresa', 'code');
    }
}
