<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Contact extends BaseController
{
    public function index()
    {
        helper(['form']);
        return $this->view('front/contact', [], false);
    }

    public function send()
    {
        helper(['form']);
        $rules = [
            'subject' => [
                'label' => 'Objet',
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Veuillez saisir un objet.',
                    'min_length' => "L'objet doit faire au moins 3 caractères.",
                ]
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|max_length[255]',
                'errors' => [
                    'required' => 'Veuillez saisir un email.',
                    'valid_email' => "Veuillez saisir un email correct.",
                    'max_lenght' => "L'email est limité à 255 caractères"
                ]
            ],
            'message' => [
                'label' => 'Message',
                'rules' => 'required|min_length[10]|max_length[1000]',
                'errors' => [
                    'required' => 'Veuillez saisir un message.',
                    'min_lenght' => "Le message doit contenir au minimum 10 caractères",
                    'max_lenght' => "Le message ne doit pas dépasser 1000 caractères"
                ]
            ]
        ];
        $data = $this->request->getPost();
        if (!$this->validate($rules)) {
            return $this->redirect('/contactez-nous', ['errors' => $this->validator, 'data' => $data]);
        }
        //print_r($data);
        $email = service('email');
        $email->setTo('contact@ins3gram.com');
        $email->setFrom($data['email']);
        $email->setSubject($data['subject']);
        $message = esc($data['message']);
        if(!isset($data['rgpd'])) $message .= "<hr> Attention, la personne n'a pas coché la case RGPD !";
        $email->setMessage($message);
        if ($email->send()) {
            return $this->redirect('/contactez-nous', ['success' => 'Email envoyé avec succès, nous vous contacterons prochainement.']);
        } else {
            return $this->redirect('/');
        }
    }
}