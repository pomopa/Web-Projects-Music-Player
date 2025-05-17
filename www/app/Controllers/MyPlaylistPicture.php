<?php

namespace App\Controllers;

use App\Models\PlaylistModel;
use CodeIgniter\HTTP\ResponseInterface;

class MyPlaylistPicture extends BaseController
{
    private $playlistModel;
    private const UPLOADS_DIR = WRITEPATH . 'uploads/';

    public function __construct(){
        $this->playlistModel = new playlistModel();
    }

    public function playlistImage(int $playlistID): ResponseInterface
    {
        $session = session();
        $userID = $session->get('user');

        if (!$this->playlistModel->where('id', $playlistID)->where('user_id', $userID['id'])->first()) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'The provided playlist does not exist or does not belong to the current user.'
            ]);
        }

        $cover = $this->playlistModel->where('id', $playlistID)->first()['cover'];

        $path = self::UPLOADS_DIR . $userID['id'] . '/playlists/' . $cover;

        $mime = mime_content_type($path);

        return response()
            ->setHeader('Content-Type', $mime)
            ->setBody(file_get_contents($path));
    }
}