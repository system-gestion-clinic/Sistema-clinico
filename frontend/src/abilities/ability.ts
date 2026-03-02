/**
 * Placeholder para CASL no frontend.
 * Se quiser implementar CASL de verdade, você pode instalar:
 *   npm i @casl/ability
 * e criar as regras por role.
 */
import type { UserRole } from "../types/api";

export function canAccess(role: UserRole, feature: string): boolean {
  const r = role.toUpperCase();
  if (r === "ADMIN") return true;
  if (r === "ADM") return feature !== "usuarios_admin_only";
  if (r === "MEDICO") return ["dashboard", "atendimentos", "relatorios", "perfil"].includes(feature);
  return false;
}
