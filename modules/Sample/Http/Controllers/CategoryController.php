<?php

namespace Modules\Sample\Http\Controllers;

use Modules\Controller;
use Illuminate\Http\Request;
use Modules\Sample\Repositories\CategoryRepository;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function __construct(private CategoryRepository $repository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = $this->repository->categories($request);
        return Inertia::render("sample/categories/index", [
            "categories" => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->repository->storeCategory($request->validated());
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function find(int $id,Request $request)
    {
        $request->validated();
        $this->repository->findCategory($id);
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, Request $request)
    {
        $this->repository->updateCategory($id, $request->validated());
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, Request $request)
    {
        $request->validated();
        $this->repository->destroyCategory($id);
        return back();
    }
}
