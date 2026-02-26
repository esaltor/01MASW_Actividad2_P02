<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CalendarioSeeder extends Seeder
{
    public function run(): void
    {
        // Rango a poblar
        $from = Carbon::create(2025, 9, 1);
        $to   = Carbon::create(2026, 6, 30);

        // Periodo lectivo (Aragón) para Infantil/Primaria/ESO y similares:
        $courseStart = Carbon::create(2025, 9, 8);
        $courseEnd   = Carbon::create(2026, 6, 19);

        // Vacaciones oficiales (Aragón)
        // Navidad: desde fin de la mañana del 19/12 -> a nivel "día", marcamos no lectivo desde 20/12
        $navidadStart = Carbon::create(2025, 12, 20);
        $navidadEnd   = Carbon::create(2026, 1, 6);

        // Semana Santa
        $ssStart = Carbon::create(2026, 3, 30);
        $ssEnd   = Carbon::create(2026, 4, 6);

        // Festivos nacionales/autonómicos relevantes en el rango (Aragón)
        $festivosAragon = [
            '2025-10-13', // Fiesta Nacional (12/10 cae en domingo)
            '2025-12-08', // Inmaculada
            '2026-04-23', // Día de Aragón
            '2026-05-01', // Día del Trabajo
        ];

        // No lectivos de ámbito provincial (Zaragoza) según calendario escolar Aragón
        $noLectivosProvZaragoza = [
            '2025-10-10',
            '2025-11-03',
            '2026-03-06',
            '2026-04-24',
        ];

        // Festivos locales del municipio de Zaragoza (dentro del rango pedido)
        $festivosLocalesZaragoza = [
            '2026-01-29', // San Valero
            '2026-03-05', // Cincomarzada
        ];

        // Set rápido para comprobar fechas especiales
        $noLectivosExtra = array_flip(array_merge(
            $festivosAragon,
            $noLectivosProvZaragoza,
            $festivosLocalesZaragoza
        ));

        $now = now();
        $rows = [];

        foreach (CarbonPeriod::create($from, $to) as $day) {
            /** @var Carbon $day */
            $dateStr = $day->toDateString();

            // Base: lectivo
            $lectivo = true;

            // 1) Sábados y domingos
            if ($day->isWeekend()) {
                $lectivo = false;
            }

            // 2) Fuera del periodo lectivo del curso
            if ($day->lt($courseStart) || $day->gt($courseEnd)) {
                $lectivo = false;
            }

            // 3) Festivos / no lectivos oficiales (nacional/autonómico/provincial/local)
            if (isset($noLectivosExtra[$dateStr])) {
                $lectivo = false;
            }

            // 4) Vacaciones de Navidad
            if ($day->betweenIncluded($navidadStart, $navidadEnd)) {
                $lectivo = false;
            }

            // 5) Vacaciones de Semana Santa
            if ($day->betweenIncluded($ssStart, $ssEnd)) {
                $lectivo = false;
            }

            $rows[] = [
                'fecha' => $dateStr,
                'lectivo' => $lectivo,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Upsert para poder ejecutar el seeder varias veces sin error por PK (fecha)
        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('CALENDARIO')->upsert(
                $chunk,
                ['fecha'],
                ['lectivo', 'updated_at']
            );
        }
    }
}