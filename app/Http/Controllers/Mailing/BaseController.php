<?php

namespace App\Http\Controllers\Mailing;

use App\Http\Controllers\Controller;
use App\Services\Mailing\Service;

class BaseController extends Controller
{
    public function __construct(
        protected Service $service,
    ) {}
}
