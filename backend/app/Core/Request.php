<?php
namespace App\Core;

final class Request
{
    public static function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public static function path(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        return rtrim($path, '/') ?: '/';
    }

    public static function json(): array
    {
        $raw = file_get_contents('php://input');
        if (!$raw) return [];
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    public static function bearerToken(): ?string
    {
        $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s+(.*)$/i', $auth, $m)) {
            return trim($m[1]);
        }
        return null;
    }
}
