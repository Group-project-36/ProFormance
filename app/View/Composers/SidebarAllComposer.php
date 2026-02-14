<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class SidebarAllComposer
{
    public function compose(View $view): void
        {
            $mainCategories = Category::query()
        ->with(['children' => function ($q) {
            $q->where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order');
        }])
        ->whereNull('parent_id')
        ->where('is_active', true)
        ->withCount('products')
        ->orderBy('sort_order')
        ->get();

        // Add a total count for parents = own + children
        $mainCategories->each(function ($cat) {
            $cat->products_total = (int) $cat->products_count
                + (int) $cat->children->sum('products_count');
        });
        $view->with('mainCategories', $mainCategories);


    }
}
