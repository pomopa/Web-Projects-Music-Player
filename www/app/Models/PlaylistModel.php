<?php

namespace App\Models;

use CodeIgniter\Model;

class PlaylistModel extends Model
{
    protected $table            = 'playlists';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'name',
        'cover',
        'user_id',
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules  = [
        'name'     => 'required|string|max_length[255]',
        'cover'    => 'permit_empty|valid_url|max_length[255]',
        'user_id'  => 'required|integer',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'The playlist name is required.',
            'string'     => 'The playlist name must be a string.',
            'max_length' => 'The playlist name cannot exceed 255 characters.'
        ],
        'cover' => [
            'valid_url'  => 'The cover must be a valid URL.',
            'max_length' => 'The cover URL cannot exceed 255 characters.'
        ],
        'user_id' => [
            'required' => 'The user_id is required.',
            'integer'  => 'The user_id must be an integer.'
        ]
    ];
}
