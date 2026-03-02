<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Especialidade;
use App\Models\Servico;
use App\Models\LogAcao;

final class CatalogoController
{
    public function especialidadesIndex(): void
    {
        $rows = (new Especialidade())->list();
        Response::json(['ok' => true, 'data' => $rows]);
    }

    public function especialidadesStore(array $payload): void
    {
        $data = Request::json();
        if (empty($data['nome'])) Response::error('nome é obrigatório', 422);
        $id = (new Especialidade())->create($data);
        (new LogAcao())->add((int)($payload['sub'] ?? 0), 'INSERT', 'especialidades', $id, 'Especialidade criada via API');
        Response::json(['ok' => true, 'id' => $id], 201);
    }

    public function servicosIndex(): void
    {
        $rows = (new Servico())->list();
        Response::json(['ok' => true, 'data' => $rows]);
    }

    public function servicosStore(array $payload): void
    {
        $data = Request::json();
        foreach (['nome','especialidade_id'] as $f) {
            if (empty($data[$f])) Response::error("Campo obrigatório: $f", 422);
        }
        $id = (new Servico())->create($data);
        (new LogAcao())->add((int)($payload['sub'] ?? 0), 'INSERT', 'servicos', $id, 'Serviço criado via API');
        Response::json(['ok' => true, 'id' => $id], 201);
    }
}
