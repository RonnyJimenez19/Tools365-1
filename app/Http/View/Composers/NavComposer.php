<?php

namespace App\Http\View\Composers;

use App\Models\NavItem;
use Illuminate\View\View;

class NavComposer
{
    public function compose(View $view): void
    {
        $navItems = NavItem::activos()->get();
        $view->with('navItems', $navItems);
    }
}