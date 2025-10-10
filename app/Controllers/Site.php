<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Site extends BaseController
{
    public function forbidden()
    {
        return $this->view('templates/forbidden', [], false);
    }
    public function show404()
    {
        return $this->view('templates/404', [], false);
    }

    public function test() {
        $email = service('email');

        $email->setTo('astridgaignet17@gmail.com');

        $email->setSubject('Email Test');
        $email->setMessage('Ceci est un message.');
        if ($email->send()) {
            echo 'Email envoyé avec succès!';
        } else {
            echo $email->printDebugger(['headers']);
        }
    }

}
