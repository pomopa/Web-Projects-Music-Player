<?php

namespace App\Controllers;

class LandingPage extends BaseController
{
    public function landingPage(): string
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
