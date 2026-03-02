import React from "react";
import { api } from "../services/api";

export default function Catalogo() {
  const [esp, setEsp] = React.useState<any[]>([]);
  const [srv, setSrv] = React.useState<any[]>([]);
  const [err, setErr] = React.useState<string | null>(null);

  async function load() {
    setErr(null);
    try {
      const a = await api.get("/api/especialidades");
      const b = await api.get("/api/servicos");
      setEsp(a.data.data || []);
      setSrv(b.data.data || []);
    } catch (e: any) {
      setErr(e?.response?.data?.error || "Erro ao carregar catálogo");
    }
  }

  React.useEffect(() => { load(); }, []);

  return (
    <div className="card">
      <h2 style={{ marginTop: 0 }}>Especialidades e Serviços</h2>
      {err && <div style={{ marginTop: 12, color: "#b00020" }}>{err}</div>}

      <div className="row" style={{ marginTop: 12 }}>
        <div className="card" style={{ flex: 1, minWidth: 300 }}>
          <strong>Especialidades</strong>
          <ul>
            {esp.map((e) => <li key={e.id}>{e.nome}</li>)}
            {esp.length === 0 && <li className="muted">Sem especialidades</li>}
          </ul>
        </div>
        <div className="card" style={{ flex: 1, minWidth: 300 }}>
          <strong>Serviços</strong>
          <ul>
            {srv.map((s) => <li key={s.id}>{s.especialidade_nome} • {s.nome} ({s.duracao_minutos} min)</li>)}
            {srv.length === 0 && <li className="muted">Sem serviços</li>}
          </ul>
        </div>
      </div>
    </div>
  );
}
