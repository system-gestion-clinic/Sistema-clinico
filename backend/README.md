# Backend (PHP) - SGC

Backend base em **PHP 8+** com API REST simples, **JWT** e **PDO MySQL**.

## Requisitos
- PHP 8.1+
- Composer
- MySQL 8+

## Instalação
```bash
cd backend
composer install
cp .env.example .env
# ajuste DB_* e JWT_SECRET
```

## Rodar local (PHP built-in server)
```bash
php -S localhost:8000 -t public
```

## Endpoints principais
- POST /api/auth/login
- GET  /api/me
- CRUD:
  - /api/pacientes
  - /api/usuarios
  - /api/especialidades
  - /api/servicos
  - /api/atendimentos

## Observação
Algumas rotas exigem perfil ADMIN/ADM.
