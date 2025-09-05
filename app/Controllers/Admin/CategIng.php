<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class CategIng extends BaseController
{
    public function index()
    {
        helper('form');

        // Récupérer toutes les catégories pour le select parent
        $parents = model('CategIngModel')->orderBy('name')->findAll();

        return $this->view('admin/categ-ing', ['parents' => $parents]);
    }

    public function insert()
    {
        $cim = model('CategIngModel');
        $data = $this->request->getPost();
        // si c'est vide on retire l'info du data donc on donne juste un nom
        if(empty($data['id_categ_parent'])) unset($data['id_categ_parent']);
        if ($cim->insert($data)) {
            $this->success('catégorie d\'ingrédients bien créée');
        } else {
            //print_r($upm->errors());
            //die();
            foreach ($cim->errors() as $error) {
                $this->error ($error);
            }
        }
        return $this->redirect('admin/categ-ing');
    }
    public function update()
    {
        $cim = model('CategIngModel');
        $data = $this->request->getPost();
        $id = $data['id'];
        unset($data['id']);
        if ($cim->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'La catégorie a été modifiée avec succès !',
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $cim->errors(),
            ]);
        }
    }
    public function delete()
    {
        $cim = model('CategIngModel');
        $id = $this->request->getPost('id');
        if ($cim->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "La catégorie a été supprimée avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $cim->errors(),
            ]);
        }
    }
}
