<?php
namespace Application\Core;

abstract class Controller
{

    protected function jsonResponse($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }


    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

}
