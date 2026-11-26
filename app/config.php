<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/config.php
 *
 * @author Bilal
 */

// Détecte la base dynamiquement (ok XAMPP/sous-dossier)
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';
define('BASE_URL', $base);

// Nom d'appli (facultatif)
define('APP_NAME', "Touche pas au klaxon");

// Helper d’URL (url('trajets') -> BASE_URL.'trajets')
if (!function_exists('url')) {
  function url(string $path = ''): string {
    return BASE_URL . ltrim($path, '/');
  }
}
// === MySQL (XAMPP par défaut : root / mot de passe vide) ===
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'covoit_mvc');   // <- adapte si on a un autre nom
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
define('DB_TIMEZONE', '+02:00');   // Europe/Paris (été)

if (!function_exists('redirect')) {
  function redirect(string $path = ''): void {
    header('Location: ' . url($path));
    exit;
  }
}
