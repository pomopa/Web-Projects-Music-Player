<?php

namespace App\Entities;

class TrackEntity
{
    public int $id;
    public string $name;
    public string $cover;
    public string $artistName;
    public int $artistId;
    public string $albumName;
    public int $albumId;
    public int $duration;
    public string $playerUrl;
    public string $releasedate;
    public string $license_ccurl;
    public ?string $artist_image;
    public array $playlists;

    public function __construct(
        int $id,
        string $name,
        string $cover,
        string $artistName,
        int $artistId,
        string $albumName,
        int $albumId,
        int $duration,
        string $playerUrl,
        string $releasedate,
        string $license_ccurl
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->cover = $cover;
        $this->artistName = $artistName;
        $this->artistId = $artistId;
        $this->albumName = $albumName;
        $this->albumId = $albumId;
        $this->duration = $duration;
        $this->playerUrl = $playerUrl;
        $this->releasedate = $releasedate;
        $this->license_ccurl = $license_ccurl;
    }

}
