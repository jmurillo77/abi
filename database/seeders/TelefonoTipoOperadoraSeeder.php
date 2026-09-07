<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TelefonoTipoOperadoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    static $data = [
        ['NA'],
        ['Claro'],
        ['Movistar'],
        ['CNT'],
        ['Tuenti'],
    ];
    public function run(): void
    {
        $table = DB::connection('matriz')->table('telefono_tipo_operadoras');

        foreach (self::$data as $value) {
            $nombre = trim((string) $value[0]);

            $table->updateOrInsert(
                ['Nombre' => $nombre],
                ['Nombre' => $nombre]
            );
        }

        $duplicates = $table
            ->select('IdOperadora', 'Nombre')
            ->whereNotNull('Nombre')
            ->orderBy('IdOperadora')
            ->get()
            ->groupBy(fn ($row) => strtolower(trim((string) $row->Nombre)));

        foreach ($duplicates as $nombre => $rows) {
            if (count($rows) <= 1) {
                continue;
            }

            $idsToDelete = $rows->pluck('IdOperadora')->skip(1)->values()->all();

            if (! empty($idsToDelete)) {
                $table->whereIn('IdOperadora', $idsToDelete)->delete();
            }
        }
    }
}
