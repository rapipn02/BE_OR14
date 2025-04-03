<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            [
                'code' => 'programming',
                'name' => 'Programming',
                'description' => 'Divisi untuk pengembangan aplikasi dan software'
            ],
            [
                'code' => 'multimedia',
                'name' => 'Multimedia dan Desain',
                'description' => 'Divisi untuk desain grafis dan media'
            ],
            [
                'code' => 'skj',
                'name' => 'Sistem Komputer dan Jaringan',
                'description' => 'Divisi untuk jaringan komputer dan sistem'
            ],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}
