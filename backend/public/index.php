<?php
declare(strict_types=1);

require __DIR__ . '/../config/bootstrap.php';

use App\Core\Request;
use App\Core\Router;
use App\Core\Response;

// CORS básico (ajuste conforme frontend)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if (Request::method() === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$router = new Router();

$registerRoutes = require __DIR__ . '/../routes/api.php';
$registerRoutes($router);

$method = Request::method();
$path = Request::path();

try {
    $router->dispatch($method, $path);
} catch (Throwable $e) {
    $debug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);
    Response::error('Erro interno', 500, $debug ? ['detail' => $e->getMessage()] : []);
}
