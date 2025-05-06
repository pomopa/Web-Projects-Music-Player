<?php

namespace App\Controllers;

class LandingPage extends BaseController
{
    public function landingPage(): string
    {
        return view('landing');
    }
}
