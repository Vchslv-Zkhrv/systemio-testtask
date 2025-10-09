<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;

class ApiRequest extends Request
{
    public function getPreferredFormat(?string $default = 'html'): ?string
    {
        return 'json';
    }
}
