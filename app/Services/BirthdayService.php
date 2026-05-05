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
        if (Employee::count() < 550) {
            return null;
        }

        $message = BirthdayMessage::find(now()->dayOfYear)?->phrase
            ?? "¡Felicidades en tu día!";

        $birthdays = Employee::with(['branch.country'])
            ->whereRaw("FORMAT(Cumple, 'MM-dd') = ?", [now()->format('m-d')])
            ->get()
            ->filter(fn($e) => $e->Cumple && $e->Cumple->age < $this->maxAge)
            ->unique('Nombre');

        if ($birthdays->isEmpty()) {
            return [
                'phrase' => $message,
                'birthdays' => collect()
            ];
        }


        $groupedData = $birthdays->groupBy([
            fn($e) => $e->branch?->country?->name ?? 'Otros Países',
            fn($e) => $e->branch?->company_name ?? 'Empresa no asignada'
        ]);

        return [
            'phrase' => $message,
            'birthdays' => $groupedData
        ];
    }

    public function getAuditRecords(): Collection
    {
        return Employee::where(function ($query) {
            $query->whereNull('Cumple')
                ->orWhereYear('Cumple', '<', now()->year - $this->maxAge);
        })
            ->where('Nombre', 'NOT LIKE', '%Dynamics Ax 2012%')
            ->get();
    }
}
