<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class NotLoggedFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if($session->has('state')){
            $state = $session->get('state');
            if($state != 'LOGGED IN'){
                $session->setFlashdata('error_message', 'You must be logged in to access the previous page.');
                return redirect()->to(base_url(route_to('landing_view')));
            }
        } else {
            $session->setFlashdata('error_message', 'You must be logged in to access the previous page.');
            return redirect()->to(base_url(route_to('landing_view')));
        }
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}
