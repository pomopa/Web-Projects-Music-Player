<?php

namespace App\Models;

use CodeIgniter\Model;

class TrackPlaylistModel extends Model
{
    protected $table            = 'track_playlist';
    protected $primaryKey       = null;
    protected $returnType       = 'array';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields    = [
        'track_id',
        'playlist_id',
        'created_at',
        'updated_at',
        ];

    protected $validationRules    = [
        'track_id'    => 'required|alpha_numeric|max_length[255]',
        'playlist_id' => 'required|is_natural_no_zero',
    ];

    protected $validationMessages = [
        'track_id' => [
            'required'      => 'Track ID is required.',
            'alpha_numeric' => 'Track ID must be alphanumeric.',
            'max_length'    => 'Track ID must be at most 255 characters.',
        ],
        'playlist_id' => [
            'required'           => 'Playlist ID is required.',
            'is_natural_no_zero' => 'Playlist ID must be a positive integer.',
        ],
    ];
}
