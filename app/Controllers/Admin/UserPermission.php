<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UserPermission extends BaseController
{
    public function index()
    {
        helper('form');
        return $this->view('admin/user-permission');
    }

    public function insert()
    {
        $upm = model('UserPermissionModel');
        $data = $this->request->getPost();
        if ($upm->insert($data)) {
            $this->success('Permission utilisateur bien créée');
        } else {
            //print_r($upm->errors());
                //die();
            foreach ($upm->errors() as $key =>$error) {
                $this->error($key . ' : ' . $error);
            }
        }
        return $this->redirect('admin/user-permission');
    }
    public function update()
    {
        $upm = model('UserPermissionModel');
        $data = $this->request->getPost();
        $id = $data['id'];
        unset($data['id']);
        if ($upm->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'La permission a été modifiée avec succès !',
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $upm->errors(),
            ]);
        }
    }
    public function delete()
    {
        $upm = model('UserPermissionModel');
        $id = $this->request->getPost('id');
        if ($upm->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "La permission a été supprimée avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $upm->errors(),
            ]);
        }
    }
}
