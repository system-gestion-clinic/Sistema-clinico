<?php
namespace App\Models;

final class LogAcao extends BaseModel
{
    public function add(?int $usuarioId, string $acao, string $tabela, ?int $registroId, ?string $descricao): void
    {
        $st = $this->db->prepare('
            INSERT INTO log_acoes (usuario_id, acao, tabela, registro_id, descricao, ip, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $st->execute([
            $usuarioId,
            $acao,
            $tabela,
            $registroId,
            $descricao,
            $_SERVER['REMOTE_ADDR'] ?? null,
            substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255)
        ]);
    }
}
