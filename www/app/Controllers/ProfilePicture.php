<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProfilePicture extends BaseController
{
    private UserModel $userModel;
    private const UPLOADS_DIR = WRITEPATH . 'uploads';

    public function __construct(){
        $this->userModel = new UserModel();
    }

    public function profileImage(): ResponseInterface
    {
        $session = session();
        $userID = $session->get('user');
        $user = (object) $this->userModel->find($userID['id']);

        $path = self::UPLOADS_DIR . '/' . $user->profile_pic;

        $mime = mime_content_type($path);

        return response()
            ->setHeader('Content-Type', $mime)
            ->setBody(file_get_contents($path));
    }
}