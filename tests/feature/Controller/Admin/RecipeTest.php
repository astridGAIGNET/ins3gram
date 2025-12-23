<?php
namespace Tests\Feature\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

final class RecipeTest extends CIUnitTestCase
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
    public function testRecipeIndexPageLoadsForAdmin()
    {
        // On simule la connexion de l'admin
        $result = $this->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->get('/admin/recipe');

        // Vérifications
        $result->assertStatus(200); // La page charge bien
        $result->assertSee('Recettes'); // On voit le mot "Recettes"
    }

    /**
     * Test 2 : Vérifier qu'un utilisateur non-admin ne peut pas accéder
     */
    public function testRecipeIndexPageForbiddenForNonAdmin()
    {
        $result = $this->withSession([
            'user' => $this->user,
            'user_id' => $this->user->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->get('/admin/recipe');

        // Devrait être redirigé vers /forbidden
        $result->assertRedirectTo('/forbidden');
    }

    /* ==========================================
       TESTS DE LA PAGE CREATE (Formulaire de création)
       ========================================== */
    /**
     * Test 3 : Vérifier que le formulaire de création charge bien
     */
    public function testRecipeCreatePageLoadsForAdmin()
    {
        //Pour récupérer la route
        $result = $this->withRoutes([
            ['get', 'admin/recipe/create', '\App\Controllers\Admin\Recipe::create']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->get('/admin/recipe/create');

        $result->assertStatus(200);
        $result->assertSee('Nom de la recette');
        $result->assertSee('Général');
    }

    /* ==========================================
       TESTS DE L'INSERTION (POST /admin/recipe/insert)
       ========================================== */

    /**
     * Test 4 : Créer une recette avec données valides
     */
    public function testInsertRecipeWithValidData()
    {
        $recipeData = [
            'name' => 'Coco Mango',
            'description' => 'Une délicieuse recette au goût exotique',
            'alcool' => '0',
            'active' => 'on',
            'id_user' => $this->admin->id,
        ];

        $result = $this->withRoutes([
            ['post', 'admin/recipe/insert', '\App\Controllers\Admin\Recipe::insert'],
            ['get', 'admin/recipe', '\App\Controllers\Admin\Recipe::index']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->post('/admin/recipe/insert', $recipeData);

        // Vérifications
        $result->assertRedirectTo('/admin/recipe');
        $result->assertArrayHasKey('success');

        // Vérifier que la recette est bien en base
        $recipeModel = model('RecipeModel');
        $recipe = $recipeModel->where('name', 'Coco Mango')->first();
        $this->assertNotNull($recipe);
        $this->assertEquals('Coco Mango', $recipe['name']);
    }

    /**
     * Test 5 : Créer une recette avec un nom vide (devrait échouer)
     */
    public function testInsertRecipeWithEmptyName()
    {
        $recipeData = [
            'name' => '', // Nom vide = invalide
            'description' => 'Une description',
            'alcool' => '0',
        ];

        $result = $this->withRoutes([
            ['post', 'admin/recipe/insert', '\App\Controllers\Admin\Recipe::insert'],
            ['get', 'admin/recipe', '\App\Controllers\Admin\Recipe::index']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->post('/admin/recipe/insert', $recipeData);

        // Devrait rediriger avec une erreur
        $result->assertRedirectTo('/admin/recipe');
        $result->assertArrayHasKey('error');
    }

    /**
     * Test 6 : Créer une recette avec un nom déjà existant
     */
    public function testInsertRecipeWithDuplicateName()
    {
        // Créer une première recette
        model('RecipeModel')->insert([
            'name' => 'Recette Unique',
            'slug' => 'recette-unique',
            'alcool' => 0,
            'id_user' => $this->admin->id,
        ]);

        // Essayer de créer une recette avec le même nom
        $recipeData = [
            'name' => 'Recette Unique',
            'description' => 'Test',
            'alcool' => '0',
        ];

        $result = $this->withRoutes([
            ['post', 'admin/recipe/insert', '\App\Controllers\Admin\Recipe::insert'],
            ['get', 'admin/recipe', '\App\Controllers\Admin\Recipe::index']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->post('/admin/recipe/insert', $recipeData);

        $result->assertRedirectTo('/admin/recipe');
        $result->assertArrayHasKey('error');
    }

/**
* Test 7 : Créer une recette désactivée (sans cocher 'active')
*/
    public function testInsertInactiveRecipe()
    {
        $recipeData = [
            'name' => 'Recette Désactivée',
            'description' => 'Test',
            'alcool' => '0',
            // Pas de 'active' => sera soft deleted
            'id_user' => $this->admin->id,
        ];

        $result = $this->withRoutes([
            ['post', 'admin/recipe/insert', '\App\Controllers\Admin\Recipe::insert'],
            ['get', 'admin/recipe', '\App\Controllers\Admin\Recipe::index']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->post('/admin/recipe/insert', $recipeData);

        $result->assertRedirectTo('/admin/recipe');

        // Vérifier que la recette est soft deleted
        $recipeModel = model('RecipeModel');
        $recipe = $recipeModel->withDeleted()->where('name', 'Recette Désactivée')->first();
        $this->assertNotNull($recipe);
        $this->assertNotNull($recipe['deleted_at']);
    }

    /* ==========================================
       TESTS DE LA PAGE EDIT (Modification)
       ========================================== */

    /**
     * Test 8 : Charger la page d'édition avec une recette existante
     */
    public function testRecipeEditPageLoadsWithValidId()
    {
        // Créer une recette
        $recipeId = model('RecipeModel')->insert([
            'name' => 'Recette à Modifier',
            'slug' => 'recette-a-modifier',
            'alcool' => 0,
            'id_user' => $this->admin->id,
        ]);

        $result = $this->withRoutes([
            ['get', 'admin/recipe/edit/(:num)', '\App\Controllers\Admin\Recipe::edit/$1']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->get("/admin/recipe/edit/{$recipeId}");

        $result->assertStatus(200);
        $result->assertSee('Recette à Modifier');
    }

    /**
     * Test 9 : Essayer de charger une recette inexistante
     */
    public function testRecipeEditPageWithInvalidId()
    {
        $result = $this->withRoutes([
            ['get', 'admin/recipe/edit/(:num)', '\App\Controllers\Admin\Recipe::edit/$1'],
            ['get', 'admin/recipe', '\App\Controllers\Admin\Recipe::index']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->get('/admin/recipe/edit/99999');

        $result->assertRedirectTo('/admin/recipe');
        $result->assertArrayHasKey('error', 'Recette introuvable');
    }

    /* ==========================================
       TESTS DE L'UPDATE (POST /admin/recipe/update)
       ========================================== */

    /**
     * Test 10 : Modifier une recette avec des données valides
     */
    public function testUpdateRecipeWithValidData()
    {
        // Créer une recette
        $recipeId = model('RecipeModel')->insert([
            'name' => 'Recette Originale',
            'slug' => 'recette-originale',
            'alcool' => 0,
            'id_user' => $this->admin->id,
        ]);

        // Modifier la recette
        $updateData = [
            'id_recipe' => $recipeId,
            'name' => 'Recette Modifiée',
            'description' => 'Description mise à jour',
            'alcool' => '1',
            'active' => 'on',
        ];

        $result = $this->withRoutes([
            ['post', 'admin/recipe/update', '\App\Controllers\Admin\Recipe::update'],
            ['get', 'admin/recipe', '\App\Controllers\Admin\Recipe::index']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->post('/admin/recipe/update', $updateData);

        $result->assertRedirectTo('/admin/recipe');
        $result->assertArrayHasKey('success');

        // Vérifier que les modifications sont bien enregistrées
        $recipe = model('RecipeModel')->find($recipeId);
        $this->assertEquals('Recette Modifiée', $recipe['name']);
        $this->assertEquals(1, $recipe['alcool']);
    }

    /* ==========================================
       TESTS DE SWITCHACTIVE (Activer/Désactiver)
       ========================================== */

    /**
     * Test 11 : Désactiver une recette active
     */
    public function testSwitchActiveDeactivatesRecipe()
    {
        // Créer une recette active
        $recipeId = model('RecipeModel')->insert([
            'name' => 'Recette Active',
            'slug' => 'recette-active',
            'alcool' => 0,
            'id_user' => $this->admin->id,
        ]);

        $result = $this->withRoutes([
            ['post', 'admin/recipe/switch-active', '\App\Controllers\Admin\Recipe::switchActive']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->post('/admin/recipe/switch-active', [
            'id_recipe' => $recipeId
        ]);

        // Vérifier la réponse JSON
        $result->assertStatus(200);
        $result->assertJSONFragment([
            'success' => true,
            'status' => 'inactive'
        ]);

        // Vérifier en base que la recette est soft deleted
        $recipe = model('RecipeModel')->withDeleted()->find($recipeId);
        $this->assertNotNull($recipe['deleted_at']);
    }

    /**
     * Test 12 : Réactiver une recette désactivée
     */
    public function testSwitchActiveReactivatesRecipe()
    {
        // Créer une recette et la soft delete
        $recipeId = model('RecipeModel')->insert([
            'name' => 'Recette Inactive',
            'slug' => 'recette-inactive',
            'alcool' => 0,
            'id_user' => $this->admin->id,
        ]);
        model('RecipeModel')->delete($recipeId);

        $result = $this->withRoutes([
            ['post', 'admin/recipe/switch-active', '\App\Controllers\Admin\Recipe::switchActive']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->post('/admin/recipe/switch-active', [
            'id_recipe' => $recipeId
        ]);

        $result->assertStatus(200);
        $result->assertJSONFragment([
            'success' => true,
            'status' => 'active'
        ]);

        // Vérifier que deleted_at est null
        $recipe = model('RecipeModel')->find($recipeId);
        $this->assertNotNull($recipe);
        $this->assertNull($recipe['deleted_at']);
    }

    /**
     * Test 13 : Essayer de switcher une recette inexistante
     */
    public function testSwitchActiveWithInvalidId()
    {
        $result = $this->withRoutes([
            ['post', 'admin/recipe/switch-active', '\App\Controllers\Admin\Recipe::switchActive']
        ])->withSession([
            'user' => $this->admin,
            'user_id' => $this->admin->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->post('/admin/recipe/switch-active', [
            'id_recipe' => 99999
        ]);

        $result->assertStatus(200);
        $result->assertJSONFragment([
            'success' => false
        ]);
    }
}