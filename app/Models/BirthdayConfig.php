<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BirthdayConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'banner_url',
        'intro_text',
        'main_body',
        'closing_text',
        'sign_off'
    ];
}
