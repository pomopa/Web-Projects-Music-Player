<?php

namespace App\Controllers;

use Carbon\Carbon;

class LandingPage extends BaseController
{
    public function landingPage(): string
    {
        date_default_timezone_set('Europe/Madrid');
        $now = Carbon::now()->format('d/m/Y H:i');

        return view('landing', ['now' => $now]);
    }
}
