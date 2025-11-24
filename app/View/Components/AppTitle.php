<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppTitle extends Component
{

    public $pageTitle;
    public $customBreadcrumb;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($pageTitle, $customBreadcrumb = null)
    {
        $this->pageTitle = is_array(__($pageTitle)) ? $pageTitle : __($pageTitle);
        $this->customBreadcrumb = $customBreadcrumb;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('components.app-title');
    }

}
