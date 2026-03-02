import React from "react";
import { api } from "../services/api";

export default function Dashboard() {
  const [me, setMe] = React.useState<any>(null);

  React.useEffect(() => {
    (async () => {
      try {
        const { data } = await api.get("/api/me");
        setMe(data.user);
      } catch {
        setMe(null);
      }
    })();
  }, []);

  return (
    <div className="card">
      <h2 style={{ marginTop: 0 }}>Dashboard</h2>
      <p className="muted">Resumo rápido do sistema (base). Depois dá pra colocar cards e gráficos.</p>

      <div className="row">
        <div className="card" style={{ flex: 1, minWidth: 240 }}>
          <strong>Usuário logado</strong>
          <div className="muted" style={{ marginTop: 8 }}>
            {me ? (
              <pre style={{ margin: 0, whiteSpace: "pre-wrap" }}>{JSON.stringify(me, null, 2)}</pre>
            ) : (
              "Sem dados (faça login)."
            )}
          </div>
        </div>

        <div className="card" style={{ flex: 1, minWidth: 240 }}>
          <strong>Status do backend</strong>
          <div className="muted" style={{ marginTop: 8 }}>Teste em /api/health</div>
        </div>
      </div>
    </div>
  );
}
