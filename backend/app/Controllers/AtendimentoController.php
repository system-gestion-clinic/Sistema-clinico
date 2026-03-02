<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Atendimento;
use App\Models\LogAcao;

final class AtendimentoController
{
    public function store(array $payload): void
    {
        $data = Request::json();
        foreach (['paciente_id','medico_id','servico_id','data_hora'] as $f) {
            if (empty($data[$f])) Response::error("Campo obrigatório: $f", 422);
        }
        $data['criado_por'] = (int)($payload['sub'] ?? 0);
        $id = (new Atendimento())->create($data);

        (new LogAcao())->add((int)($payload['sub'] ?? 0), 'INSERT', 'atendimentos', $id, 'Atendimento criado via API');
        Response::json(['ok' => true, 'id' => $id], 201);
    }

    public function evoluir(int $id, array $payload): void
    {
        $data = Request::json();
        $ok = (new Atendimento())->updateEvolucao($id, $data);
        if (!$ok) Response::error('Falha ao atualizar', 400);

        (new LogAcao())->add((int)($payload['sub'] ?? 0), 'UPDATE', 'atendimentos', $id, 'Evolução/anamnese registrada');
        Response::json(['ok' => true]);
    }

    public function relatorio(): void
    {
        $from = $_GET['from'] ?? date('Y-m-01 00:00:00');
        $to   = $_GET['to'] ?? date('Y-m-t 23:59:59');

        $rows = (new Atendimento())->listByPeriod($from, $to);
        Response::json(['ok' => true, 'from' => $from, 'to' => $to, 'data' => $rows]);
    }
}
