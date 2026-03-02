<?php
namespace App\Models;

final class Usuario extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        $st = $this->db->prepare('SELECT * FROM usuarios WHERE email = ? LIMIT 1');
        $st->execute([$email]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $st = $this->db->prepare('SELECT id, nome, email, cpf, telefone, tipo, ativo, created_at, updated_at FROM usuarios WHERE id = ? LIMIT 1');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $st = $this->db->prepare('
            INSERT INTO usuarios (nome, email, senha_hash, cpf, telefone, tipo, ativo)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $st->execute([
            $data['nome'],
            $data['email'],
            $data['senha_hash'],
            $data['cpf'] ?? null,
            $data['telefone'] ?? null,
            $data['tipo'] ?? 'ADM',
            $data['ativo'] ?? 1
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function list(int $limit = 50, int $offset = 0): array
    {
        $st = $this->db->prepare('
            SELECT id, nome, email, cpf, telefone, tipo, ativo, created_at, updated_at
            FROM usuarios
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

        foreach (['nome','email','cpf','telefone','tipo','ativo'] as $k) {
            if (array_key_exists($k, $data)) {
                $fields[] = "$k = ?";
                $vals[] = $data[$k];
            }
        }
        if (array_key_exists('senha_hash', $data)) {
            $fields[] = "senha_hash = ?";
            $vals[] = $data['senha_hash'];
        }

        if (!$fields) return false;

        $vals[] = $id;
        $sql = 'UPDATE usuarios SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $st = $this->db->prepare($sql);
        return $st->execute($vals);
    }
}
