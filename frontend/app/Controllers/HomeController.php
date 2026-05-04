<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Assessment;

final class HomeController extends Controller
{
    public function index(Request $request): void
    {
        $stats = (new Assessment())->platformStats();
        $this->view('pages.home', [
            'pageTitle' => (string) t('home'),
            'platformStats' => $stats,
        ]);
    }
}
