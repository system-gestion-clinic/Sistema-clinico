import React from "react";
import { api } from "../services/api";
import { Bar } from "react-chartjs-2";
import { Chart as ChartJS, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from "chart.js";

ChartJS.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend);

export default function Relatorios() {
  const [rows, setRows] = React.useState<any[]>([]);
  const [err, setErr] = React.useState<string | null>(null);

  async function load() {
    setErr(null);
    try {
      const { data } = await api.get("/api/relatorios/atendimentos");
      setRows(data.data || []);
    } catch (e: any) {
      setErr(e?.response?.data?.error || "Erro ao carregar relatório");
    }
  }

  React.useEffect(() => { load(); }, []);

  // gráfico simples por status
  const counts = rows.reduce((acc, r) => {
    const s = r.status || "DESCONHECIDO";
    acc[s] = (acc[s] || 0) + 1;
    return acc;
  }, {} as Record<string, number>);

  const labels = Object.keys(counts);
  const values = Object.values(counts);

  return (
    <div className="card">
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
        <h2 style={{ margin: 0 }}>Relatórios</h2>
        <button className="btn secondary" onClick={load}>Atualizar</button>
      </div>

      {err && <div style={{ marginTop: 12, color: "#b00020" }}>{err}</div>}

      <div className="card" style={{ marginTop: 12 }}>
        <strong>Atendimentos por status</strong>
        <div style={{ marginTop: 8 }}>
          <Bar data={{ labels, datasets: [{ label: "Qtd", data: values }] }} />
        </div>
      </div>

      <div className="card" style={{ marginTop: 12, overflowX: "auto" }}>
        <strong>Detalhado</strong>
        <table className="table" style={{ marginTop: 8 }}>
          <thead>
            <tr>
              <th>ID</th><th>Data</th><th>Status</th><th>Paciente</th><th>Médico</th><th>Serviço</th>
            </tr>
          </thead>
          <tbody>
            {rows.map((r) => (
              <tr key={r.id}>
                <td>{r.id}</td>
                <td>{r.data_hora}</td>
                <td>{r.status}</td>
                <td>{r.paciente_nome ?? "-"}</td>
                <td>{r.medico_nome ?? "-"}</td>
                <td>{r.servico ?? "-"}</td>
              </tr>
            ))}
            {rows.length === 0 && (
              <tr><td colSpan={6} className="muted">Sem dados</td></tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}
