<?php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Services\JwtService;

final class AuthMiddleware
{
    public static function requireAuth(): array
    {
        $token = Request::bearerToken();
        if (!$token) {
            Response::error('Token ausente', 401);
        }

        try {
            $payload = JwtService::verify($token);
            return $payload;
        } catch (\Throwable $e) {
            Response::error('Token inválido', 401);
        }
    }
}
