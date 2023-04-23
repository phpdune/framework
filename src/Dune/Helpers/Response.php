<?php

declare(strict_types=1);

namespace Dune\Helpers;

class Response
{
    /**
     * array to json format
     *
     * @param array<mixed> $data
     * @param int $code
     *
     * @return null|string
     */
    public function json(array $data, int $code = 200): ?string
    {
        $data = json_encode($data);
        header('Content-Type:application/json');
        http_response_code($code);
        echo $data;
        exit;
    }
}
