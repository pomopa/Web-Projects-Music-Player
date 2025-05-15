<?php

namespace App\Models;

use CodeIgniter\Model;

class TrackModel extends Model
{
    protected $table            = 'tracks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'id',
        'name',
        'cover',
        'artist_id',
        'artist_name',
        'album_id',
        'album_name',
        'duration',
        'player_url',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id'           => 'required|alpha_numeric|max_length[255]',
        'name'         => 'required|string|max_length[255]',
        'cover'        => 'required|valid_url|max_length[255]',
        'artist_id'    => 'required|integer',
        'artist_name'  => 'required|string|max_length[255]',
        'album_id'     => 'required|integer',
        'album_name'   => 'required|string|max_length[255]',
        'duration'     => 'required|integer|greater_than[0]',
        'player_url'   => 'required|valid_url|max_length[255]',
    ];

    protected $validationMessages = [
        'id' => [
            'required'      => 'The ID field is required.',
            'alpha_numeric' => 'The ID may only contain letters and numbers.',
            'max_length'    => 'The ID must not exceed 255 characters.'
        ],
        'name' => [
            'required'   => 'The track name is required.',
            'string'     => 'The track name must be a string.',
            'max_length' => 'The track name must not exceed 255 characters.'
        ],
        'cover' => [
            'required'   => 'The album name is required.',
            'valid_url'  => 'The cover URL must be valid.',
            'max_length' => 'The cover URL must not exceed 255 characters.'
        ],
        'artist_id' => [
            'required' => 'The artist_id field is required.',
            'integer'  => 'The artist_id must be an integer.'
        ],
        'artist_name' => [
            'required'   => 'The artist name is required.',
            'string'     => 'The artist name must be a string.',
            'max_length' => 'The artist name must not exceed 255 characters.'
        ],
        'album_id' => [
            'required' => 'The album_id field is required.',
            'integer'  => 'The album_id must be an integer.'
        ],
        'album_name' => [
            'required'   => 'The album name is required.',
            'string'     => 'The album name must be a string.',
            'max_length' => 'The album name must not exceed 255 characters.'
        ],
        'duration' => [
            'required'     => 'The duration is required.',
            'integer'      => 'The duration must be an integer.',
            'greater_than' => 'The duration must be greater than 0 seconds.'
        ],
        'player_url' => [
            'required'   => 'The player URL is required.',
            'valid_url'  => 'The player URL must be valid.',
            'max_length' => 'The player URL must not exceed 255 characters.'
        ]
    ];

}
