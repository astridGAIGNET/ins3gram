<?php

namespace App\Models;

use App\Traits\Select2Searchable;
use CodeIgniter\Model;
use App\Traits\DataTableTrait;


class BrandModel extends Model
{
    use DataTableTrait;
    use Select2Searchable;


    protected $table = 'brand';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['name'];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = ['name' => 'required|max_length[255]|is_unique[brand.name,id,{id}]'];
    protected $validationMessages = [
        'name' => [
            'required' => 'Le nom de la marque est obligatoire.',
            'max_length' => 'Le nom de la marque ne peut pas dépasser 255 caractères.',
            'is_unique' => 'Cette marque existe déjà.',
        ],
    ];

    protected $beforeDelete = [];

    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => [
                'name',
                'id',
            ],
            'joins' => [],
            'select' => '*',
        ];
    }
}