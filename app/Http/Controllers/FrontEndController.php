<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\View\View;

class FrontEndController extends Controller
{
    public function show(): View
    {
        return view('front-end.index');
    }
}
