<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use function PHPUnit\Framework\isNull;

class SignUp extends BaseController
{
    private const UPLOADS_DIR = WRITEPATH . 'uploads/';

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
                'required'    => lang('Validation.required', ['email']),
                'valid_email' => lang('Validation.valid_email'),
                'is_email_unique'   => lang('Validation.unique_email'),
                'is_from_domain' => lang('Validation.is_from_domain'),
                'max_length' => lang('Validation.max_length', ['email', '60'])
            ],
            'password' => [
                'required'   => lang('Validation.required', [lang('App.password')]),
                'min_length' => lang('Validation.min_length'),
                'special_password_rule' => lang('Validation.special_password_rule'),
                'max_length' => lang('Validation.max_length', [lang('App.password'), '40']),
            ],
            'repeat_password' => [
                'required'   => lang('Validation.required', [lang('App.repeat_password')]),
                'matches'     => lang('Validation.matches'),
            ],
            'username' => [
                'max_length' => lang('Validation.max_length', [lang('App.username'), '20']),
            ]
        ];

        if ($this->validate($rules, $errors)) {
            $userModel = new UserModel();

            $username = $this->request->getPost('username');
            if (empty($username)) {
                $username = explode("@", $this->request->getPost('email'))[0];
            }

            $data = [
                'email'    => $this->request->getPost('email'),
                'username' => $username,
                'profile_pic' => '', // provisionalment buit
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            ];

            if ($userModel->insert($data)) {
                $userID = $userModel->getInsertID();

                $profilePath = $userID . '/profile/';
                $basePath = self::UPLOADS_DIR . $userID . '/';
                $fullPathProfile = self::UPLOADS_DIR . $profilePath;
                $fullPathPlaylists = self::UPLOADS_DIR . $userID . '/playlists/';

                mkdir($basePath, 0755, true);
                mkdir($fullPathPlaylists, 0755, true);
                mkdir($fullPathProfile, 0755, true);

                $file = $this->request->getFile('picture');
                $newName = null;

                if (!empty($file) && $file->getSize() !== 0) {
                    $newName = $file->getRandomName();

                    if (!$file->move(self::UPLOADS_DIR, $profilePath . $newName)) {
                        session()->setFlashdata('errorImage', lang('Validation.error_uploading'));
                        return redirect()->back();
                    }

                    $userModel->update($userID, ['profile_pic' => $newName]);
                }
                return redirect()->to(base_url(route_to('sign-up_success')));
            } else {
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