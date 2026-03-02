<?php
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\PacienteController;
use App\Controllers\UsuarioController;
use App\Controllers\CatalogoController;
use App\Controllers\AtendimentoController;
use App\Middleware\AuthMiddleware;
use App\Middleware\PermissionMiddleware;

return function (Router $r) {
    // Health
    $r->add('GET', '/api/health', function () {
        \App\Core\Response::json(['ok' => true, 'status' => 'UP']);
    });

    // Auth
    $r->add('POST', '/api/auth/login', function () {
        (new AuthController())->login();
    });

    $r->add('GET', '/api/me', function () {
        $payload = AuthMiddleware::requireAuth();
        (new AuthController())->me($payload);
    });

    // Pacientes (ADM/ADMIN)
    $r->add('GET', '/api/pacientes', function () {
        AuthMiddleware::requireAuth();
        (new PacienteController())->index();
    });
    $r->add('GET', '/api/pacientes/{id}', function ($id) {
        AuthMiddleware::requireAuth();
        (new PacienteController())->show((int)$id);
    });
    $r->add('POST', '/api/pacientes', function () {
        $payload = AuthMiddleware::requireAuth();
        PermissionMiddleware::requireRole($payload, ['ADMIN','ADM']);
        (new PacienteController())->store($payload);
    });
    $r->add('PUT', '/api/pacientes/{id}', function ($id) {
        $payload = AuthMiddleware::requireAuth();
        PermissionMiddleware::requireRole($payload, ['ADMIN','ADM']);
        (new PacienteController())->update((int)$id, $payload);
    });

    // Usuários (somente ADMIN)
    $r->add('GET', '/api/usuarios', function () {
        $payload = AuthMiddleware::requireAuth();
        PermissionMiddleware::requireRole($payload, ['ADMIN']);
        (new UsuarioController())->index();
    });
    $r->add('POST', '/api/usuarios', function () {
        $payload = AuthMiddleware::requireAuth();
        PermissionMiddleware::requireRole($payload, ['ADMIN']);
        (new UsuarioController())->store($payload);
    });
    $r->add('PUT', '/api/usuarios/{id}', function ($id) {
        $payload = AuthMiddleware::requireAuth();
        PermissionMiddleware::requireRole($payload, ['ADMIN']);
        (new UsuarioController())->update((int)$id, $payload);
    });

    // Catálogo (ADM/ADMIN)
    $r->add('GET', '/api/especialidades', function () {
        AuthMiddleware::requireAuth();
        (new CatalogoController())->especialidadesIndex();
    });
    $r->add('POST', '/api/especialidades', function () {
        $payload = AuthMiddleware::requireAuth();
        PermissionMiddleware::requireRole($payload, ['ADMIN','ADM']);
        (new CatalogoController())->especialidadesStore($payload);
    });

    $r->add('GET', '/api/servicos', function () {
        AuthMiddleware::requireAuth();
        (new CatalogoController())->servicosIndex();
    });
    $r->add('POST', '/api/servicos', function () {
        $payload = AuthMiddleware::requireAuth();
        PermissionMiddleware::requireRole($payload, ['ADMIN','ADM']);
        (new CatalogoController())->servicosStore($payload);
    });

    // Atendimentos
    $r->add('POST', '/api/atendimentos', function () {
        $payload = AuthMiddleware::requireAuth();
        PermissionMiddleware::requireRole($payload, ['ADMIN','ADM']);
        (new AtendimentoController())->store($payload);
    });

    // Evolução/Anamnese (MEDICO/ADM/ADMIN)
    $r->add('PUT', '/api/atendimentos/{id}/evolucao', function ($id) {
        $payload = AuthMiddleware::requireAuth();
        PermissionMiddleware::requireRole($payload, ['ADMIN','ADM','MEDICO']);
        (new AtendimentoController())->evoluir((int)$id, $payload);
    });

    // Relatório (ADM/ADMIN)
    $r->add('GET', '/api/relatorios/atendimentos', function () {
        $payload = AuthMiddleware::requireAuth();
        PermissionMiddleware::requireRole($payload, ['ADMIN','ADM']);
        (new AtendimentoController())->relatorio();
    });
};
