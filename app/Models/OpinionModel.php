<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;

class OpinionModel extends Model
{
    use DataTableTrait;

    protected $table = 'opinion';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['comments', 'score', 'id_recipe', 'id_user'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'comments'  => 'permit_empty|string',
        'score'     => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        'id_recipe' => 'required|integer',
        'id_user'   => 'required|integer',
    ];

    protected $validationMessages = [
        'comments' => [
            'string' => 'Le commentaire doit être une chaîne de caractères.',
        ],
        'score' => [
            'required'              => 'La note est obligatoire.',
            'integer'               => 'La note doit être un nombre.',
            'greater_than_equal_to' => 'La note doit être au minimum 1.',
            'less_than_equal_to'    => 'La note doit être au maximum 5.',
        ],
        'id_recipe' => [
            'required' => 'La recette est obligatoire.',
            'integer'  => 'L’ID de la recette doit être un nombre.',
        ],
        'id_user' => [
            'required' => 'L’utilisateur est obligatoire.',
            'integer'  => 'L’ID de l’utilisateur doit être un nombre.',
        ],
    ];

    function insertOrUpdateScore($id_recipe, $id_user, $score = null) {
        $opinion = $this->select('COUNT(id) as count')->where('id_recipe', $id_recipe)->where('id_user', $id_user)->first();
        if ($opinion['count'] == 0) {
            //insert
            $id = $this->insert([
                'id_recipe' => $id_recipe,
                'id_user' => $id_user,
                'score' => $score
            ]);
            return ['type' => 'insert', 'id' => $id];
        } else {
            //update
            $result = $this->where('id_recipe', $id_recipe)->where('id_user', $id_user)->set('score', $score)->update();
            return ['type' => 'update', 'success' => $result];
        }
        return ['type' => 'error'];
    }

    public function getScore($id_recipe, $id_user) {
        $opinion = $this->where('id_recipe', $id_recipe)
            ->where('id_user', $id_user)
            ->first();

        return $opinion ? $opinion['score'] : null; //récupère le score OU null
    }

    function insertOrUpdateComments($id_recipe, $id_user, $comments = null) {
        $opinion = $this->select('COUNT(id) as count')->where('id_recipe', $id_recipe)->where('id_user', $id_user)->first();
        if ($opinion['count'] == 0) {
            //insert
            $id = $this->insert([
                'id_recipe' => $id_recipe,
                'id_user' => $id_user,
                'comments' => $comments,
            ]);
            return ['type' => 'insert', 'id' => $id];
        } else {
            //update
            $result = $this->where('id_recipe', $id_recipe)->where('id_user', $id_user)->set('comments', $comments)->update();
            return ['type' => 'update', 'success' => $result];
        }
        return ['type' => 'error'];
    }
    protected $skipValidation = false;
    protected $cleanValidationRules = true;


    protected $beforeDelete = [];
}
