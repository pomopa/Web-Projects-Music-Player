<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoggedFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $currentPath = $request->getPath();
        $session = session();
        if(!$session->has('state')){
            if ($currentPath !== '') {
                return redirect()->to('/');
            }
        }
        $state = $session->get('state');
        if($state != 'LOGGED IN'){
            if ($currentPath !== '') {
                return redirect()->to('/');
            }
        } else {
            if ($currentPath !== 'home') {
                return redirect()->to('/home');
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}
