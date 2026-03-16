<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Company extends Model
{
    protected $table = 'companies';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'estado'
    ];

    protected $casts = [
        'estado'   => 'boolean'
    ];



    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            // Al obtenerlo: "RECURSOS HUMANOS" -> "Recursos Humanos"
            get: fn(string $value) => mb_convert_case($value, MB_CASE_TITLE, "UTF-8"),

            // Al guardarlo: "recursos humanos" -> "RECURSOS HUMANOS"
            set: fn(string $value) => mb_strtoupper($value, "UTF-8"),
        );
    }
}
