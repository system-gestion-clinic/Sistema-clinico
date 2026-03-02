<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Usuario;
use App\Models\LogAcao;

final class UsuarioController
{
    public function index(): void
    {
        $limit = (int)($_GET['limit'] ?? 50);
        $offset = (int)($_GET['offset'] ?? 0);
        $rows = (new Usuario())->list($limit, $offset);
        Response::json(['ok' => true, 'data' => $rows]);
    }

    public function store(array $payload): void
    {
        $data = Request::json();
        foreach (['nome','email','senha','tipo'] as $f) {
            if (empty($data[$f])) Response::error("Campo obrigatório: $f", 422);
        }

        $data['senha_hash'] = password_hash((string)$data['senha'], PASSWORD_DEFAULT);
        unset($data['senha']);

        $id = (new Usuario())->create($data);
        (new LogAcao())->add((int)($payload['sub'] ?? 0), 'INSERT', 'usuarios', $id, 'Usuário criado via API');

        Response::json(['ok' => true, 'id' => $id], 201);
    }

    public function update(int $id, array $payload): void
    {
        $data = Request::json();
        if (isset($data['senha'])) {
            $data['senha_hash'] = password_hash((string)$data['senha'], PASSWORD_DEFAULT);
            unset($data['senha']);
        }

        $ok = (new Usuario())->update($id, $data);
        if (!$ok) Response::error('Nada para atualizar', 422);

        (new LogAcao())->add((int)($payload['sub'] ?? 0), 'UPDATE', 'usuarios', $id, 'Usuário atualizado via API');
        Response::json(['ok' => true]);
    }
}
