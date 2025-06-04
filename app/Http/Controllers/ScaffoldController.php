<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Scaffold\ScaffoldGenerateRequest;
use App\Services\ScaffoldService;
use Inertia\Inertia;

final class ScaffoldController extends Controller
{
    public function __construct(protected ScaffoldService $service) {}
    public function index()
    {
        return Inertia::render('scaffold/index', [
            "fieldTypes" => config('scaffold.fields')
        ]);
    }

    public function generate(ScaffoldGenerateRequest $request)
    {
        $this->service->generate($request);
        return redirect()->route('scaffold.index');
    }
}
