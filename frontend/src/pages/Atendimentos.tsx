import React from "react";
import { api } from "../services/api";

export default function Atendimentos() {
  const [msg, setMsg] = React.useState<string>("");
  const [err, setErr] = React.useState<string | null>(null);

  async function criarExemplo() {
    setErr(null);
    setMsg("");
    try {
      // Exemplo: você deve trocar IDs reais do seu banco
      const { data } = await api.post("/api/atendimentos", {
        paciente_id: 1,
        medico_id: 1,
        servico_id: 1,
        data_hora: new Date().toISOString().slice(0, 19).replace("T", " "),
        status: "AGENDADO"
      });
      setMsg(`Atendimento criado (id: ${data.id}).`);
    } catch (e: any) {
      setErr(e?.response?.data?.error || "Erro ao criar atendimento");
    }
  }

  return (
    <div className="card">
      <h2 style={{ marginTop: 0 }}>Atendimentos</h2>
      <p className="muted">Tela base. Depois dá para listar, filtrar por data e permitir evolução/anamnese.</p>

      <button className="btn" onClick={criarExemplo}>Criar atendimento (exemplo)</button>

      {msg && <div style={{ marginTop: 12 }}>{msg}</div>}
      {err && <div style={{ marginTop: 12, color: "#b00020" }}>{err}</div>}
    </div>
  );
}
