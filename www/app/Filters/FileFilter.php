<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class FileFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $files = $request->getFiles();

        if (!empty($files)) {
            return redirect()->back()
                ->with('error', 'Well, well, well... look who just tried to be clever. You must be real proud of yourself.')
                ->withInput();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}