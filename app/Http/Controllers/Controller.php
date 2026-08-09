<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Menu yang disorot di sidebar. Ditimpa oleh masing-masing controller.
     */
    public string $mainMenu = '';

    /**
     * Render view sambil menyisipkan $mainMenu yang dibutuhkan layout.
     */
    protected function createView(string $viewPath, array $additionalData = []): View
    {
        return view($viewPath, array_merge(['mainMenu' => $this->mainMenu], $additionalData));
    }
}
