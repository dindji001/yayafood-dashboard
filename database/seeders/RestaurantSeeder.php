<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $resto = Restaurant::create([
            'name' => "L'Assiette d'Or",
            'address' => 'Cocody, Abidjan',
            'phone' => '+225 07070707',
            'is_active' => true,
        ]);

        $cat1 = Category::create([
            'restaurant_id' => $resto->id,
            'name' => 'Entrées',
            'sort_order' => 1,
        ]);

        Dish::create([
            'category_id' => $cat1->id,
            'name' => 'Alloco',
            'description' => 'Bananes plantains frites',
            'price' => 1500,
            'is_available' => true,
        ]);

        $cat2 = Category::create([
            'restaurant_id' => $resto->id,
            'name' => 'Plats de Résistance',
            'sort_order' => 2,
        ]);

        Dish::create([
            'category_id' => $cat2->id,
            'name' => 'Garba Spécial',
            'description' => 'Attiéké avec poisson thon frit',
            'price' => 2500,
            'is_available' => true,
        ]);

        Dish::create([
            'category_id' => $cat2->id,
            'name' => 'Poulet Braisé',
            'description' => 'Poulet assaisonné et cuit au charbon',
            'price' => 5000,
            'is_available' => true,
        ]);
    }
}
