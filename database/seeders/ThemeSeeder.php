<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('themes')->insert([
            [
                'primary' => '#FE6E87',
                'secondary' => '#FFBBC7',
                'tertiary' => '#FFD0D8',
                'light_text' => '#E0E0E0',
                'primary_text' => '#1B263B',
                'secondary_text' => '#D2D2D2',
                'primary_background' => '#FFFFFF',
                'secondary_background' => '#FFFFFF',
            ],
            [
                'primary' => '#54E0F2',
                'secondary' => '#8BF2FF',
                'tertiary' => '#C1F8FF',
                'light_text' => '#E0E0E0',
                'primary_text' => '#1B263B',
                'secondary_text' => '#D2D2D2',
                'primary_background' => '#FFFFFF',
                'secondary_background' => '#FFFFFF',
            ],
            [
                'primary' => '#4FDA4A',
                'secondary' => '#85EC81',
                'tertiary' => '#BBFFB8',
                'light_text' => '#E0E0E0',
                'primary_text' => '#1B263B',
                'secondary_text' => '#D2D2D2',
                'primary_background' => '#FFFFFF',
                'secondary_background' => '#FFFFFF',
            ],
        ]);
    }
}
