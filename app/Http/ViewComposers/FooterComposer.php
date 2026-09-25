<?php

namespace App\Http\ViewComposers;

use App\Models\FooterMenu;
use Illuminate\View\View;

class FooterComposer
{
    public function compose(View $view): void
    {
        $footerColumns = FooterMenu::whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->with(['children' => fn($q) => $q->where('status', 1)->orderBy('sort_order')])
            ->get();

        $view->with('footerColumns', $footerColumns);
    }
}
