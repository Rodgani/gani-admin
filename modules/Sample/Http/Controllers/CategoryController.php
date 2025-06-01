<?php

namespace Modules\Sample\Http\Controllers;

use Modules\Controller;
use Illuminate\Http\Request;
use Modules\Sample\Repositories\CategoryRepository;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Modules\Sample\Http\Requests\Categories\CategoryIndexRequest;
use Modules\Sample\Http\Requests\Categories\CategoryCreateRequest;
use Modules\Sample\Http\Requests\Categories\CategoryUpdateRequest;
use Modules\Sample\Http\Requests\Categories\CategoryDestroyRequest;

class CategoryController extends Controller
{
    public function __construct(private CategoryRepository $repository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(CategoryIndexRequest $request): Response
    {
        $categories = $this->repository->categories($request->validatedObject());
        return Inertia::render("sample/category/index", [
            "categories" => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryCreateRequest $request): RedirectResponse
    {
        $this->repository->storeCategory($request->validated());
        return redirect()->route('categories.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->repository->updateCategory($validated['id'], $request->validated());
        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryDestroyRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->repository->destroyCategory($validated['id']);
        return redirect()->route('categories.index');
    }
}
