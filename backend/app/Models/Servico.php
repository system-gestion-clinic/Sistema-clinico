<?php
namespace App\Models;

final class Servico extends BaseModel
{
    public function create(array $data): int
    {
        $st = $this->db->prepare('
            INSERT INTO servicos (nome, especialidade_id, duracao_minutos, ativo)
            VALUES (?, ?, ?, ?)
        ');
        $st->execute([
            $data['nome'],
            $data['especialidade_id'],
            $data['duracao_minutos'] ?? 30,
            $data['ativo'] ?? 1
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function list(): array
    {
        $st = $this->db->query('
            SELECT s.*, e.nome AS especialidade_nome
            FROM servicos s
            JOIN especialidades e ON e.id = s.especialidade_id
            ORDER BY e.nome, s.nome
        ');
        return $st->fetchAll();
    }
}
