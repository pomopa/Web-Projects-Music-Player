<?php

namespace App\Controllers;

use App\Models\UserModel;

class SignUp extends BaseController
{
    private const UPLOADS_DIR = WRITEPATH . 'uploads';

    public function showForm()
    {
        helper(['form']);
        return view('signup_form');
    }

    public function simpleSubmit()
    {
        helper(['form']);
        $rules = [
            'email'        => 'required|valid_email|is_from_domain|max_length[40]|is_email_unique',
            'password'     => 'required|min_length[8]|special_password_rule|max_length[40]',
            'repeat_password'  => 'required|matches[password]',
            'username' => 'max_length[20]',
        ];

        $errors = [
            'email' => [
                'required'    => 'The Email field is required.',
                'valid_email' => 'The email address is not valid.',
                'is_email_unique'   => 'The email address is already registered.',
                'is_from_domain' => 'Only emails from the domain @students.salle.url.edu, @ext.salle.url.edu or @salle.url.edu are accepted.',
                'max_length' => 'The email address must be less than 40 characters long.'
            ],
            'password' => [
                'required'   => 'The Password field is required.',
                'min_length' => 'The password must contain at least 8 characters.',
                'special_password_rule' => 'The password must contain both upper and lower case letters and numbers.',
                'max_length' => 'The password must be less than 40 characters long.'
            ],
            'repeat_password' => [
                'required'   => 'The Repeat Password field is required.',
                'matches'     => 'Passwords do not match.'
            ],
            'username' => [
                'max_length' => 'The username must be less than 20 characters long.'
            ]
        ];

        if ($this->validate($rules, $errors)) {
            $userModel = new UserModel();

            $username = $this->request->getPost('username');
            if(empty($username)) {
                $username = explode("@", $this->request->getPost('email'))[0];
            }

            $file = $this->request->getFile('profilePicture');
            $newName = $file->getRandomName();
            if (!$file->move(self::UPLOADS_DIR, $newName)) {
                session()->setFlashdata('errorImage', 'There was an error uploading your file.');
                return redirect()->back();
            }


            $data = [
                'email'    => $this->request->getPost('email'),
                'username' => $username,
                'profile_pic' => $newName,
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            ];

            if ($userModel->insert($data)) {
                return redirect()->to('/sign-up/success');
            } else {
                die("puto");
                return redirect()->back()->withInput()->with('errors', $userModel->errors());
            }
        } else {
            return view('signup_form');
        }

    }

    public function success()
    {

        helper(['form']);
        return view('signup_success');
    }
}