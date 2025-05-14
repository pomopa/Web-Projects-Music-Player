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
                return redirect()->to(base_url(route_to('landing_view')));
            }
        }
        return redirect()->to(base_url(route_to('landing_view')));
    }


}
