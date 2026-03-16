<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BirthdayService;
use App\Mail\BirthdayMail;
use App\Mail\NoBirthdaysMail;
use App\Mail\ProcessErrorMail;
use App\Mail\DataQualityMail;
use Illuminate\Support\Facades\Mail;

class SendDailyBirthdays extends Command
{
    protected $signature = 'app:send-daily-birthdays';
    protected $description = 'Procesa y envía los correos de cumpleaños y frases diarias de OBGROUP';

    public function handle(BirthdayService $service)
    {
        // 1. Obtener datos procesados del servicio
        $data = $service->getProcessedBirthdays();

        // --- CASO A: ERROR DE INTEGRIDAD (Quórum < 550) ---
        // NOTA: Si en pruebas tienes menos de 550 empleados, ajusta el 'is_null($data)' 
        // o modifica tu BirthdayService para que el umbral sea menor durante el desarrollo.
        if (is_null($data)) {
            $recipients = ['whawowlolaxz@gmail.com'];

            Mail::to($recipients)->send(new ProcessErrorMail([
                'message' => 'ALERTA URGENTE: Actualización Incompleta de Base de Datos (Quórum insuficiente)',
                'timestamp' => now()->toDateTimeString()
            ]));

            $this->error('Fallo de quórum. Alerta técnica enviada a tu correo.');
            return;
        }

        // --- DESTINATARIO DE PRUEBAS ---
        $mainRecipient = 'whawowlolaxz@gmail.com';
        $bccList = []; // Mantenemos vacío para no enviar spam a producción

        // --- CASO B: HAY CUMPLEAÑEROS ---
        if ($data['birthdays']->isNotEmpty()) {
            Mail::to($mainRecipient)
                ->bcc($bccList)
                ->send(new BirthdayMail($data));

            $this->info('Correos de felicitación enviados exitosamente al correo de pruebas.');
        }
        // --- CASO C: DÍA SIN CUMPLEAÑOS ---
        else {
            Mail::to($mainRecipient)
                ->bcc($bccList)
                ->send(new NoBirthdaysMail($data));

            $this->info('Correo de día sin cumpleaños enviado al correo de pruebas.');
        }

        // --- PASO 4: REPORTE DE AUDITORÍA ---
        $this->processAuditReport($service);
    }

    private function processAuditReport(BirthdayService $service)
    {
        $auditRecords = $service->getAuditRecords();

        if ($auditRecords->isNotEmpty()) {
            $auditRecipients = ['whawowlolaxz@gmail.com'];

            Mail::to($auditRecipients)
                ->send(new DataQualityMail($auditRecords));

            $this->warn('Reporte de calidad de datos enviado a auditoría (pruebas).');
        }
    }
}
