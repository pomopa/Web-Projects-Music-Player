<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ImageFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $files = $request->getFiles();

        if (empty($files)) {
            return null;
        }

        if (count($files) > 1) {
            return redirect()->back()
                ->with('errorImage', 'Only one profile image file is allowed.')
                ->withInput();
        }

        $file = $request->getFile('profilePicture');
        // Aquesta comprovació no acostuma a saltar però comprova que el fitxer s'hagi penjat correctament. No treure.
        if (!$file->isValid()) {
            return redirect()->back()
                ->with('errorImage', 'The file could not be uploaded, try again later.')
                ->withInput();
        }

        if (strpos($file->getMimeType(), 'image/') !== 0) {
            return redirect()->back()
                ->with('errorImage', 'The uploaded file must be an image file.')
                ->withInput();
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}