<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Recipe extends BaseController
{
    public function index()
    {

        $recipes = Model('RecipeModel')->getAllRecipes();
        return $this->view('front/recipe/index', ['recipes'=>$recipes], false);
    }
    public function show($slug) {
        $rm = Model('RecipeModel');
        $recipe = $rm->getFullRecipe(null, $slug);
        if($recipe) {
            $this->title = "recipe : " . $recipe['name'];
            return $this->view('front/recipe/show', ['recipe' => $recipe], false);
        }
        return $this->view('templates/404.php', [], false);
    }
}
