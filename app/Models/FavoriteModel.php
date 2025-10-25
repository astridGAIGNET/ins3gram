<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Traits\DataTableTrait;
class FavoriteModel extends Model
{
    use DataTableTrait;

    protected $table            = 'favorite';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id_user','id_recipe'];

    protected $validationRules = [
        'id_recipe' => 'required|integer',
        'id_user'   => 'required|integer',
    ];

    protected $validationMessages = [
        'id_recipe' => [
            'required' => 'La recette est obligatoire.',
            'integer'  => 'L’ID de la recette doit être un nombre.',
        ],
        'id_user' => [
            'required' => 'L’utilisateur est obligatoire.',
            'integer'  => 'L’ID de l’utilisateur doit être un nombre.',
        ],
    ];

    function switchFavorite($id_recipe, $id_user) {
        if ($this->hasFavorite($id_recipe, $id_user)) {
            $res = $this->delete(['id_recipe' => $id_recipe, 'id_user' => $id_user]);
            return ['type' => 'delete', 'success' => $res];
        } else {
            $res = $this->insert(['id_recipe' => $id_recipe, 'id_user' => $id_user]);
            return ['type' => 'insert', 'success' => $res];
        }
    }

    function hasFavorite($id_recipe, $id_user) {
        $favorite = $this->select('COUNT(*) as count')->where('id_recipe', $id_recipe)->where('id_user', $id_user)->first();
        if ($favorite['count'] != 0) {
            return true;
        }
        return false;
    }

    public function getFavoritesByRecipe($id_recipe) {
        return $this->select('favorite.*, user.username')
            ->join('user', 'user.id = favorite.id_user')
            ->where('favorite.id_recipe', $id_recipe)
            ->findAll();
    }

    public function countFavoritesByRecipe($id_recipe) {
        return $this->where('id_recipe', $id_recipe)
            ->countAllResults();
    }
}
