<?php
namespace App\Models;

final class Especialidade extends BaseModel
{
    public function create(array $data): int
    {
        $st = $this->db->prepare('INSERT INTO especialidades (nome, descricao) VALUES (?, ?)');
        $st->execute([$data['nome'], $data['descricao'] ?? null]);
        return (int)$this->db->lastInsertId();
    }

    public function list(): array
    {
        $st = $this->db->query('SELECT * FROM especialidades ORDER BY nome ASC');
        return $st->fetchAll();
    }

    public function update(int $id, array $data): bool
    {
        $st = $this->db->prepare('UPDATE especialidades SET nome = ?, descricao = ? WHERE id = ?');
        return $st->execute([$data['nome'], $data['descricao'] ?? null, $id]);
    }
}
