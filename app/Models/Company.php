<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $table = 'companies';
    public $timestamps = false;

    protected $fillable = ['name', 'estado'];

    protected $casts = [
        'estado' => 'boolean'
    ];

    public function branches(): HasMany
    {
        // Relación: Una empresa tiene muchas sucursales (vinculadas por company_id)
        return $this->hasMany(Branch::class, 'company_id');
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => $value ? mb_convert_case($value, MB_CASE_TITLE, "UTF-8") : '',
            set: fn(?string $value) => $value ? mb_strtoupper($value, "UTF-8") : '',
        );
    }
}
