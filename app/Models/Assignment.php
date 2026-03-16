<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Assignment extends Model
{
    protected $table = 'assignments';

    public $timestamps = false;

    protected $fillable = [
        'position_id',
        'department_id',
        'estado'
    ];
    protected $casts = [
        'estado'   => 'boolean'
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
