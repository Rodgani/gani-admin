<?php

namespace Modules\Sample\Repositories;

use App\Helpers\PaginationHelper;
use Modules\Sample\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class CategoryRepository
{
    /**
     * Display a listing of the resource.
     */
    public function categories(object $request): LengthAwarePaginator
    {
        $option = PaginationHelper::pageQueryOptions($request);

        return Category::orderBy($option->column, $option->direction)
            ->paginate($option->perPage);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCategory(array $request): Category
    {
        return Category::create($request);
    }

    /**
     * Display the specified resource.
     */
    public function findCategory(int $id): ?Category
    {
        try {
            return Category::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::warning("Category not found for ID {$id}", ['exception' => $e]);
            return null;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCategory(int $id, array $request): bool
    {
        try {
            $model = Category::findOrFail($id);
            return $model->update($request);
        } catch (ModelNotFoundException $e) {
            Log::warning("Failed to update: Category not found for ID {$id}", ['exception' => $e]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyCategory(int $id): bool
    {
        try {
            $model = Category::findOrFail($id);
            return $model->delete();
        } catch (ModelNotFoundException $e) {
            Log::warning("Failed to delete: Category not found for ID {$id}", ['exception' => $e]);
            return false;
        }
    }
}
