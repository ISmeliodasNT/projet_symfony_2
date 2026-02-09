<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;

class TestController
{
    #[Route('/test', name: 'app_test')]
    public function number()
    {
        dd('welcome! ');
    }
}