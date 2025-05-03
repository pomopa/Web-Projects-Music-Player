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
            'password'     => 'required|min_length[8]|special_password_rule|max_length[60]'
        ];

        $errors = [
            'email' => [
                'required'    => 'The Email field is required.',
                'valid_email' => 'The email address is not valid.',
                'is_email_in_system'   => 'User with this email address does not exist.',
                'is_from_domain' => 'Only emails from the domain @students.salle.url.edu, @ext.salle.url.edu or @salle.url.edu are accepted.',
                'max_length' => 'The email address must be less than 60 characters long.'
            ],
            'password' => [
                'required'   => 'The Password field is required.',
                'min_length' => 'The password must contain at least 8 characters.',
                'special_password_rule' => 'The password must contain both upper and lower case letters and numbers.',
                'max_length' => 'The password must be less than 60 characters long.',
                'check_signin' => 'Your email and/or password are incorrect.'
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
                return redirect()->to('');
            } else {
                return redirect()->back()->withInput()->with('errors', ['password' => 'Your email and/or password are incorrect.']);
            }

        } else {
            return view('signin_form');
        }

    }
}