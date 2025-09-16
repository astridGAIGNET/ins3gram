<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Unit extends BaseController
{
    public function index()
    {
        helper(['form']);
        return $this->view('admin/unit');
    }

    public function search()
    {
        $request = $this->request;

        // Vérification AJAX
        if (!$request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Requête non autorisée']);
        }

        $unm = Model('UnitModel');

        // Paramètres de recherche
        $search = $request->getGet('search') ?? '';
        $page = (int)($request->getGet('page') ?? 1);
        $limit = 20;

        // Utilisation de la méthode du Model (via le trait)
        $result = $unm->quickSearchForSelect2($search, $page, $limit);

        // Réponse JSON
        return $this->response->setJSON($result);
    }

    public function insert()
    {
        $unm = Model('UnitModel');
        $data = $this->request->getPost();
        if ($id_unit = $unm->insert($data)) {
            $this->success('Unité de mesure créée avec succès !');
        } else {
            foreach ($unm->errors() as $error) {
                $this->error($error);
            }
        }
        return $this->redirect('admin/unit');
    }

    public function update() {
        $unm = Model('UnitModel');
        $data = $this->request->getPost();
        $id = $data['id'];
        unset($data['id']);
        if ($unm->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'L\'unité de mesure a été modifiée avec succès !',
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $unm->errors(),
            ]);
        }
    }
    public function delete() {
        $unm = Model('UnitModel');
        $qm = Model('QuantityModel');
        $id = $this->request->getPost('id');
        // Supprimer d'abord toutes les quantités qui utilisent cette unité
        $qm->where('id_unit', $id)->delete();
        // Puis supprimer l'unité
        if ($unm->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'L\unité de mesure a été supprimée avec succès !',
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $unm->errors(),
            ]);
        }
    }
}