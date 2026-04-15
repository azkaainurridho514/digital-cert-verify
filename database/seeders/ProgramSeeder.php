<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            ['name' => 'English for Overthinking',        'code' => 'EOT'],
            ['name' => 'Broken English Survival',         'code' => 'BES'],
            ['name' => 'Grammar is Optional',             'code' => 'GIO'],
            ['name' => 'Speak English Like a Bossy Boss', 'code' => 'SEBB'],
            ['name' => 'English for Ngoding People',      'code' => 'ENP'],
            ['name' => 'No Grammar Just Vibes',           'code' => 'NGJV'],
            ['name' => 'English Level: “Yang Penting Ngerti”', 'code' => 'YPN'],
            ['name' => 'Confident but Wrong English',     'code' => 'CBWE'],
            ['name' => 'English for Wibu International',  'code' => 'EWI'],
            ['name' => 'Fluent in Google Translate',      'code' => 'FGT'],
        ];

        foreach ($programs as $program) {
            DB::table('programs')->insert([
                'name' => $program['name'],
                'code' => $program['code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}