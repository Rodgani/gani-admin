<?php

namespace App\Http\Controllers;

use App\Http\Requests\Scaffold\ScaffoldGenerateRequest;
use App\Services\ScaffoldService;
use Inertia\Inertia;

class ScaffoldController extends Controller
{
    public function __construct(protected ScaffoldService $service) {}
    public function index()
    {
        return Inertia::render('scaffold/index');
    }

    public function generate(ScaffoldGenerateRequest $request)
    {
        $this->service->generate($request);
        return back();
    }
}
