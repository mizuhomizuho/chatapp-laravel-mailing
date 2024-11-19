<?php

namespace App\Http\Controllers\Mailing;


use App\Models\Mailing;

class IndexController extends BaseController
{
    public function __invoke()
    {
        $list = Mailing::orderBy('id', 'desc')->paginate(8);
        return view('mailing.index', compact('list'));
    }
}
