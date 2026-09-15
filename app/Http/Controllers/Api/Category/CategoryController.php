<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Category\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with($this->childrenRelations())
            ->orderBy('name')
            ->get();

        return CategoryResource::collection($categories);
    }

    /**
     * ساخت رشته eager-load تودرتو مثل 'children.children.children'
     * تا چند سطح از زیرکتگوری‌ها هم از قبل لود بشن (پیش‌فرض تا ۴ سطح).
     */
    private function childrenRelations(int $depth = 4): string
    {
        $relation = 'children';

        for ($i = 1; $i < $depth; $i++) {
            $relation .= '.children';
        }

        return $relation;
    }
}