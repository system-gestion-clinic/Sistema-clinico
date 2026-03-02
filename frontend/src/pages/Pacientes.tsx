import React from "react";
import { api } from "../services/api";
import type { Paciente } from "../types/api";

export default function Pacientes() {
  const [rows, setRows] = React.useState<Paciente[]>([]);
  const [nome, setNome] = React.useState("");
  const [loading, setLoading] = React.useState(false);
  const [err, setErr] = React.useState<string | null>(null);

  async function load() {
    setErr(null);
    setLoading(true);
    try {
      const { data } = await api.get<{ ok: boolean; data: Paciente[] }>("/api/pacientes?limit=50&offset=0");
      setRows(data.data);
    } catch (e: any) {
      setErr(e?.response?.data?.error || "Erro ao carregar");
    } finally {
      setLoading(false);
    }
  }

  async function create() {
    if (!nome.trim()) return;
    setErr(null);
    try {
      await api.post("/api/pacientes", { nome });
      setNome("");
      await load();
    } catch (e: any) {
      setErr(e?.response?.data?.error || "Erro ao criar");
    }
  }

  React.useEffect(() => { load(); }, []);

  return (
    <div className="card">
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", gap: 12 }}>
        <h2 style={{ margin: 0 }}>Pacientes</h2>
        <button className="btn secondary" onClick={load} disabled={loading}>
          {loading ? "Atualizando..." : "Atualizar"}
        </button>
      </div>

      <div className="row" style={{ marginTop: 12, alignItems: "end" }}>
        <div style={{ flex: 1, minWidth: 260 }}>
          <div className="label">Novo paciente (nome)</div>
          <input className="input" value={nome} onChange={(e) => setNome(e.target.value)} placeholder="Ex: João da Silva" />
        </div>
        <button className="btn" onClick={create}>Adicionar</button>
      </div>

      {err && <div style={{ marginTop: 12, color: "#b00020" }}>{err}</div>}

      <div style={{ marginTop: 14, overflowX: "auto" }}>
        <table className="table">
          <thead>
            <tr>
              <th>ID</th><th>Nome</th><th>CPF</th><th>Telefone</th><th>Ativo</th>
            </tr>
          </thead>
          <tbody>
            {rows.map((p) => (
              <tr key={p.id}>
                <td>{p.id}</td>
                <td>{p.nome}</td>
                <td>{p.cpf ?? "-"}</td>
                <td>{p.telefone ?? "-"}</td>
                <td>{p.ativo ? "Sim" : "Não"}</td>
              </tr>
            ))}
            {rows.length === 0 && (
              <tr><td colSpan={5} className="muted">Nenhum paciente</td></tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}
