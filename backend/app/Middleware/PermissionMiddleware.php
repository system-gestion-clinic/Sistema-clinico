<?php
namespace App\Middleware;

use App\Core\Response;

final class PermissionMiddleware
{
    /** @param array $payload payload do JWT */
    public static function requireRole(array $payload, array $allowedRoles): void
    {
        $role = strtoupper((string)($payload['role'] ?? ''));
        $allowed = array_map('strtoupper', $allowedRoles);

        if (!in_array($role, $allowed, true)) {
            Response::error('Sem permissão', 403, ['required' => $allowed, 'role' => $role]);
        }
    }
}
