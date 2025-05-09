<?php

namespace Modules\Sample\Repositories;
use App\Helpers\PaginationHelper;
use Modules\Sample\Models\Category;
class CategoryRepository
{
    public function __construct(private Category $model){}
    /**
     * Display a listing of the resource.
     */
    public function categories($request)
    {
         $option = PaginationHelper::pageQueryOptions($request);
         return $this->model->orderBy($option->column, $option->direction)
            ->paginate($option->perPage);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCategory($request)
    {
        return $this->model->create($request);
    }

    /**
     * Display the specified resource.
     */
    public function findCategory(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCategory(int $id, $request)
    {
        return $this->model->where('id',$id)->update($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyCategory(int $id)
    {
        return $this->model->findOrFail($id)->delete();
    }
}
