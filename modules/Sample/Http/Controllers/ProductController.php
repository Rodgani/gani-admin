<?php

namespace Modules\Sample\Http\Controllers;

use Modules\Controller;
use Illuminate\Http\Request;
use Modules\Sample\Repositories\ProductRepository;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(private ProductRepository $repository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = $this->repository->products($request);
        return Inertia::render("sample/products/index", [
            "products" => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->repository->storeProduct($request->validated());
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function find(int $id,Request $request)
    {
        $request->validated();
        $this->repository->findProduct($id);
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, Request $request)
    {
        $this->repository->updateProduct($id, $request->validated());
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, Request $request)
    {
        $request->validated();
        $this->repository->destroyProduct($id);
        return back();
    }
}
