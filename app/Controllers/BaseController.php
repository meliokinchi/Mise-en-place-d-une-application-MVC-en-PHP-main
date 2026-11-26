<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/Controllers/BaseController.php
 *
 * @package App.Controllers
 * @author Bilal
 */

namespace App\Controllers;
/**
 * Contrôleur de base : helpers de rendu (public/admin) et protections d'accès.
 */

abstract class BaseController {
/**
 * Méthode.
 * @param string $view
 * @param array $data
 * @param ?string $title
 * @return void
 */
  protected function render(string $view, array $data = [], ?string $title = null): void {
    extract($data, EXTR_SKIP);
    $pageTitle = $title ? ($title . ' — ' . APP_NAME) : APP_NAME;

    require ROOT_DIR . 'views/partials/head.php';
    require ROOT_DIR . 'views/partials/header.php';
    require ROOT_DIR . 'views/' . $view . '.php';
    require ROOT_DIR . 'views/partials/footer.php';
  }
/**
 * Méthode.
 * @return int
 */

  protected function requireLogin(): int {
  $uid = \App\Core\Auth::id();
  if (!$uid) {
    \App\Core\Flash::set('error', "Veuillez vous connecter pour accéder à cette fonctionnalité.");
    redirect('connexion');
  }
  return (int)$uid;
}
/**
 * Méthode.
 * @return void
 */

protected function requireAdmin(): void {
  if (!\App\Core\Auth::check() || !\App\Core\Auth::isAdmin()) {
    \App\Core\Flash::set('error', "Accès réservé à l’administrateur.");
    redirect();
  }
}
/**
 * Méthode.
 * @param string $view
 * @param array $data
 * @param ?string $title
 * @return void
 */

protected function renderAdmin(string $view, array $data = [], ?string $title = null): void {
  $this->requireAdmin();
  extract($data, EXTR_SKIP);
  $pageTitle = $title ? ($title . ' — ' . APP_NAME) : APP_NAME;
  require ROOT_DIR . 'views/partials/head.php';
  require ROOT_DIR . 'views/partials/header_admin.php';
  require ROOT_DIR . 'views/' . $view . '.php';
  require ROOT_DIR . 'views/partials/footer.php';
}


}
