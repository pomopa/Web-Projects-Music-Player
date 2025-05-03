<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoggedFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if(!$session->has('state')){
            $session->setFlashdata('error_message', 'You must be logged in to access the shopping cart.');
            return redirect()->to('/sign-in');
        }
        $state = $session->get('state');
        if($state != 'LOGGED IN'){
            $session->setFlashdata('error_message', 'You must be logged in to access the shopping cart.');
            return redirect()->to('/sign-in');
        }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}
