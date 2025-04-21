<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $session = session();

        if($session->has('state')){
            $state = $session->get('state');
            if($state == 'LOGGED IN'){
                return view('home_loggedin');
            }
        }
        return view('home_not_loggedin');
    }
}
