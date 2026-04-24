<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BirthdayConfig;
use App\Models\NoBirthdayConfig;

class MailConfigsSeeder extends Seeder
{
    public function run(): void
    {
        // Datos iniciales para Cumpleaños
        BirthdayConfig::create([
            'banner_url' => 'https://www.elorbe.la/images/cumpleanos.jpg',
            'intro_text' => "¡Feliz Cumpleaños de parte de OBGROUP!\nHoy celebramos a nuestros valiosos compañeros.",
            'main_body' => "Les deseamos un día lleno de alegría. ¡Que lo disfruten!",
            'closing_text' => "¡Detente un momento para leer esto...!",
            'sign_off' => "Departamento de Talento Humano"
        ]);

        // Datos iniciales para Días sin Cumpleaños
        NoBirthdayConfig::create([
            'intro_text' => "¡Buen día, equipo de OBGROUP!\n\nHoy no registramos compañeros de cumpleaños en nuestras sucursales, pero aprovechamos este espacio para compartir un mensaje de valor con todos ustedes.",
            'main_body' => "Inspiración para hoy",
            'closing_text' => "¡Les deseamos una jornada llena de productividad y éxito!",
            'sign_off' => "Departamento de Talento Humano"
        ]);
    }
}
