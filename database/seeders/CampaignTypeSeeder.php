<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampaignTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    static $data = [
        ['Llamada Fijo'],
        ['Llamada Movil'],
        ['SMS'],
        ['Correo'],
        ['Whatsapp'],
    ];
    public function run(): void
    {
        foreach (self::$data as $key => $value) {
            DB::table('campaign_type')->insert([
                'Nombre' => $value[0],
            ]);
        }
    }
}
