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
        $this->addBreadCrumb('Recettes', "/admin/recipe");
        $this->addBreadCrumb('Création d\'une recette', "");
        return $this->view('admin/recipe/form');

    }
}
