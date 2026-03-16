<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Department extends Model
{
    protected $table = 'departments';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'estado'
    ];

    protected $casts = [
        'estado'   => 'boolean'
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'department_id');
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
