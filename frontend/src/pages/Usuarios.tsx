import React from "react";
import { api } from "../services/api";

export default function Usuarios() {
  const [rows, setRows] = React.useState<any[]>([]);
  const [err, setErr] = React.useState<string | null>(null);

  async function load() {
    setErr(null);
    try {
      const { data } = await api.get("/api/usuarios");
      setRows(data.data || []);
    } catch (e: any) {
      setErr(e?.response?.data?.error || "Erro ao carregar usuários (precisa ser ADMIN)");
    }
  }

  React.useEffect(() => { load(); }, []);

  return (
    <div className="card">
      <h2 style={{ marginTop: 0 }}>Usuários (Admin)</h2>
      <p className="muted">Essa página só aparece no menu se o role do usuário for ADMIN.</p>
      {err && <div style={{ marginTop: 12, color: "#b00020" }}>{err}</div>}
      <div style={{ marginTop: 12, overflowX: "auto" }}>
        <table className="table">
          <thead>
            <tr><th>ID</th><th>Nome</th><th>Email</th><th>Tipo</th><th>Ativo</th></tr>
          </thead>
          <tbody>
            {rows.map((u) => (
              <tr key={u.id}>
                <td>{u.id}</td><td>{u.nome}</td><td>{u.email}</td><td>{u.tipo}</td><td>{u.ativo ? "Sim" : "Não"}</td>
              </tr>
            ))}
            {rows.length === 0 && <tr><td colSpan={5} className="muted">Sem dados</td></tr>}
          </tbody>
        </table>
      </div>
    </div>
  );
}
