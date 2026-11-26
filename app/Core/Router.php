<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/Core/Router.php
 *
 * @package App.Core
 * @author Bilal
 */

namespace App\Core;
/**
 * Routeur HTTP minimaliste (GET/POST) avec prise en charge du préfixe de base et d’un fallback `?r=`.
 */

final class Router {
  private array $routes = [];
/**
 * Lit puis supprime un message flash.
 * @param string $path
 * @param mixed $handler
 * @return void
 */

  public function get(string $path, $handler): void { $this->map('GET', $path, $handler); }
/**
 * Méthode.
 * @param string $path
 * @param mixed $handler
 * @return void
 */
  public function post(string $path, $handler): void { $this->map('POST', $path, $handler); }
/**
 * Méthode.
 * @param string $method
 * @param string $path
 * @param mixed $handler
 * @return void
 */

  public function map(string $method, string $path, $handler): void {
    $key = $this->normalize($path);
    $this->routes[$method][$key] = $handler;
  }
/**
 * Méthode.
 * @param string $path
 * @return string
 */

  private function normalize(string $path): string {
    $p = '/' . trim($path, '/');
    return $p === '//' ? '/' : $p;
  }
/**
 * Méthode.
 * @return void
 */

  public function dispatch(): void {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

    // Retire le préfixe BASE_URL (ex: /covoit-mvc/public/)
    $base = rtrim(BASE_URL, '/');
    if ($base !== '' && strpos($requestPath, $base) === 0) {
      $requestPath = substr($requestPath, strlen($base));
      if ($requestPath === false) { $requestPath = '/'; }
    }

    $path = $this->normalize($requestPath);

    // Fallback query-string si pas de réécriture: /?r=trajets
    if (($path === '/' || $path === '') && !empty($_GET['r'])) {
      $path = $this->normalize('/' . $_GET['r']);
    }

    $handler = $this->routes[$method][$path] ?? null;

    if (!$handler) {
      http_response_code(404);
      $pageTitle = 'Page non trouvée — ' . APP_NAME;
      require ROOT_DIR . 'views/partials/head.php';
      require ROOT_DIR . 'views/partials/header.php';
      require ROOT_DIR . 'views/errors/404.php';
      require ROOT_DIR . 'views/partials/footer.php';
      return;
    }

    // Handler callable direct
    if (is_callable($handler)) { $handler(); return; }

    // Handler "Controller@method"
    if (is_string($handler)) {
      [$ctrl, $methodName] = explode('@', $handler, 2);
      $class = 'App\\Controllers\\' . $ctrl;
      $instance = new $class();
      $instance->$methodName();
      return;
    }

    throw new \RuntimeException('Handler de route invalide.');
  }
}
