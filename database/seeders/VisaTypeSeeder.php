<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VisaType;

class VisaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'E-visa',
            'Sticker Visa',
            'Visa on Arrival',
            'Visa Free'
        ];

        foreach ($data as $type) {
            VisaType::create([
                'name' => $type
            ]);
        }
    }
}