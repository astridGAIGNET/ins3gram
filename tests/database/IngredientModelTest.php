<?php

namespace Tests\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\IngredientModel;

final class IngredientModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    // Configuration des tests
    protected $migrate = true;
    protected $refresh = true;
    protected $DBGroup = 'tests';
    protected $namespace = 'App';
    protected $seed = 'Tests\Support\Database\Seeds\IngredientTestSeeder';

    private IngredientModel $ingredientModel;
    private int $Id;

    /**
     * Crée un ingredient
     */
    private function createIngredient(): int
    {
        $id_ingredient = $this->ingredientModel->insert([
            'name' => 'Jus de cerise',
            'description' => 'le jus de cerise est un jus de couleur rouge au goût sucré',
            'id_brand' => $this->testBrandId,
            'id_categ' => $this->testCategId,
        ]);

        $media = [
            ['file_path' => 'uploads/2025/10/ingredient/12/margarita-6023895-1280-jpg-1759481401.jpg', 'entity_id' => $id_ingredient, 'entity_type' => 'ingredient', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
        ];
        $this->db->table('media')->insertBatch($media);
        return $id_ingredient;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->ingredientModel = new ingredientModel();

        $this->testBrandId = 23;
        $this->testCategId = 5;
    }

    public function testCanCreateIngredient()
    {
        $data = [
            'name' => 'Jus de mangue fraîche',
            'description' => 'Jus de couleur jaune au goût sucré',
            'id_brand' => $this->testBrandId,
            'id_categ' => $this->testCategId,
        ];

        $ingredientId = $this->ingredientModel->insert($data);

        $this->assertIsInt($ingredientId);
        $this->assertGreaterThan(0, $ingredientId);
        $this->seeInDatabase('ingredient', ['name' => 'Jus de mangue fraiche']);
    }
}