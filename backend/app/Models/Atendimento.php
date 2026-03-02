<?php
namespace App\Models;

final class Atendimento extends BaseModel
{
    public function create(array $data): int
    {
        $st = $this->db->prepare('
            INSERT INTO atendimentos (paciente_id, medico_id, servico_id, data_hora, status, criado_por)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        $st->execute([
            $data['paciente_id'],
            $data['medico_id'],
            $data['servico_id'],
            $data['data_hora'],
            $data['status'] ?? 'AGENDADO',
            $data['criado_por'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function listByPeriod(string $from, string $to): array
    {
        $st = $this->db->prepare('SELECT * FROM vw_atendimentos_detalhado WHERE data_hora BETWEEN ? AND ? ORDER BY data_hora ASC');
        $st->execute([$from, $to]);
        return $st->fetchAll();
    }

    public function updateEvolucao(int $id, array $data): bool
    {
        $st = $this->db->prepare('UPDATE atendimentos SET evolucao = ?, anamnese = ?, status = ?, updated_at = NOW() WHERE id = ?');
        return $st->execute([
            $data['evolucao'] ?? null,
            $data['anamnese'] ?? null,
            $data['status'] ?? 'CONCLUIDO',
            $id
        ]);
    }
}
