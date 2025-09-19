<?php

namespace App\Models;

use CodeIgniter\Model;

class SubstituteModel extends Model
{
    protected $table            = 'substitute';
    protected $primaryKey       = ['id_ingredient_base'];
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_ingredient_base', 'id_ingredient_sub'];

    protected $validationRules = [
        'id_ingredient_base' => 'required|integer',
        'id_ingredient_sub'  => 'required|integer|differs[id_ingredient_base]',
    ];

    protected $validationMessages = [
        'id_ingredient_base' => [
            'required' => 'L’ingrédient de base est obligatoire.',
            'integer'  => 'L’ID de l’ingrédient de base doit être un nombre.',
        ],
        'id_ingredient_sub' => [
            'required'  => 'L’ingrédient substitut est obligatoire.',
            'integer'   => 'L’ID de l’ingrédient substitut doit être un nombre.',
            'different' => 'L’ingrédient substitut doit être différent de l’ingrédient de base.',
        ],
    ];

    protected function getDataTableConfig(): array {
        return [
            'searchable_fields' => [
                'substitute.id_ingredient_base',
                'substitute.id_ingredient_sub',
                'ingredient_base.name',
                'ingredient_sub.name',
            ],
            'joins' => [
                [
                    'table' => 'ingredient as ingredient_base',
                    'condition' => 'substitute.id_ingredient_base = ingredient_base.id',
                    'type' => 'left',
                ],
                [
                    'table' => 'ingredient as ingredient_sub',
                    'condition' => 'substitute.id_ingredient_sub = ingredient_sub.id',
                    'type' => 'left',
                ]
            ],
            'select' => 'substitute.*, ingredient_base.name as ingredient_base_name, ingredient_sub.name as ingredient_sub_name',
            'with_deleted' => false
        ];
    }
}
