<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Country extends Model
{
    protected $table = 'countries';

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

            get: fn(string $value) => mb_convert_case($value, MB_CASE_TITLE, "UTF-8"),
            set: fn(string $value) => mb_strtoupper($value, "UTF-8"),
        );
    }
}
