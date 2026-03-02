<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Paciente;
use App\Models\LogAcao;

final class PacienteController
{
    public function index(): void
    {
        $limit = (int)($_GET['limit'] ?? 50);
        $offset = (int)($_GET['offset'] ?? 0);
        $rows = (new Paciente())->list($limit, $offset);
        Response::json(['ok' => true, 'data' => $rows]);
    }

    public function show(int $id): void
    {
        $row = (new Paciente())->find($id);
        if (!$row) Response::error('Paciente não encontrado', 404);
        Response::json(['ok' => true, 'data' => $row]);
    }

    public function store(array $payload): void
    {
        $data = Request::json();
        if (empty($data['nome'])) Response::error('Nome é obrigatório', 422);

        $id = (new Paciente())->create($data);
        (new LogAcao())->add((int)($payload['sub'] ?? 0), 'INSERT', 'pacientes', $id, 'Paciente criado via API');

        Response::json(['ok' => true, 'id' => $id], 201);
    }

    public function update(int $id, array $payload): void
    {
        $data = Request::json();
        $ok = (new Paciente())->update($id, $data);
        if (!$ok) Response::error('Nada para atualizar', 422);

        (new LogAcao())->add((int)($payload['sub'] ?? 0), 'UPDATE', 'pacientes', $id, 'Paciente atualizado via API');
        Response::json(['ok' => true]);
    }
}
