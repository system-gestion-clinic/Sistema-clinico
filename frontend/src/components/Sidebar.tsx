import { NavLink } from "react-router-dom";
import { useAuth } from "../hooks/useAuth";

export function Sidebar() {
  const { user } = useAuth();
  const role = user?.tipo ?? "ADM";

  const items = [
    { to: "/", label: "Dashboard" },
    { to: "/pacientes", label: "Pacientes" },
    { to: "/atendimentos", label: "Atendimentos" },
    { to: "/relatorios", label: "Relatórios" },
    { to: "/catalogo", label: "Especialidades/Serviços" },
    ...(role === "ADMIN" ? [{ to: "/usuarios", label: "Usuários (Admin)" }] : []),
  ];

  return (
    <div className="card sidebar">
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
        <strong>Menu</strong>
        <span className="muted" style={{ fontSize: 12 }}>{role}</span>
      </div>
      <div className="menu" style={{ marginTop: 10 }}>
        {items.map((it) => (
          <NavLink
            key={it.to}
            to={it.to}
            className={({ isActive }) => (isActive ? "active" : "")}
            end={it.to === "/"}
          >
            {it.label}
          </NavLink>
        ))}
      </div>
    </div>
  );
}
