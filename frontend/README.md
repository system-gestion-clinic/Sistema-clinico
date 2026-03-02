# Frontend (React) - SGC

Estrutura pronta em **React + TypeScript (Vite)**, com páginas base e integração com a API PHP.

## Requisitos
- Node 18+ (ou 20+)
- NPM

## Instalar e rodar
```bash
cd frontend
npm install
cp .env.example .env
# ajuste VITE_API_BASE_URL (ex: http://localhost:8000)
npm run dev
```

## Rotas
- /login
- / (dashboard)
- /pacientes
- /atendimentos
- /relatorios
- /catalogo
- /usuarios (aparece no menu só para ADMIN)

## Observações
- O token JWT fica em localStorage (sgc_token).
- O backend deve estar rodando em http://localhost:8000 (ou ajuste no .env).
