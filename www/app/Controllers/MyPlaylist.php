<?php

namespace App\Controllers;

use App\Models\PlaylistModel;
use App\Models\TrackModel;

class MyPlaylist extends BaseController
{
    private TrackModel $trackModel;
    private PlaylistModel $playlistModel;

    public function __construct(){
        $this->trackModel = new TrackModel();
        $this->playlistModel = new PlaylistModel();
    }

    public function index() {

    }

    public function putTrack(string $trackID, int $playlistID) {

    }

    public function putPlaylist(int $playlistID) {

    }

    public function deleteTrack(string $trackID, int $playlistID) {
        $track = $this->trackModel
            ->where('id', $trackID)
            ->where('playlist_id', $playlistID);

        if (!$track || !$this->playlistModel->find($playlistID)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Playlist or track does not exist in the system.'
            ]);
        }

        if (!$this->trackModel->delete($trackID)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'The track could not be deleted.',
                'errors'  => $this->trackModel->errors()
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Track deleted successfully.'
        ]);
    }

    public function deletePlaylist(int $playlistID) {
        if (!$this->playlistModel->find($playlistID)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Playlist does not exists.'
            ]);
        }

        $this->trackModel->where('playlist_id', $playlistID)->delete();

        if (!$this->playlistModel->delete($playlistID)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'The playlist could not be deleted.',
                'errors'  => $this->playlistModel->errors()
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Playlist deleted successfully.'
        ]);
    }
}