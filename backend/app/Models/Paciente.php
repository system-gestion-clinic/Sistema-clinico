<?php
namespace App\Models;

final class Paciente extends BaseModel
{
    public function create(array $data): int
    {
        $st = $this->db->prepare('
            INSERT INTO pacientes (nome, cpf, email, telefone, data_nascimento, ativo, observacoes)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $st->execute([
            $data['nome'],
            $data['cpf'] ?? null,
            $data['email'] ?? null,
            $data['telefone'] ?? null,
            $data['data_nascimento'] ?? null,
            $data['ativo'] ?? 1,
            $data['observacoes'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $st = $this->db->prepare('SELECT * FROM pacientes WHERE id = ? LIMIT 1');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function list(int $limit = 50, int $offset = 0): array
    {
        $st = $this->db->prepare('
            SELECT * FROM pacientes
            ORDER BY nome ASC
            LIMIT ? OFFSET ?
        ');
        $st->bindValue(1, $limit, \PDO::PARAM_INT);
        $st->bindValue(2, $offset, \PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $vals = [];

        foreach (['nome','cpf','email','telefone','data_nascimento','ativo','observacoes'] as $k) {
            if (array_key_exists($k, $data)) {
                $fields[] = "$k = ?";
                $vals[] = $data[$k];
            }
        }
        if (!$fields) return false;

        $vals[] = $id;
        $sql = 'UPDATE pacientes SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $st = $this->db->prepare($sql);
        return $st->execute($vals);
    }
}
