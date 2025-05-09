<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    public function index(): string
    {
        $userModel = new UserModel();
        $session = session();
        $userID = $session->get('user');
        $user = (object) $userModel->find($userID['id']);
        $data = [
            'user' => $user
        ];

        return view('profile', $data);
    }

    private function delete(): string
    {

    }

    private function modify(): string
    {
        helper(['form']);
        $rules = [
            'email'        => 'required|valid_email|is_from_domain|max_length[40]|is_email_unique',
            'password'     => 'required|min_length[8]|special_password_rule|max_length[40]',
            'age' => 'required',
            'confirm_password'  => 'required|matches[password]',
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
            'confirm_password' => [
                'required'   => 'The Repeat Password field is required.',
                'matches'     => 'Passwords do not match.'
            ],
            'username' => [
                'max_length' => 'The username must be less than 20 characters long.'
            ]
        ];

        if ($this->validate($rules, $errors)) {

        } else {
            return $this->index();
        }
    }

    public function managePost(): string
    {
        $action = $this->request->getPost('action');
        if ($action == 'delete') {
            return $this->delete();
        } else if ($action == 'save') {
            return $this->modify();
        } else {
            return $this->index();
            redirect()->back()->withInput()->with('error', "Error in the server petition.");
        }
    }
}
