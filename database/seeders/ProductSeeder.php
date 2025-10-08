<?php

namespace Database\Seeders;

use App\Models\ProductKantin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductKantin::create([
            'nama' => 'Nasi Cokot',
            'harga' => '5000',
            'stok' => '20',
            'kategori' => 'makanan'
        ]);
        ProductKantin::create([
            'nama' => 'Dimsum Mentai',
            'harga' => '9000',
            'stok' => '30',
            'kategori' => 'makanan'
        ]);
        ProductKantin::create([
            'nama' => 'Pop ice',
            'harga' => '5000',
            'stok' => '75',
            'kategori' => 'minuman'
        ]);
        ProductKantin::create([
            'nama' => 'Es teh',
            'harga' => '3000',
            'stok' => '25',
            'kategori' => 'minuman'
        ]);
    }
}
