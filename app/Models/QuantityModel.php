<?php

namespace App\Models;

use CodeIgniter\Model;

class QuantityModel extends Model
{
    protected $table            = 'quantity';
    protected $primaryKey       = 'id_recipe';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_ingredient','id_recipe', 'id_unit', 'quantity'];

    protected $validationRules = [
        'id_ingredient' => 'required|integer',
        'id_recipe'     => 'required|integer',
        'id_unit'       => 'required|integer',
        'quantity'      => 'required|decimal',
    ];

    protected $validationMessages = [
        'id_ingredient' => [
            'required' => 'L’ingrédient est obligatoire.',
            'integer'  => 'L’ID de l’ingrédient doit être un nombre.',
        ],
        'id_recipe' => [
            'required' => 'La recette est obligatoire.',
            'integer'  => 'L’ID de la recette doit être un nombre.',
        ],
        'id_unit' => [
            'required' => 'L’unité est obligatoire.',
            'integer'  => 'L’ID de l’unité doit être un nombre.',
        ],
        'quantity' => [
            'required' => 'La quantité est obligatoire.',
            'decimal'  => 'La quantité doit être un nombre valide.',
        ],
    ];

    protected $beforeDelete   = [];

    public function getQuantityByRecipe($id_recipe) {
        $this->select('quantity.*, ingredient.name as ingredient, unit.name as unit' );
        $this->join('ingredient', 'ingredient.id = quantity.id_ingredient', 'left');
        $this->join('unit', 'unit.id = quantity.id_unit', 'left');
        $this->where('id_recipe', $id_recipe);
        return $this->findAll();
    }
}
