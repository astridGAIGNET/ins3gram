<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Ingredient extends BaseController
{
    public function search()
    {
        $request = $this->request;

        // Vérification AJAX
        if (!$request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Requête non autorisée']);
        }

        $im = Model('IngredientModel');

        // Paramètres de recherche
        $search = $request->getGet('search') ?? '';
        $page = (int)($request->getGet('page') ?? 1);
        $limit = 20;

        // Utilisation de la méthode du Model (via le trait)
        $result = $im->quickSearchForSelect2($search, $page, $limit);

        // Réponse JSON
        return $this->response->setJSON($result);
    }

    public function index()
    {
        return $this->view('admin/ingredient/index');
    }

    public function create()
    {
        helper('form');
        $brands = Model('BrandModel')->findAll();
        $categs = Model('CategIngModel')->findAll();
        $subs = Model('IngredientModel')->findAll();

        return $this->view('admin/ingredient/form', ['brands' => $brands, 'categs' => $categs, 'subs' => $subs]);
    }

    public function insert()
    {
        $data = $this->request->getPost();
        $id_ingredient = $this->request->getPost('id');

        // Extraire les substituts des données principales pour éviter l'erreur
        $substitutes = null;
        if (isset($data['substitutes']) && is_array($data['substitutes'])) {
            $substitutes = $data['substitutes'];
            unset($data['substitutes']); // CRUCIAL pour éviter l'erreur du modèle
        }

        $im = model('IngredientModel');

        // Nettoyage des données
        if (empty($data['id_brand'])) {
            unset($data['id_brand']);
        }
        if (empty($data['id_categ'])) {
            $this->error('La catégorie est obligatoire.');
            return $this->redirect('/admin/ingredient/new');
        }
        // Insertion de l'ingrédient principal
        if ($id_ingredient = $im->insert($data)) {
            $this->success('Ingrédient créé avec succès !');

            // Traitement des substituts
            if (!empty($substitutes)) {
                $sbm = Model('SubstituteModel');
                foreach ($substitutes as $id_substitute) {
                    $substitute = [
                        'id_ingredient_base' => $id_ingredient,
                        'id_ingredient_sub' => $id_substitute
                    ];
                    // Debug
                    //echo "Data à insérer : ";
                    //var_dump($substitute);
                    //echo "Erreurs modèle : ";
                    //var_dump($sbm->errors());
                    //die(); // Arrête l'exécution pour voir les infos
                    $sbm->insert($substitute);
                }
            }
            //images mea
            $mea = $this->request->getFile('mea');
            if($mea && $mea->getError() !== UPLOAD_ERR_NO_FILE){
                $mediaData = [
                    'entity_type' => 'ingredient',
                    'entity_id' => $id_ingredient,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                // Utiliser la fonction upload_file() de l'utils_helper pour gérer l'upload et les données du média
                $uploadResult = upload_file($mea, 'ingredient/mea', $mea->getName(), $mediaData,false);
                // Vérifier le résultat de l'upload
                if (is_array($uploadResult) && $uploadResult['status'] === 'error') {
                    // Afficher un message d'erreur détaillé
                    $this->error("Une erreur est survenue lors de l'upload de l'image : " . $uploadResult['message']);
                }
            }
        } else {
            foreach ($im->errors() as $error) {
                $this->error($error);
            }
        }

        return $this->redirect('/admin/ingredient');
    }
    function edit($id_ingredient)
    {
        $im = model('IngredientModel');
        $ingredient = $im->find($id_ingredient);
        if (!$ingredient) {
            $this->error('Ingrédient inexistant');
            return $this->redirect('/admin/ingredient');
        }

        helper('form');
        $brands = Model('BrandModel')->findAll();
        $categs = Model('CategIngModel')->findAll();
        $subs = Model('IngredientModel')->findAll(); // TOUS les ingrédients pour le select

        // Initialiser le tableau des substituts
        $ingredient->substitutes = [];

        //Gestion des mots substituts à notre ingredient
        if($id_ingredient != null) {
            //Récupération des substituts à notre ingredient
            $existing_substitutes = Model('SubstituteModel')->where('id_ingredient_base', $id_ingredient)->findAll();
            //Création d'un tableau à une dimension pour utiliser in_array (directement dans notre tableau ingredient)
            foreach ($existing_substitutes as $substitute) {
                $ingredient->substitutes[] = $substitute['id_ingredient_sub'];
            }
        } else { //Cas d'un slug (notamment pour l'affichage en Front office)
            $ingredient->substitutes = Model('SubsituteModel')->join('substitute', 'ingredient.id = substitute.id_ingredient_sub')->where('id_ingredient_base', $id_ingredient)->findAll();
        }
        $mediamodel = Model('MediaModel');
        //Récupération de l'image principale et stocker dans le tableau ingredient
        $ingredient->mea = $mediamodel->where('entity_id', $id_ingredient)->where('entity_type', 'ingredient')->first();

        return $this->view('/admin/ingredient/form', [
            'ingredient' => $ingredient,
            'brands' => $brands,
            'categs' => $categs,
            'subs' => $subs // Tous les ingrédients disponibles
        ]);
    }

    public function update()
    {
        $id_ingredient = $this->request->getPost('id');
        $im = model('IngredientModel');
        $data = $this->request->getPost();
        $id = $data['id'];

        //nettoyer les champs vides
        if (empty($data['id_brand'])) {
            unset($data['id_brand']);
        }
        if (empty($data['id_categ'])) {
            $this->error('La catégorie est obligatoire.');
            return $this->redirect('/admin/ingredient/edit/' . $id);
        }

        // Traitement des substituts
        if (!empty($substitutes)) {
            $sbm = Model('SubstituteModel');
            foreach ($substitutes as $id_substitute) {
                $substitute = [
                    'id_ingredient_base' => $id_ingredient,
                    'id_ingredient_sub' => $id_substitute
                ];
                // Debug
                //echo "Data à insérer : ";
                //var_dump($substitute);
                //echo "Erreurs modèle : ";
                //var_dump($sbm->errors());
                //die(); // Arrête l'exécution pour voir les infos
                $sbm->insert($substitute);
            }
        }

        //images mea
        $mea = $this->request->getFile('mea');
        if($mea && $mea->getError() !== UPLOAD_ERR_NO_FILE){
            $mediaData = [
                'entity_type' => 'ingredient',
                'entity_id' => $id_ingredient,
                'created_at' => date('Y-m-d H:i:s')
            ];
            // Utiliser la fonction upload_file() de l'utils_helper pour gérer l'upload et les données du média
            $uploadResult = upload_file($mea, 'ingredient/'.$id_ingredient, $mea->getName(), $mediaData,false);
            // Vérifier le résultat de l'upload
            if (is_array($uploadResult) && $uploadResult['status'] === 'error') {
                // Afficher un message d'erreur détaillé
                $this->error("Une erreur est survenue lors de l'upload de l'image : " . $uploadResult['message']);
            }
        }
        if ($im->update($id, $data)) {
            $this->success('l\'ingredient a bien été modifié !');

        } else {
            foreach ($im->errors() as $error) {
                $this->error($error);
            }
        }

        return $this->redirect('admin/ingredient');
    }

    public
    function delete()
    {
        $im = model('IngredientModel');
        $sbm = Model('SubstituteModel');
        $id = $this->request->getPost('id');

        // Supprimer d'abord les substituts liés (pour éviter les contraintes de clé étrangère)
        $sbm->where('id_ingredient_base', $id)->delete();
        $sbm->where('id_ingredient_sub', $id)->delete(); // Au cas où cet ingrédient est utilisé comme substitut

        if ($im->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "L'ingrédient a été supprimé avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $im->errors(),
            ]);
        }
    }
}