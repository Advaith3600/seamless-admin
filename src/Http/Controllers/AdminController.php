<?php

namespace Advaith\SeamlessAdmin\Http\Controllers;

class AdminController extends Controller
{
    public function __construct()
    {
        // only authenticated users will be able to use the admin panel
        $this->middleware('auth');
    }

    public function welcome()
    {
        $resolver = app('modelResolver');

        return view('seamless::welcome', [
            'resolver' => $resolver
        ]);
    }
}
