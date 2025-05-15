<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;
use Psr\Http\Message\ResponseInterface;

class Profile extends BaseController
{
    private const UPLOADS_DIR = WRITEPATH . 'uploads/';
    private UserModel $userModel;

    public function __construct(){
        $this->userModel = new UserModel();
    }

    public function index(): string
    {
        $session = session();
        $userID = $session->get('user');
        $user = (object) $this->userModel->find($userID['id']);
        $data = [
            'user' => $user
        ];

        return view('profile', $data);
    }

    private function removeFiles(string $folderPath)
    {
        $files = array_diff(scandir($folderPath), ['.', '..']);

        foreach ($files as $file) {
            $fullPath = $folderPath . DIRECTORY_SEPARATOR . $file;
            if (is_dir($fullPath)) {
                $this->removeFiles($fullPath);
            } else {
                unlink($fullPath);
            }
        }

        rmdir($folderPath);
        return null;
    }

    private function delete(): RedirectResponse
    {
        $session = session();
        $userID = $session->get('user');

        $user = (object) $this->userModel->find($userID['id']);
        $this->userModel->delete($userID['id']);
        $folderPath = self::UPLOADS_DIR . $userID['id'];
        $this->removeFiles($folderPath);
        $session->destroy();

        session()->setFlashdata('success', 'The user data was removed successfully.');
        return redirect()->to(base_url(route_to('landing_view')));
    }

    private function modify(): string
    {
        helper(['form']);

        $rules = [
            'password'     => 'permit_empty|min_length[8]|special_password_rule|max_length[40]',
            'age' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[125]',
            'confirm_password'  => 'matches[password]',
            'username' => 'required|max_length[20]',
        ];

        $errors = [
            'password' => [
                'min_length' => 'The password must contain at least 8 characters.',
                'special_password_rule' => 'The password must contain both upper and lower case letters and numbers.',
                'max_length' => 'The password must be less than 40 characters long.'
            ],
            'age' => [
                'integer' => 'The age must be an integer.',
                'greater_than_equal_to' => 'The age must be greater or equal to 0.',
                'less_than_equal_to' => 'The age must be less than or equal to 125.'
            ],
            'confirm_password' => [
                'matches'     => 'Passwords do not match.'
            ],
            'username' => [
                'max_length' => 'The username must be less than 20 characters long.'
            ]
        ];

        if ($this->validate($rules, $errors)) {
            $session = session();
            $userID = $session->get('user');
            $user = (object) $this->userModel->find($userID['id']);

            $file = $this->request->getFile('profilePicture');
            if (!empty($file) && $file->getSize() !== 0) {
                $newName = $file->getRandomName();

                if (!empty($user->profile_pic)) {
                    $oldPath = self::UPLOADS_DIR . $userID['id'] . '/profile/' . $user->profile_pic;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                if (!$file->move(self::UPLOADS_DIR, $userID['id'] . '/profile/' . $newName)) {
                    session()->setFlashdata('error', 'There was an error uploading your file.');
                    return $this->index();
                }

                $user->profile_pic = $newName;
            }

            if ($this->request->getPost('age') != null) {
                $user->age = $this->request->getPost('age');
            }

            if ($this->request->getPost('password') != null) {
                $user->password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }

            $user->username = $this->request->getPost('username');

            if ($this->userModel->update($userID['id'], $user)) {
                session()->setFlashdata('success', 'The user data was updated successfully.');
            } else {
                session()->setFlashdata('error', 'There was an error updating the data.');
            }
        }

        return $this->index();
    }

    public function managePost():string|RedirectResponse
    {
        $action = $this->request->getPost('action');
        if ($action == 'delete') {
            return $this->delete();
        } else if ($action == 'save') {
            return $this->modify();
        } else {
            return $this->index();
        }
    }
}
