<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Employee extends Model
{
    protected $table = 'employees';
    public $timestamps = false;

    protected $fillable = ['name', 'birthday', 'branch_id', 'assignment_id', 'estado'];

    protected $casts = [
        'estado'   => 'boolean',
        'birthday' => 'date',
    ];

    /* --- RELACIONES --- */

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
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
    protected static function booted()
    {
        static::created(fn($employee) => $employee->branch()->increment('total_staff'));

        // Usamos el evento 'updated' para detectar cuando el estado pasa a ser falso (desactivado)
        static::updated(function ($employee) {
            if ($employee->wasChanged('estado')) {
                if ($employee->estado) {
                    $employee->branch()->increment('total_staff');
                } else {
                    $employee->branch()->decrement('total_staff');
                }
            }
        });
    }
}
