<?php

declare(strict_types=1);

namespace Dune\Helpers;

class Response
{
    public function json(array $data, int $code = 200): ?string
    {
        $data = json_encode($data);
        header('Content-Type:application/json');
        http_response_code($code);
        echo $data;
        exit;
    }
}