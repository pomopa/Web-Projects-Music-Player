<?php

namespace App\Controllers;

use App\Models\UserModel;

class SignIn extends BaseController
{
    public function showForm()
    {
        helper(['form']);
        return view('signin_form');
    }

    public function simpleSubmit()
    {
        helper(['form']);
        $rules = [
            'email'        => 'required|valid_email|is_from_domain|max_length[60]|is_email_in_system',
            'password'     => 'required|min_length[8]|special_password_rule|max_length[40]'
        ];

        $errors = [
            'email' => [
                'required'    => lang('Validation.required', ['email']),
                'valid_email' => lang('Validation.valid_email'),
                'is_email_in_system'   => lang('Validation.is_email_in_system'),
                'is_from_domain' => lang('Validation.is_from_domain'),
                'max_length' => lang('Validation.max_length', ['email', '60'])
            ],
            'password' => [
                'required'   => lang('Validation.required', [lang('App.password')]),
                'min_length' => lang('Validation.min_length'),
                'special_password_rule' => lang('Validation.special_password_rule'),
                'max_length' => lang('Validation.max_length', [lang('App.password'), '40']),
            ]
        ];

        if ($this->validate($rules, $errors)) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $userModel = new \App\Models\UserModel();

            $user = $userModel->where('email', $email)->first();

            if ($user && password_verify($password, $user['password'])) {
                $session = session();
                $session->set('user', [
                    'id' => $user['id'],
                    'email' => $user['email']
                ]);

                $session->set('state', "LOGGED IN");
                return redirect()->to(base_url(route_to('landing_view')));
            } else {
                return redirect()->back()->withInput()->with('error', ['password' => lang('Validation.email_password_incorrect')]);
            }

        } else {
            return view('signin_form');
        }

    }
}