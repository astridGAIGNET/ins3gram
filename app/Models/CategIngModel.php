<?php

namespace App\Models;

use App\Traits\Select2Searchable;
use CodeIgniter\Model;
use App\Traits\DataTableTrait;

class CategIngModel extends Model
{
    use DataTableTrait;
    use Select2Searchable;

    protected $table            = 'categ_ing';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name','id_categ_parent'];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules      = ['name' => 'required|max_length[255]|is_unique[categ_ing.name,id,{id}]', 'id_categ_parent' => 'permit_empty|integer',];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Le nom de la catégorie est obligatoire.',
            'max_length' => 'Le nom de la catégorie ne peut pas dépasser 255 caractères.',
            'is_unique'  => 'Cette catégorie existe déjà.',
        ],
        'id_categ_parent' => [
            'integer' => 'L’ID du parent doit être un nombre.',
        ],
    ];


    protected $beforeDelete   = [];
    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => [
                'categ_ing.name',
                'categ_ing.id',
                'parent_name.name'
            ],
            'joins' => [
                [
                    'table' => 'categ_ing as parent_name',
                    'condition' => 'categ_ing.id_categ_parent = parent_name.id',
                    'type' => 'left'
                ]
            ],
            'select' => 'categ_ing.*, parent_name.name as parent_name',
            'with_deleted' => false
        ];
    }
}
