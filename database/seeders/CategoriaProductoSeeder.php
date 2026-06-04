<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Str;

class CategoriaProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $data = [
        'Electrónica' => [
            ['nombre' => 'Audífonos Bluetooth', 'precio' => 650],
            ['nombre' => 'Mouse Gamer', 'precio' => 800],
            ['nombre' => 'Teclado Mecánico', 'precio' => 1200],
        ],
        'Ropa' => [
            ['nombre' => 'Playera Básica', 'precio' => 150],
            ['nombre' => 'Sudadera', 'precio' => 400],
            ['nombre' => 'Pantalón Jeans', 'precio' => 500],
        ],
        'Hogar' => [
            ['nombre' => 'Licuadora', 'precio' => 900],
            ['nombre' => 'Tostador', 'precio' => 600],
            ['nombre' => 'Sartén Antiadherente', 'precio' => 350],
        ],
        'Deportes' => [
            ['nombre' => 'Balón de Fútbol', 'precio' => 300],
            ['nombre' => 'Tenis Running', 'precio' => 1500],
            ['nombre' => 'Raqueta de Tenis', 'precio' => 1200],
        ],
    ];

    foreach ($data as $categoriaNombre => $productos) {

        $cat = Categoria::firstOrCreate([
            'slug' => Str::slug($categoriaNombre)
        ], [
            'nombre' => $categoriaNombre
        ]);

        foreach ($productos as $prod) {
            Producto::create([
                'nombre' => $prod['nombre'],
                'descripcion' => 'Producto de ' . $categoriaNombre,
                'precio' => $prod['precio'],
                'categoria_id' => $cat->id
            ]);
        }
    }
}
}