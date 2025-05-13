<?php

namespace App\Controllers;

class SignOut extends BaseController
{
    public function signOut()
    {
        $session = session();

        if($session->has('state')){
            $state = $session->get('state');
            if($state == 'LOGGED IN'){
                $session->destroy();
                return redirect()->to('/');
            }
        }
        return redirect()->to('/');
    }


}
