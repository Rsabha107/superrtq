<?php

namespace Database\Seeders;

use App\Models\Stadium;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StadiumSeeder extends Seeder
{
    public function run(): void
    {
        $stadiums = [
            [
                'name' => 'Lusail Stadium',
                'city' => 'Lusail',
                'capacity' => 88966,
                'description' => "Qatar's largest stadium, host to the biggest fixtures on the RTQ+ calendar. A short walk from Lusail Boulevard's fan zones and dining.",
            ],
            [
                'name' => 'Al Bayt Stadium',
                'city' => 'Al Khor',
                'capacity' => 68895,
                'description' => 'A tent-inspired design reflecting the traditional dwellings of the region\'s nomadic peoples, set in Al Khor to the north of Doha.',
            ],
            [
                'name' => 'Education City Stadium',
                'city' => 'Al Rayyan',
                'capacity' => 44667,
                'description' => "Set within a cluster of universities in Al Rayyan, known for its striking geometric diamond facade that shifts with the daylight.",
            ],
            [
                'name' => 'Khalifa International Stadium',
                'city' => 'Al Rayyan',
                'capacity' => 40000,
                'description' => "Qatar's oldest major stadium, extensively renovated and shaded by its distinctive twin arches.",
            ],
        ];

        foreach ($stadiums as $stadium) {
            Stadium::updateOrCreate(
                ['slug' => Str::slug($stadium['name'])],
                $stadium + ['slug' => Str::slug($stadium['name']), 'status' => 'active']
            );
        }
    }
}
