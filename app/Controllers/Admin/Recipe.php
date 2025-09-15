<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Recipe extends BaseController
{
    protected $breadcrumb = [['text' => 'Tableau de Bord','url' => '/admin/dashboard']];
    public function index()
    {
        $this->addBreadcrumb('Recettes', "");
        return $this->view('admin/recipe/index');
    }

    public function create() {
        helper('form');
        $this->addBreadcrumb('Recettes', "/admin/recipe");
        $this->addBreadcrumb('Création d\'une recette', "");
        $tags = Model('TagModel')->findAll();
        return $this->view('admin/recipe/form', ['tags' => $tags]);
    }
    public function edit($id_recipe) {
        helper('form');
        $this->addBreadcrumb('Recettes', "/admin/recipe");
        $this->addBreadcrumb('Modification d\'une recette', "");
        $recipe = Model('RecipeModel')->withDeleted()->find($id_recipe);
        if(!$recipe) {
            $this->error('Recette introuvable');
            return $this->redirect('/admin/recipe');
        }
        $user = Model('UserModel')->withDeleted()->find($recipe['id_user']);
        unset($recipe['id_user']);
        $recipe['user'] = $user;
        $qm = Model('QuantityModel');
        $ingredients = $qm->getQuantityByRecipe($id_recipe);
        //recupération de la liste des tags
        $tags = Model('TagModel')->findAll();
        //recupération des ingrédients selectionnés
        $recipe['ingredients'] = $ingredients;
        //recupération des tags selectionnés
        $recipe_tags = Model('TagRecipeModel')->where('id_recipe', $id_recipe)->findAll();
        foreach($recipe_tags as $recipe_tag) {
            $recipe['tags'][] = $recipe_tag['id_tag'];
        }
        //Récupération des étapes créées
        $steps = Model('StepModel')->where('id_recipe', $id_recipe)->orderBy('order','ASC')->findAll();
        $recipe['steps'] = $steps;

        return $this->view('admin/recipe/form', ['tags' => $tags, 'recipe' => $recipe]);
    }

    public function insert() {

        $data = $this->request->getPost();
        //echo "<pre>";
        //print_r($data);
        //die();
        $rm = Model('RecipeModel');

        //Ajout de ma recette + Récupération de l'ID de ma recette ajouté
        if($id_recipe = $rm->insert($data, true)){
            $this->success('Recette créée avec succès !');
            //Gestion activation /désactivation
            if(!isset($data['active'])) {
                $rm->delete($id_recipe);
            }
            if(isset($data['ingredients'])) {
                $qm = Model('QuantityModel');

                //Ajout des ingrédients
                foreach($data['ingredients'] as $ingredient) {
                    $ingredient['id_recipe'] = $id_recipe;
                    if ($qm->insert($ingredient)) {
                        $this->success('Ingrédient ajouté avec succès !');
                    } else {
                        foreach($qm->errors() as $error){
                            $this->error($error);
                        }
                    }
                }
            }

            if(isset($data['tags'])) {
                //ajout des mots clés
                $trm = Model('TagRecipeModel');
                $tag_recipe = array();
                $tag_recipe['id_recipe'] = $id_recipe;
                foreach($data['tags'] as $id_tag) {
                    $tag_recipe['id_tag'] = $id_tag;
                    if ($trm->insert($tag_recipe)) {
                        $this->success('Mots clés ajoutés avec succès à la recette !');
                    } else {
                        foreach ($trm->errors() as $error) {
                            $this->error($error);
                        }
                    }
                }
            }

            if(isset($data['step'])) {
                $sm = Model('StepModel');
                foreach($data['steps'] as $order => $step) {
                    $step['id_recipe'] = $id_recipe;
                    $step['order'] = $order;
                    if ($sm->insert($step)) {
                        $this->success('Étape ajoutée avec succès à la recette !');
                    } else {
                        foreach($sm->errors() as $error){
                            $this->error($error);
                        }
                    }
                }
            }
        } else {
            foreach($rm->errors() as $error){
                $this->error($error);
            }
        }
        return $this->redirect('/admin/recipe');
    }

    public function update() {
        $data = $this->request->getPost();
        $id_recipe = $data['id_recipe'];
        $rm = Model('RecipeModel');
        if($rm->update($id_recipe,$data)){
            $this->success('Recette modifiée avec succès !');
            //Gestion activation / désactivation
            if(isset($data['active'])) {
                $rm->reactive($id_recipe);
            } else {
                $rm->delete($id_recipe);
            }
            //Gestion des mots clés
            $trm = Model('TagRecipeModel');
            if($trm->where('id_recipe', $id_recipe)->delete()){
                if(isset($data['tags'])) {
                    //ajout des mots clés (avec création d'un tableau)
                    $tag_recipe = array();
                    $tag_recipe['id_recipe'] = $id_recipe;
                    foreach($data['tags'] as $id_tag) {
                        $tag_recipe['id_tag'] = $id_tag;
                        if ($trm->insert($tag_recipe)) {
                            //nothing
                        } else {
                            foreach ($trm->errors() as $error) {
                                $this->error($error);
                            }
                        }
                    }
                }

                if(isset($data['steps'])) {
                    $sm = Model('StepModel');
                    $existing_steps = $sm->where('id_recipe', $id_recipe)->findAll();
                    $check_existing_steps = array();
                    foreach ($existing_steps as $step) {
                        $check_existing_steps[] = $step['id'];
                    }
                    foreach($data['steps'] as $order => $step) {
                        //Pas d'ID alors c'est un ajout
                        if(!isset($step['id'])) {
                            //Insert
    @                       $step['id_recipe'] = $id_recipe;
                            $step['order'] = $order;
                            if ($sm->insert($step)) {
                                $this->success('Étape ajoutée avec succès à la recette !');
                            } else {
                                foreach($sm->errors() as $error){
                                    $this->error($error);
                                }
                            }
                        } else {
                            //Si j'ai un ID et qu'il est présent dans la BDD c'est une modification
                            if(($key = array_search($step['id'], $check_existing_steps)) !== FALSE) {
                            //Update + unset(id)
                                $step['order'] = $order;
                                if ($sm->update($step['id'],$step)) {
                                    $this->success('Étape modifiée avec succès !');
                                } else {
                                    foreach($sm->errors() as $error){
                                        $this->error($error);
                                    }
                                }
                                unset($check_existing_steps[$key]);
                            }
                        }
                    }
                    foreach($check_existing_steps as $id) {
                        //Delete
                        foreach($check_existing_steps as $id_step) {
                            if($sm->delete($id_step)) {
                                $this->success('Étape supprimée avec succès !');
                            } else {
                                foreach($sm->errors() as $error){
                                    $this->error($error);
                                }
                            }
                        }
                    }
                }
            } else {
                foreach($rm->errors() as $error){
                    $this->error($error);
                }
            }

            //Gestion des ingrédients
            $qm = Model('QuantityModel');
            if($qm->where('id_recipe', $id_recipe)->delete()){
                if(isset($data['ingredients'])) {
                    //ajout des ingrédients
                    foreach($data['ingredients'] as $ingredient) {
                        $ingredient['id_recipe'] = $id_recipe;
                        if ($qm->insert($ingredient)) {
                            //nothing
                        } else {
                            foreach($qm->errors() as $error){
                                $this->error($error);
                            }
                        }
                    }
                }
            } else {
                foreach($qm->errors() as $error){
                    $this->error($error);
                }
            }
        } else {
            foreach($rm->errors() as $error){
                $this->error($error);
            }
        }
        return $this->redirect('/admin/recipe');
    }
}