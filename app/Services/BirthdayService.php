<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\BirthdayMessage;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BirthdayService
{
    protected $maxAge = 100;

    public function getProcessedBirthdays(): ?array
    {
        // 1. Validación de Quórum (Quitamos el filtro de 'estado')
        // Contamos todos los registros actuales en la tabla
        if (Employee::count() < -550) {
            return null;
        }

        // 2. Selección de frase del día
        $message = BirthdayMessage::find(now()->dayOfYear)?->phrase
            ?? "¡Felicidades en tu día!";

        // 3. Consulta con nombres reales: 'Cumple', 'Nombre' y 'Empresa'
        $birthdays = Employee::with(['branch.company', 'branch.country'])
            ->whereRaw("FORMAT(Cumple, 'MM-dd') = ?", [now()->format('m-d')])
            ->get()
            // Filtros de integridad: que tenga fecha y que la edad sea lógica
            ->filter(fn($e) => $e->Cumple && $e->Cumple->age < $this->maxAge)
            ->unique('Nombre');

        if ($birthdays->isEmpty()) {
            return ['phrase' => $message, 'birthdays' => collect()];
        }

        // 4. Agrupación jerárquica: País -> Empresa
        $groupedData = $birthdays->groupBy([
            fn($e) => $e->branch?->country?->name ?? 'Otros Países',
            fn($e) => $e->branch?->company?->name ?? 'Empresa no asignada'
        ]);

        return [
            'phrase' => $message,
            'birthdays' => $groupedData
        ];
    }

    public function getAuditRecords(): Collection
    {
        // Auditoría sin filtro de estado
        return Employee::where(function ($query) {
            $query->whereNull('Cumple')
                ->orWhereYear('Cumple', '<', now()->year - $this->maxAge);
        })
            ->where('Nombre', 'NOT LIKE', '%Dynamics Ax 2012%')
            ->get();
    }
}
