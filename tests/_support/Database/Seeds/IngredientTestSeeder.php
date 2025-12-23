<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class IngredientTestSeeder extends Seeder
{
    public function run(): void
    {
        $this->call('App\Database\Seeds\PermissionSeeder');
        $this->call('App\Database\Seeds\UserSeeder');
        $this->call('App\Database\Seeds\BrandSeeder');
        $this->call('App\Database\Seeds\CategIngSeeder');
        $this->call('App\Database\Seeds\UnitSeeder');
        $this->call('App\Database\Seeds\RecipeSeeder');
        $this->call('App\Database\Seeds\TagSeeder');

    }
}
