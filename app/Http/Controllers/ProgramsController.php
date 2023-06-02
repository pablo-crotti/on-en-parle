<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgramsController extends Controller
{

    public function index()
    {
        return Inertia::render('Admin/Programs/container');
    }
}
