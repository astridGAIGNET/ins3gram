<?php
namespace Tests\Feature\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

final class IngredientTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $refresh = true;
    protected $DBGroup = 'tests';
    protected $namespace = 'App';
    protected $seed = 'App\Database\Seeds\MasterSeeder';

    // Variable pour stocker un admin créé
    private $admin;
    private $user;

    /**
     * Cette méthode s'exécute AVANT chaque test
     * On crée un admin pour les tests qui en ont besoin
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Créer un utilisateur admin pour les tests
        $this->admin = new \App\Entities\User([
            'email' => 'admin@test.com',
            'username' => 'admintest',
            'birthdate' => '1990-01-01',
            'id_permission' => 1, // Admin
        ]);
        $this->admin->setPassword('admin123');
        model('UserModel')->save($this->admin);
        // Recharger pour avoir l'ID
        $this->admin = model('UserModel')->find(model('UserModel')->getInsertID());

        // Créer un utilisateur normal
        $this->user = new \App\Entities\User([
            'email' => 'user@test.com',
            'username' => 'usertest',
            'birthdate' => '1990-01-01',
            'id_permission' => 2, // User normal
        ]);
        $this->user->setPassword('user123');
        model('UserModel')->save($this->user);
        $this->user = model('UserModel')->find(model('UserModel')->getInsertID());
    }

    /* ==========================================
       TESTS DE LA PAGE INDEX (Liste des recettes)
       ========================================== */

    /**
     * Test 1 : Vérifier que la page liste des recettes charge correctement
     * pour un admin connecté
     */
    public function testIngredientIndexPageLoadsForAdmin()
    {
        // On simule la connexion de l'admin
        $result = $this->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->get('/admin/ingredient');

        // Vérifications
        $result->assertStatus(200); // La page charge bien
        $result->assertSee('Ingrédient'); // On voit le mot "Recettes"
    }

    /**
     * Test 2 : Vérifier qu'un utilisateur non-admin ne peut pas accéder
     */
    public function testIngredientIndexPageForbiddenForNonAdmin()
    {
        $result = $this->withSession([
            'user' => $this->user,
            'user_id' => $this->user->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->get('/admin/ingredient');

        // Devrait être redirigé vers /forbidden
        $result->assertRedirectTo('/forbidden');
    }

    /* ==========================================
       TESTS DE LA PAGE CREATE (Formulaire de création)
       ========================================== */
    /**
     * Test 3 : Vérifier que le formulaire de création charge bien
     */
    public function testIngredientCreatePageLoadsForAdmin()
    {
        //Pour récupérer la route
        $result = $this->withRoutes([
            ['get', 'admin/ingredient/create', '\App\Controllers\Admin\Ingredient::create']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->get('/admin/ingredient/create');

        $result->assertStatus(200);
        $result->assertSee('Création d\'un nouvel ingrédient');
        $result->assertSee('Nom de l\'ingrédient');
    }

    /* ==========================================
       TESTS DE L'INSERTION (POST /admin/recipe/insert)
       ========================================== */

    /**
     * Test 4 : Créer une recette avec données valides
     */
    public function testInsertIngredientWithValidData()
    {
        $ingredientData = [
            'name' => 'Courgette',
            'description' => 'Un légume au goût sans saveur',
            'id_categ' => 1,
        ];

        $result = $this->withRoutes([
            ['post', 'admin/ingredient/insert', '\App\Controllers\Admin\Ingredient::insert'],
            ['get', 'admin/ingredient', '\App\Controllers\Admin\Ingredient::index']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->post('/admin/ingredient/insert', $ingredientData);

        // Vérifications
        $result->assertRedirectTo('/admin/ingredient');
        $result->assertArrayHasKey('success');

        // Vérifier que l'ingédient est bien en base
        $ingredientModel = model('IngredientModel');
        $ingredient = $ingredientModel->where('name', 'Courgette')->first();
        $this->assertNotNull($ingredient);
        $this->assertEquals('Courgette', $ingredient['name']);
    }

    /**
     * Test 5 : Créer un ingredient avec un nom vide (devrait échouer)
     */
    public function testInsertIngredientWithEmptyName()
    {
        $ingredientData = [
            'name' => '', // Nom vide = invalide
            'description' => 'Une description',
            'id_categ' => 3,
        ];

        $result = $this->withRoutes([
            ['post', 'admin/ingredient/insert', '\App\Controllers\Admin\Ingredient::insert'],
            ['get', 'admin/ingredient', '\App\Controllers\Admin\Ingredient::index']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->post('/admin/ingredient/insert', $ingredientData);

        // Devrait rediriger avec une erreur
        $result->assertRedirectTo('/admin/ingredient');
        $result->assertArrayHasKey('error');
    }
}