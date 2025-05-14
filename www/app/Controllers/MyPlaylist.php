<?php

namespace App\Controllers;

class MyPlaylist extends BaseController
{
    public function generalView() {
        $playlist = $this->getPlaylist($id);

        $playlist->tracks = $this->getTracks($playlist->id);

        $playlistId = $playlist->id;
        $playlistName = $playlist->name ?? 'Playlist Not Found';
        $tracks = $this->getTracks($playlist->id);
        $playlist->tracks = $tracks;

        // Utilitza la imatge del primer track, si existeix
        $playlistImage = !empty($tracks) ? $tracks[0]->album_image : '/assets/img/default-cover.png';

        $playlistCreator = $playlist->user_name ?? 'Unknown Creator';
        $playlistCreatorId = $playlist->user_id ?? 0;
        $creationDate = date('Y-m-d', strtotime($playlist->creationdate ?? 'now'));

        $totalDuration = 0;
        foreach ($playlist->tracks as $track) {
            $totalDuration += $track->duration;
        }

        $formattedTotalDuration = gmdate("H:i:s", $totalDuration);
        $tracksCount = count($playlist->tracks);

        $session = session();
        $userSession = $session->get('user');
        $currentUserId = $userSession['id'] ?? null;

        return view('my_playlists_general', [
            'playlist' => $playlist,
            'playlistId' => $playlistId,
            'playlistName' => $playlistName,
            'playlistImage' => $playlistImage,
            'playlistCreator' => $playlistCreator,
            'playlistCreatorId' => $playlistCreatorId,
            'creationDate' => $creationDate,
            'tracks' => $playlist->tracks,
            'formattedTotalDuration' => $formattedTotalDuration,
            'tracksCount' => $tracksCount,
            'isOwner' => $isOwner
        ]);
    }

    public function putTrack(int $songID, int $playlistID) {

    }

    public function putPlaylist(int $playlistID) {

    }

    public function deleteTrack(int $trackID, int $playlistID) {

    }

    public function deletePlaylist(int $playlistID) {

    }
}