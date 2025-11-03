<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserComposer
{
    public function compose(View $view): void
    {
        $view->with('currentUser', Auth::user());
    }
}
