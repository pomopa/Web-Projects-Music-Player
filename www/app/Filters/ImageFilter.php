<?php

namespace App\Filters;

use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ImageFilter implements FilterInterface
{
    private const MAX_FILE_SIZE = 2048000;

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

        if ($file instanceof UploadedFile) {
            return null;
        }

        // Aquesta comprovació no acostuma a saltar però comprova que el fitxer s'hagi penjat correctament. No treure.
        if (!$file->isValid()) {
            return redirect()->back()
                ->with('errorImage', 'The file could not be uploaded, try again later.')
                ->withInput();
        }

        if ($file->getSize() > self::MAX_FILE_SIZE) {
            return redirect()->back()
                ->with('errorImage', "The provided file is too big (maximum size allowed: 2MB)")
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