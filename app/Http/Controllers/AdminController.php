<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a table of contents in admin panel.
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $result = view('admin.index', []);

        return $result;
    }
}
