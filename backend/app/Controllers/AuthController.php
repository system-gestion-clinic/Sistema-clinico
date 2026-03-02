<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Usuario;
use App\Models\LogAcao;
use App\Services\JwtService;

final class AuthController
{
    public function login(): void
    {
        $data = Request::json();
        $email = trim((string)($data['email'] ?? ''));
        $senha = (string)($data['senha'] ?? '');

        if ($email === '' || $senha === '') {
            Response::error('Email e senha são obrigatórios', 422);
        }

        $userModel = new Usuario();
        $user = $userModel->findByEmail($email);

        if (!$user || !(int)$user['ativo']) {
            Response::error('Usuário não encontrado ou inativo', 401);
        }

        if (!password_verify($senha, (string)$user['senha_hash'])) {
            Response::error('Credenciais inválidas', 401);
        }

        $token = JwtService::sign($user);

        (new LogAcao())->add((int)$user['id'], 'LOGIN', 'usuarios', (int)$user['id'], 'Login efetuado');

        Response::json([
            'ok' => true,
            'token' => $token,
            'user' => [
                'id' => (int)$user['id'],
                'nome' => $user['nome'],
                'email' => $user['email'],
                'tipo' => $user['tipo'],
            ]
        ]);
    }

    public function me(array $payload): void
    {
        $userId = (int)($payload['sub'] ?? 0);
        $user = (new Usuario())->findById($userId);
        if (!$user) Response::error('Usuário não encontrado', 404);

        Response::json(['ok' => true, 'user' => $user]);
    }
}
