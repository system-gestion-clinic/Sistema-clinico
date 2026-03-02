import React from "react";
import type { UserRole } from "../types/api";
import { getStoredUser, logout as doLogout } from "../services/auth";

type AuthState = { id: number; nome: string; email: string; tipo: UserRole } | null;

const AuthCtx = React.createContext<{
  user: AuthState;
  setUser: React.Dispatch<React.SetStateAction<AuthState>>;
  logout: () => void;
}>({ user: null, setUser: () => {}, logout: () => {} });

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = React.useState<AuthState>(() => getStoredUser());

  const logout = React.useCallback(() => {
    doLogout();
    setUser(null);
  }, []);

  return <AuthCtx.Provider value={{ user, setUser, logout }}>{children}</AuthCtx.Provider>;
}

export function useAuth() {
  return React.useContext(AuthCtx);
}
