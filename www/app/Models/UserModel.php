<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'email',
        'password',
        'username',
        'profile_pic',
        'age',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $returnType    = 'array';

    protected $validationRules = [
        'email'    => 'required|valid_email|max_length[255]',
        'password' => 'required|min_length[8]|max_length[255]',
        'username' => 'permit_empty|max_length[255]',
        'age'      => 'permit_empty|is_natural',
        'profile_pic' => 'permit_empty|max_length[255]',
    ];
}