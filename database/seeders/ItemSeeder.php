<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Laptop Asus',
                'category_id' => 1,
                'total' => 10,
            ],
            [
                'name' => 'Proyektor Epson',
                'category_id' => 2,
                'total' => 5,
            ],
            [
                'name' => 'Meja Praktik',
                'category_id' => 3,
                'total' => 15,
            ],
            [
                'name' => 'Kursi Kantor',
                'category_id' => 2,
                'total' => 20,
            ],
            [
                'name' => 'Alat Kebersihan Set',
                'category_id' => 4,
                'total' => 8,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
