<?php

namespace Modules\Sample\Repositories;
use App\Helpers\PaginationHelper;
use Modules\Sample\Models\Product;
class ProductRepository
{
    public function __construct(private Product $model){}
    /**
     * Display a listing of the resource.
     */
    public function products($request)
    {
         $option = PaginationHelper::pageQueryOptions($request);
         return $this->model->orderBy($option->column, $option->direction)
            ->paginate($option->perPage);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeProduct($request)
    {
        return $this->model->create($request);
    }

    /**
     * Display the specified resource.
     */
    public function findProduct(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateProduct(int $id, $request)
    {
        return $this->model->where('id',$id)->update($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyProduct(int $id)
    {
        return $this->model->findOrFail($id)->delete();
    }
}
