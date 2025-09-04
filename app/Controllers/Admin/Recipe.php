<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Recipe extends BaseController
{
    protected $breadcrumb = [['text' => 'Tableau de bord', 'url' => '/admin/dashboard']];
    public function index()
    {
        $this->addBreadCrumb('Recettes', "/admin");
        return $this->view('admin/recipe/index');
    }

    public function create() {
        helper('form');
        $this->addBreadCrumb('Recettes', "/admin/recipe");
        $this->addBreadCrumb('Création d\'une recette', "");
        $users = Model('UserModel')->findAll();
        return $this->view('admin/recipe/form', ['users' => $users]);

    }

    public function insert() {
        $data = $this->request->getPost();
        //echo "<pre>";
        //print_r($data);
        $rm = Model('RecipeModel');
        if($rm->insert($data)) {
            $this->success('Recette créée avec succès !');
            $this->redirect('/admin/recipe');
        } else {
            foreach ($rm->errors() as $error) {
                $this->error($error);
            }
        }
        return $this->redirect('/admin/recipe');
    }

    public function edit($id_recipe) {
        helper('form');
        $this->addBreadCrumb('Recettes', "/admin/recipe");
        $this->addBreadCrumb('Modification d\'une recette', "");
        $recipe = Model('RecipeModel')->find($id_recipe);
        if(!$recipe) {
            $this->error('Recette introuvable');
            return $this->redirect('/admin/recipe');
        }
        $users = Model('UserModel')->findAll();
        return $this->view('admin/recipe/form', ['users' => $users, 'recipe' => $recipe]);
    }

    public function update() {
        $data = $this->request->getPost();
        $id_recipe = $data['id_recipe'];
        $rm = Model('RecipeModel');
        if($rm->update($id_recipe, $data)) {
            $this->success('Recette modifiée avec succès !');
            $this->redirect('/admin/recipe');
        } else {
            foreach ($rm->errors() as $error) {
                $this->error($error);
            }
        }
        return $this->redirect('/admin/recipe');
    }
}
