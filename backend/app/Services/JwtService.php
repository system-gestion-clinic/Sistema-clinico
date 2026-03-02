<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class JwtService
{
    public static function secret(): string
    {
        return (string)($_ENV['JWT_SECRET'] ?? 'troque_este_segredo');
    }

    public static function ttlMinutes(): int
    {
        return (int)($_ENV['JWT_TTL_MINUTES'] ?? 480);
    }

    public static function issuer(): string
    {
        return (string)($_ENV['JWT_ISSUER'] ?? 'sgc');
    }

    public static function audience(): string
    {
        return (string)($_ENV['JWT_AUDIENCE'] ?? 'sgc_users');
    }

    public static function sign(array $user): string
    {
        $now = time();
        $exp = $now + (self::ttlMinutes() * 60);

        $payload = [
            'iss' => self::issuer(),
            'aud' => self::audience(),
            'iat' => $now,
            'exp' => $exp,
            'sub' => (string)$user['id'],
            'role' => $user['tipo'] ?? 'ADM',
            'name' => $user['nome'] ?? null,
            'email' => $user['email'] ?? null,
        ];

        return JWT::encode($payload, self::secret(), 'HS256');
    }

    public static function verify(string $token): array
    {
        $decoded = JWT::decode($token, new Key(self::secret(), 'HS256'));
        return (array)$decoded;
    }
}
