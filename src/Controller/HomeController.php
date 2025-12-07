<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\BaseController;

class HomeController extends BaseController
{
    public function index(): void
    {
        $this->render('home/index', [
            'title' => 'Welcome to Pure PHP MVC'
        ]);
    }
}
