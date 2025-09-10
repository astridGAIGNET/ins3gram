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

    public function create() {
        helper('form');
        $brands = Model('BrandModel')->findAll();
        $categs = Model('CategIngModel')->findAll();

        return $this->view('admin/ingredient/form', ['brands' => $brands, 'categs' => $categs]);
    }

    public function insert()
    {
        $data = $this->request->getPost();
        //echo "<pre>";
        //print_r($data);
        //die();
        $im = model('IngredientModel');
        //Ajout de mon ingrédient + Récupération de l'ID de mon ingrédient ajouté
        if (empty($data['id_brand'])) {
            unset($data['id_brand']);
        }
        if (empty($data['id_categ'])) {
            $this->error('La catégorie est obligatoire.');
            return $this->redirect('/admin/ingredient/new');
        }
        if ($im->insert($data)) {
            $this->success('Ingredient bien créé');

        } else {
            foreach ($im->errors() as $error) {
                $this->error($error);
            }
        }

        return $this->redirect('admin/ingredient');
    }


    public function update()
    {
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
        if ($im->update($id,$data)) {
            $this->success('l\'ingredient a bien été modifié !');

        } else {
            foreach ($im->errors() as $error) {
                $this->error($error);
            }
        }

        return $this->redirect('admin/ingredient');
    }

    public function edit($id_ingredient) {
        $im = model('IngredientModel');
        $ingredient = $im->find($id_ingredient);
        if (!$ingredient) {
            $this->error('Ingrédient inexistant');
            return $this->redirect('/admin/ingredient');
        }
        helper('form');
        $brands = Model('BrandModel')->findAll();
        $categs = Model('CategIngModel')->findAll();
        return $this->view('/admin/ingredient/form',['ingredient' => $ingredient, 'brands' => $brands, 'categs' => $categs]);
    }

    public function delete()
    {
        $im = model('IngredientModel');
        $id = $this->request->getPost('id');
        if ($im->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "l'ingrédient a été supprimé avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $im->errors(),
            ]);
        }
    }
}