import { Link, useLocation } from "react-router-dom";
import { useAuth } from "../hooks/useAuth";

export function Topbar() {
  const { user, logout } = useAuth();
  const loc = useLocation();

  return (
    <div className="topbar">
      <div className="nav">
        <div style={{ display: "flex", gap: 10, alignItems: "center" }}>
          <strong>SGC</strong>
          <span className="muted" style={{ fontSize: 12 }}>{loc.pathname}</span>
        </div>
        <div style={{ display: "flex", gap: 10, alignItems: "center" }}>
          {user ? (
            <>
              <span className="muted" style={{ fontSize: 12 }}>{user.nome} • {user.tipo}</span>
              <button className="btn secondary" onClick={logout}>Sair</button>
            </>
          ) : (
            <Link className="btn" to="/login">Entrar</Link>
          )}
        </div>
      </div>
    </div>
  );
}
