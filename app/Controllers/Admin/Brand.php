<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Brand extends BaseController
{
    public function index()
    {
        helper('form');
        return $this->view('admin/brand');
    }

    public function insert() {
        $bm = Model('BrandModel');
        $brand = $this->request->getPost();
        if ($bm->insert($data)) {
            $this->success('Marque bien créée');
        } else {
            foreach ($bm->errors() as $key => $error) {
                $this->error($key . ' : ' . $error);
            }
        }
        return $this->redirect('admin/brand');
    }

    public function update() {
        $bm = model('BrandModel');
        $data = $this->request->getPost();
        $id = $data['id'];
        unset($data['id']);
        if ($bm->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'La marque a été modifiée avec success !',
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $bm->errors(),
            ]);
        }
    }
    public function delete() {
        $bm = model('BrandModel');
        $id = $this->request->getPost('id');
        if ($bm->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "La marque a été supprimée avec success !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $bm->errors(),
            ]);
        }
    }
}
