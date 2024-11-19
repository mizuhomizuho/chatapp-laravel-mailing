<?php

namespace App\Http\Controllers\Mailing;

class CreateController extends BaseController
{
    public function __invoke()
    {
        return view('mailing.create');
    }
}
