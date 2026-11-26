<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/public/index.php
 *
 * @author Bilal
 */

const ROOT_DIR = __DIR__ . '/../';

require ROOT_DIR . 'app/config.php';

// Sessions (avant tout echo)
session_set_cookie_params([
  'lifetime' => 0,
  'path'     => BASE_URL ?: '/',
  'secure'   => false,      // intranet HTTP : false (mettre true si HTTPS)
  'httponly' => true,
  'samesite' => 'Lax',
]);
session_start();


// Autoload très simple (pas de Composer)
spl_autoload_register(function ($class) {
  $prefix = 'App\\';
  if (strpos($class, $prefix) !== 0) return;
  $relative = str_replace($prefix, '', $class);
  $path = ROOT_DIR . 'app/' . str_replace('\\', '/', $relative) . '.php';
  if (is_file($path)) require $path;
});

use App\Core\Router;

// Instancie le routeur + routes
$router = new Router();
$router->get('/',          'HomeController@index');
$router->get('/connexion', 'AuthController@loginForm');
$router->post('/connexion',  'AuthController@loginSubmit');
$router->get('/deconnexion', 'AuthController@logout');
$router->get('/trajets/modifier', 'TrajetController@editForm');
$router->post('/trajets/modifier', 'TrajetController@update');
$router->post('/trajets/supprimer', 'TrajetController@delete'); // action réelle
$router->get('/trajets/nouveau', 'TrajetController@newForm'); // affichage du formulaire
$router->post('/trajets/nouveau', 'TrajetController@create');  // soumission

// === ADMIN ===
$router->get('/admin',                    'AdminController@dashboard');

$router->get('/admin/utilisateurs',       'AdminController@users');

$router->get('/admin/agences',            'AdminController@agencies');
$router->get('/admin/agences/nouveau',    'AdminController@agencyNewForm');
$router->post('/admin/agences/nouveau',   'AdminController@agencyCreate');
$router->get('/admin/agences/modifier',   'AdminController@agencyEditForm');
$router->post('/admin/agences/modifier',  'AdminController@agencyUpdate');
$router->post('/admin/agences/supprimer', 'AdminController@agencyDelete');

$router->get('/admin/trajets',            'AdminController@trips');
$router->post('/admin/trajets/supprimer', 'AdminController@tripDelete');



// Dispatch
$router->dispatch();
