import { api } from "./api";
import type { LoginResponse } from "../types/api";

export async function login(email: string, senha: string) {
  const { data } = await api.post<LoginResponse>("/api/auth/login", { email, senha });
  localStorage.setItem("sgc_token", data.token);
  localStorage.setItem("sgc_user", JSON.stringify(data.user));
  return data.user;
}

export function logout() {
  localStorage.removeItem("sgc_token");
  localStorage.removeItem("sgc_user");
}

export function getStoredUser() {
  const raw = localStorage.getItem("sgc_user");
  if (!raw) return null;
  try { return JSON.parse(raw); } catch { return null; }
}
