<?php

namespace App\Http\ViewComposers;

use App\Models\Category;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view): void
    {
        $mainCategories = Category::whereIn('id', [1, 2, 3])
            ->where('status', 1)
            ->with(['children' => fn($q) => $q->where('status', 1)
                ->with(['children' => fn($q2) => $q2->where('status', 1)])
            ])
            ->get();

        $view->with('mainCategories', $mainCategories);
    }
}
