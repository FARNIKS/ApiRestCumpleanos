<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Employee extends Model
{
    /*
    protected $connection = 'sqlsrvax';

    protected $table = 'AX_Usuarios_Cumple';*/
    protected $table = 'employees';
    public $timestamps = false;

    protected $fillable = ['Nombre', 'Cumple', 'Empresa'];

    protected $casts = [
        'Cumple' => 'date',
    ];

    /* --- RELACIONES --- */

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'Empresa', 'code');
    }


    protected function Nombre(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => $value ? mb_convert_case($value, MB_CASE_TITLE, "UTF-8") : '',
            set: fn(?string $value) => $value ? mb_strtoupper($value, "UTF-8") : '',
        );
    }
}
