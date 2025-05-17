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
                ->with('error', lang('Validation.bad_intents_file'))
                ->withInput();
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}