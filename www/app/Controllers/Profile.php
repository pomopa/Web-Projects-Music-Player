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
}
