import { BrowserRouter, Routes, Route } from "react-router-dom";
import { AuthProvider } from "./hooks/useAuth";
import { Layout } from "./components/Layout";
import { ProtectedRoute } from "./components/ProtectedRoute";

import Login from "./pages/Login";
import Dashboard from "./pages/Dashboard";
import Pacientes from "./pages/Pacientes";
import Atendimentos from "./pages/Atendimentos";
import Relatorios from "./pages/Relatorios";
import Catalogo from "./pages/Catalogo";
import Usuarios from "./pages/Usuarios";
import NotFound from "./pages/NotFound";

export default function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <Routes>
          <Route path="/login" element={<Login />} />
          <Route
            path="/"
            element={
              <ProtectedRoute>
                <Layout />
              </ProtectedRoute>
            }
          >
            <Route index element={<Dashboard />} />
            <Route path="pacientes" element={<Pacientes />} />
            <Route path="atendimentos" element={<Atendimentos />} />
            <Route path="relatorios" element={<Relatorios />} />
            <Route path="catalogo" element={<Catalogo />} />
            <Route path="usuarios" element={<Usuarios />} />
            <Route path="*" element={<NotFound />} />
          </Route>
        </Routes>
      </BrowserRouter>
    </AuthProvider>
  );
}
