<?php

namespace App\Controllers;

use App\Models\PlaylistModel;
use App\Models\TrackModel;
use App\Models\TrackPlaylistModel;

class MyPlaylist extends BaseController
{
    private TrackModel $trackModel;
    private PlaylistModel $playlistModel;
    private TrackPlaylistModel $trackPlaylistModel;

    public function __construct(){
        $this->trackModel = new TrackModel();
        $this->playlistModel = new PlaylistModel();
        $this->trackPlaylistModel = new TrackPlaylistModel();
    }

    public function index() {

    }

    public function putTrack(string $trackID, int $playlistID) {
        //TODO Afegir la canço a la BBDD si no existeix
        if ($this->trackPlaylistModel->where('playlist_id', $playlistID)->where('track_id', $trackID)->first()) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'A playlist with this track already exist in the system.'
            ]);
        }

        if (!$this->trackModel->find($trackID) || !$this->playlistModel->find($playlistID)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'The provided playlist or track ID does not exist in the system.'
            ]);
        }

        if (!$this->trackPlaylistModel->insert(['playlist_id' => $playlistID, 'track_id' => $trackID])) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'The track could not be added to the playlist.'
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Track added successfully from the playlist.'
        ]);
    }

    public function putPlaylist(int $playlistID) {

    }

    public function deleteTrack(string $trackID, int $playlistID) {
        if (!$this->trackPlaylistModel->where('playlist_id', $playlistID)->where('track_id', $trackID)->first()) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'A playlist with this track does not exist in the system.'
            ]);
        }

        if (!$this->trackPlaylistModel->where('playlist_id', $playlistID)->where('track_id', $trackID)->delete()) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'The track could not be deleted from the playlist.',
                'errors'  => $this->trackModel->errors()
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Track deleted successfully from the playlist.'
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