import React from "react";
import { useNavigate } from "react-router-dom";
import { login } from "../services/auth";
import { useAuth } from "../hooks/useAuth";

export default function Login() {
  const nav = useNavigate();
  const { setUser } = useAuth();
  const [email, setEmail] = React.useState("");
  const [senha, setSenha] = React.useState("");
  const [loading, setLoading] = React.useState(false);
  const [err, setErr] = React.useState<string | null>(null);

  async function onSubmit(e: React.FormEvent) {
    e.preventDefault();
    setErr(null);
    setLoading(true);
    try {
      const u = await login(email, senha);
      setUser(u);
      nav("/", { replace: true });
    } catch (e: any) {
      setErr(e?.response?.data?.error || "Falha no login");
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className="container" style={{ maxWidth: 460, paddingTop: 48 }}>
      <div className="card">
        <h2 style={{ marginTop: 0 }}>Entrar no SGC</h2>
        <p className="muted" style={{ marginTop: -6 }}>
          Use o email e senha do backend.
        </p>

        <form onSubmit={onSubmit} className="row" style={{ flexDirection: "column" }}>
          <div>
            <div className="label">Email</div>
            <input className="input" value={email} onChange={(e) => setEmail(e.target.value)} />
          </div>
          <div>
            <div className="label">Senha</div>
            <input className="input" type="password" value={senha} onChange={(e) => setSenha(e.target.value)} />
          </div>

          {err && <div className="muted" style={{ color: "#b00020" }}>{err}</div>}

          <button className="btn" disabled={loading}>
            {loading ? "Entrando..." : "Entrar"}
          </button>
          <div className="muted" style={{ fontSize: 12 }}>
            Dica: configure <code>VITE_API_BASE_URL</code> no <code>.env</code> do frontend.
          </div>
        </form>
      </div>
    </div>
  );
}
