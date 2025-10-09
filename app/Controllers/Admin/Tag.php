<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Tag extends BaseController
{

    public function search()
    {
        $request = $this->request;

        // Vérification AJAX
        if (!$request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Requête non autorisée']);
        }

        $tm = Model('TagModel');

        // Paramètres de recherche
        $search = $request->getGet('search') ?? '';
        $page = (int)($request->getGet('page') ?? 1);
        $limit = 20;

        // Utilisation de la méthode du Model (via le trait)
        $result = $tm->quickSearchForSelect2($search, $page, $limit);

        // Réponse JSON
        return $this->response->setJSON($result);
    }
    public function index()
    {
        helper('form');
        return $this->view('admin/tag');
    }

    public function insert() {
        $tm = model('TagModel');
        $data = $this->request->getPost();
        if ($tm->insert($data)) {
            $this->success('Mot clé bien créé');
        } else {
            foreach ($tm->errors() as $key =>$error) {
                $this->error($key . ' : ' . $error);
            }
        }
        return $this->redirect('admin/tag');
    }

    public function update() {
        $tm = Model('TagModel');
        $data = $this->request->getPost();
        $id = $data['id'];
        unset($data['id']);
        if ($tm->update($id, $data)) {
            return $this->response->setJSON([
               'success' => true,
               'message' => 'Mot clé modifié avec succès !'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $tm->errors(),
            ]);
        }
    }

    public function delete() {
        $tm = Model('TagModel');
        $id = $this->request->getPost('id');
        if ($tm->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "le mot clé a été supprimé avec succès !"
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $tm->errors(),
            ]);
        }
    }
}
