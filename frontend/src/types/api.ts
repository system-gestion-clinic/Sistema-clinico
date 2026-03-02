export type UserRole = "ADMIN" | "ADM" | "MEDICO";

export type ApiUser = {
  id: number;
  nome: string;
  email: string;
  cpf?: string | null;
  telefone?: string | null;
  tipo: UserRole;
  ativo: number;
};

export type LoginResponse = {
  ok: boolean;
  token: string;
  user: { id: number; nome: string; email: string; tipo: UserRole };
};

export type Paciente = {
  id: number;
  nome: string;
  cpf?: string | null;
  email?: string | null;
  telefone?: string | null;
  data_nascimento?: string | null;
  ativo: number;
  observacoes?: string | null;
};
