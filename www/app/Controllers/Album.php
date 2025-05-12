<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Album extends BaseController
{
    public function index(){
        return view('albums');
    }
}