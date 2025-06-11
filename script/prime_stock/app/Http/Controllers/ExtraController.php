<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ExtraController extends Controller
{
    /**
     * Cache Clear
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        return redirect()->back()->with('success', 'Cache Cleared Successfully!');
    }

    /**
     * Server Information
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function server()
    {
        return view('extra.server');
    }

    /**
     * Application Information
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function application()
    {
        return view('extra.application');
    }
}
