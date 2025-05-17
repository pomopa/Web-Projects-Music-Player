<?php

namespace App\Controllers;

use App\Models\PlaylistModel;
use App\Models\TrackModel;
use App\Models\TrackPlaylistModel;
use GuzzleHttp\Client;
use App\Models\UserModel;
use function PHPUnit\Framework\isNull;

class MyPlaylist extends BaseController
{
    private TrackModel $trackModel;
    private PlaylistModel $playlistModel;
    private TrackPlaylistModel $trackPlaylistModel;
    private Client $client;
    private string $apiKey = "aab3b83e";
    private const UPLOADS_DIR = WRITEPATH . 'uploads/';

    public function __construct()
    {
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
        $session = session();
        $userSession = $session->get('user');
        $currentUserId = $userSession['id'] ?? null;

        $myPlaylists = $this->playlistModel
            ->where('user_id', $currentUserId)
            ->findAll();

        $trackModel = new TrackModel();
        $trackPlaylistModel = new TrackPlaylistModel();

        $totalDuration = 0;

        foreach ($myPlaylists as &$playlist) {
            $trackIds = $trackPlaylistModel
                ->select('track_id')
                ->where('playlist_id', $playlist['id'])
                ->findColumn('track_id');

            $tracks = !empty($trackIds)
                ? $trackModel->whereIn('id', $trackIds)->findAll()
                : [];

            $duration = 0;
            foreach ($tracks as $track) {
                $duration += $track['duration'];
            }

            $playlist['tracks'] = $tracks;
            $playlist['duration'] = $duration;
            $totalDuration += $duration;
        }
        $userModel = new UserModel();
        $user = $userModel->find($currentUserId);
        $username = $user['username'] ?? 'Usuario';

        return view('my_playlists_general', [
            'myPlaylists' => $myPlaylists,
            'user' => $user,
            'username' => $username,
            'totalDuration' => $totalDuration,
        ]);
    }
    public function viewPlaylist($playlistId) {
        $session = session();
        $userSession = $session->get('user');
        $currentUserId = $userSession['id'] ?? null;

        $playlist = $this->playlistModel->find($playlistId);

        if (!$playlist) {
            return redirect()->to(base_url(route_to('my-playlist_view')))->with('error', 'Playlist not found.');
        }

        $userModel = new UserModel();
        $creator = $userModel->find($playlist['user_id']);
        $playlistCreator = $creator['username'] ?? 'Unknown';
        $playlistCreatorId = $creator['id'] ?? 0;

        $creationDate = isset($playlist['created_at'])
            ? date('Y-m-d', strtotime($playlist['created_at']))
            : 'Unknown';

        $trackPlaylistModel = new TrackPlaylistModel();
        $trackModel = new TrackModel();

        $trackIds = $trackPlaylistModel
            ->select('track_id')
            ->where('playlist_id', $playlistId)
            ->findColumn('track_id');

        $tracks = !empty($trackIds)
            ? $trackModel->whereIn('id', $trackIds)->findAll()
            : [];

        $totalDuration = 0;
        foreach ($tracks as $track) {
            $totalDuration += $track['duration'];
        }
        $formattedTotalDuration = gmdate("H:i:s", $totalDuration);
        $tracksCount = count($tracks);

        $isOwner = $currentUserId === $playlist['user_id'];

        $playlist['tracks'] = $tracks;

        return view('my_playlists_specific', [
            'playlist' => $playlist,
            'playlistId' => $playlist['id'],
            'playlistName' => $playlist['name'],
            'playlistCreator' => $playlistCreator,
            'playlistCreatorId' => $playlistCreatorId,
            'creationDate' => $creationDate,
            'tracks' => $playlist['tracks'],
            'formattedTotalDuration' => $formattedTotalDuration,
            'tracksCount' => $tracksCount,
            'isOwner' => $isOwner
        ]);
    }

    public function createPlaylistView() {
        return View('create_playlist');
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
                'message' => 'A playlist with this track already exist in the system.'
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
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'The provided playlist does not exist or does not belong to the current user.'
            ]);
        }

        $updateData = [];

        // nom
        if ($this->request->getHeaderLine('Content-Type') === 'application/json') {
            $json = $this->request->getJSON(true);
            if (!empty($json['name'])) {
                $updateData['name'] = trim($json['name']);
            }
        }

        /*$file = $this->request->getFile('picture');


        // Imatge
        if ($file->getSize() !== 0) {
            if ($file->isValid() && !$file->hasMoved()) {
                $userId = session('user')['id'];
                $newName = $file->getRandomName();
                $uploadPath = WRITEPATH . 'uploads/' . $userId . '/playlists/';

                // eliminar anterior imatge
                $existing = $this->playlistModel->find($playlistID);
                if (!empty($existing['cover'])) {
                    $oldPath = $uploadPath . $existing['cover'];
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $file->move($uploadPath, $newName);
                $updateData['cover'] = $newName;
            }
        }*/

        if (empty($updateData)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'No valid data to update.'
            ]);
        }

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