<?php

use App\Kernel;

use App\Request\ApiRequest;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

Request::setFactory(function (
    array $query = [],
    array $request = [],
    array $attributes = [],
    array $cookies = [],
    array $files = [],
    array $server = [],
    $content = null
) {
    return new ApiRequest($query, $request, $attributes, $cookies, $files, $server, $content);
});

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
