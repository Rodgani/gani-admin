<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScaffoldController extends Controller
{
    public function index()
    {
        return Inertia::render('scaffold/index');
    }
}
