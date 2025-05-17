<?php

namespace App\Controllers;

use App\Models\PlaylistModel;
use App\Models\TrackModel;
use App\Models\TrackPlaylistModel;
use GuzzleHttp\Client;
use function PHPUnit\Framework\isNull;

class MyPlaylist extends BaseController
{
    private TrackModel $trackModel;
    private PlaylistModel $playlistModel;
    private TrackPlaylistModel $trackPlaylistModel;
    private Client $client;
    private string $apiKey = "aab3b83e";
    private const UPLOADS_DIR = WRITEPATH . 'uploads/';

    public function __construct(){
        $this->trackModel = new TrackModel();
        $this->playlistModel = new PlaylistModel();
        $this->trackPlaylistModel = new TrackPlaylistModel();
        $this->client = new Client([
            'base_uri' => 'https://api.jamendo.com/v3.0/',
        ]);
    }

    private function checkIfPlaylistFromUser(int $playlistID): bool
    {
        $session = session();
        $userID = $session->get('user');
        if (!$this->playlistModel->where('id', $playlistID)->where('user_id', $userID['id'])->first()) {
            return false;
        }
        return true;
    }

    public function index() {
        return view('test');
    }

    public function viewPlaylist(int $playlistID) {

    }

    public function createPlaylist() {
        helper(['form']);
        $rules = [
            'name'        => 'required|max_length[255]',
        ];

        $errors = [
            'email' => [
                'required'    => 'The name field is required.',
                'max_length' => 'The name must be less than 255 characters long.'
            ]
        ];

        if ($this->validate($rules, $errors)) {
            $session = session();
            $userID = $session->get('user');

            $file = $this->request->getFile('picture');
            $newName = '';
            if (!empty($file) && $file->getSize() !== 0) {
                $newName = $file->getRandomName();
                if (!$file->move(self::UPLOADS_DIR, $userID['id'] . '/playlists/' . $newName)) {
                    session()->setFlashdata('errorImage', 'There was an error uploading your file.');
                    return redirect()->back();
                }
            }

            $data = [
                'name'    => $this->request->getPost('name'),
                'cover' => $newName,
                'user_id' => $userID['id'],
            ];

            if ($this->playlistModel->insert($data)) {
                return redirect()->to(base_url(route_to('my-playlist_view')));
            } else {
                return redirect()->back()->withInput()->with('errors', $this->playlistModel->errors());
            }
        } else {
            return redirect()->back();
        }
    }

    public function putTrack(int $playlistID, string $trackID) {
        if ($this->trackPlaylistModel->where('playlist_id', $playlistID)->where('track_id', $trackID)->first()) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'This playlist with this track already exist in the system.'
            ]);
        }

        if (!$this->checkIfPlaylistFromUser($playlistID)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'The provided playlist does not exist or does not belong to the current user.'
            ]);
        }

        if (!$this->trackModel->find($trackID)) {
            try {
                $response = $this->client->request('GET', 'tracks', [
                    'query' => [
                        'client_id' => $this->apiKey,
                        'format'    => 'json',
                        'limit'     => 10,
                        'id'     => $trackID
                    ]
                ]);
                $data = json_decode($response->getBody(), true);

                if (empty($data['results'])) {
                    return $this->response->setStatusCode(404)->setJSON([
                        'status' => 'error',
                        'message' => 'The given track id does not exist in our system.'
                    ]);
                }

                $trackData = $data['results'][0];

                $newTrack = [
                    'id' => $trackData['id'],
                    'name' => $trackData['name'],
                    'cover' => $trackData['image'],
                    'artist_id' => $trackData['artist_id'],
                    'artist_name' => $trackData['artist_name'],
                    'album_id' => $trackData['album_id'],
                    'album_name' => $trackData['album_name'],
                    'duration' => $trackData['duration'],
                    'player_url' => $trackData['audio'],
                ];

                if (!$this->trackModel->insert($newTrack)) {
                    return $this->response->setStatusCode(500)->setJSON([
                        'status' => 'error',
                        'message' => 'Failed to save track.',
                        'errors' => $this->trackModel->errors()
                    ]);
                }

            } catch (\Exception $e) {
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 'error',
                    'message' => 'Connection error.'
                ]);
            }
        }

        $this->trackPlaylistModel->protect(false)->insert(['playlist_id' => $playlistID, 'track_id' => $trackID]);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Track added successfully to the playlist.'
        ]);
    }

    public function putPlaylist(int $playlistID)
    {
        if (!$this->checkIfPlaylistFromUser($playlistID)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'The provided playlist does not exist or does not belong to the current user.'
            ]);
        }

        $data = $this->request->getJSON(true);

        if (empty($data['name']) && empty($data['picture'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'No data provided to update.'
            ]);
        }

        $updateData = $this->playlistModel->find($playlistID);
        if (!empty($data['name'])) {
            $updateData['name'] = $data['name'];
        }
//        if (!empty($data['picture'])) {
//            $updateData['picture'] = $data['picture'];
//        }  TODO Possiblement s'ha de trobar alguna manera de canviar la imatge

        if (!$this->playlistModel->update($playlistID, $updateData)) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Failed to update the playlist.'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Playlist updated successfully.',
            'data'    => $updateData
        ]);
    }


    public function deleteTrack(int $playlistID, string $trackID) {
        if (!$this->trackPlaylistModel->where('playlist_id', $playlistID)->where('track_id', $trackID)->first()) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'A playlist with this track does not exist in the system.'
            ]);
        }

        if (!$this->checkIfPlaylistFromUser($playlistID)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'The provided playlist does not exist or does not belong to the current user.'
            ]);
        }

        if (!$this->trackPlaylistModel->where('playlist_id', $playlistID)->where('track_id', $trackID)->delete()) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'The track could not be deleted from the playlist.',
                'errors'  => $this->trackModel->errors()
            ]);
        }

        $otherAssociations = $this->trackPlaylistModel
            ->where('track_id', $trackID)
            ->where('playlist_id !=', $playlistID)
            ->countAllResults();

        if ($otherAssociations === 0) {
            $this->trackModel->delete($trackID);
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

        if (!$this->checkIfPlaylistFromUser($playlistID)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'The provided playlist does not exist or does not belong to the current user.'
            ]);
        }

        $session = session();
        $userID = $session->get('user');

        $tracks = $this->trackPlaylistModel
            ->where('playlist_id', $playlistID)
            ->findAll();

        foreach ($tracks as $track) {
            $trackID = $track['track_id'];

            $otherAssociations = $this->trackPlaylistModel
                ->where('track_id', $trackID)
                ->where('playlist_id !=', $playlistID)
                ->countAllResults();

            if ($otherAssociations === 0) {
                $this->trackModel->delete($trackID);
            }
        }

        $this->trackPlaylistModel->where('playlist_id', $playlistID)->delete();

        $playlist = $this->playlistModel->find($playlistID);
        if (!$this->playlistModel->delete($playlistID)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'The playlist could not be deleted.',
                'errors'  => $this->playlistModel->errors()
            ]);
        }

        $oldPath = self::UPLOADS_DIR . $userID['id'] . '/playlists/' . $playlist['cover'];
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Playlist deleted successfully.'
        ]);
    }
}