<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/Controllers/AdminController.php
 *
 * @package App.Controllers
 * @author Bilal
 */

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AgencyModel;
use App\Models\TripModel;
use App\Core\CSRF;
use App\Core\Flash;
/**
 * Back‑office administrateur : listes, CRUD agences, suppression trajets, gestion mdp.
 */

final class AdminController extends BaseController {
/**
 * Affiche le tableau de bord admin.
 * @return void
 */
  public function dashboard(): void {
    $this->renderAdmin('admin/dashboard', []);
  }
/**
 * Liste les utilisateurs.
 * @return void
 */

  public function users(): void {
    $users = (new UserModel())->all();
    $this->renderAdmin('admin/users', ['users' => $users], 'Utilisateurs');
  }
/**
 * Liste les agences.
 * @return void
 */

  public function agencies(): void {
    $agencies = (new AgencyModel())->all();
    $this->renderAdmin('admin/agencies_index', ['agencies' => $agencies], 'Agences');
  }
/**
 * Affiche le formulaire de création d’agence.
 * @return void
 */

  public function agencyNewForm(): void {
    $this->renderAdmin('admin/agencies_new', ['old' => [], 'errors' => []], 'Nouvelle agence');
  }
/**
 * Crée une agence (POST + CSRF).
 * @return void
 */
  public function agencyCreate(): void {
    $this->requireAdmin();
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !CSRF::verify($_POST['_token'] ?? null)) {
      Flash::set('error', "Requête invalide."); redirect('admin/agences/nouveau');
    }
    $name = trim((string)($_POST['name'] ?? ''));
    $errors = [];
    if ($name === '') $errors['name'] = "Nom obligatoire.";
    if (!empty($errors)) { $this->renderAdmin('admin/agencies_new', ['old'=>['name'=>$name],'errors'=>$errors],'Nouvelle agence'); return; }
    $id = (new AgencyModel())->create($name);
    if ($id === false) { Flash::set('error', "Création impossible (doublon ?)"); redirect('admin/agences/nouveau'); }
    Flash::set('success', "Agence créée.");
    redirect('admin/agences');
  }
/**
 * Affiche le formulaire d’édition d’agence.
 * @return void
 */

  public function agencyEditForm(): void {
    $this->requireAdmin();
    $id = (int)($_GET['id'] ?? 0);
    $a = (new AgencyModel())->getById($id);
    if (!$a) { Flash::set('error', "Agence introuvable."); redirect('admin/agences'); }
    $this->renderAdmin('admin/agencies_edit', ['agency'=>$a, 'errors'=>[]], 'Modifier agence');
  }
/**
 * Met à jour une agence (POST + CSRF).
 * @return void
 */
  public function agencyUpdate(): void {
    $this->requireAdmin();
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !CSRF::verify($_POST['_token'] ?? null)) {
      Flash::set('error', "Requête invalide."); redirect('admin/agences');
    }
    $id = (int)($_POST['id'] ?? 0);
    $name = trim((string)($_POST['name'] ?? ''));
    $errors = [];
    if ($id <= 0) $errors['id']="ID invalide.";
    if ($name === '') $errors['name']="Nom obligatoire.";
    if (!empty($errors)) {
      $this->renderAdmin('admin/agencies_edit', ['agency'=>['id'=>$id,'name'=>$name],'errors'=>$errors], 'Modifier agence'); return;
    }
    $ok = (new AgencyModel())->update($id, $name);
    if (!$ok) { Flash::set('error', "Mise à jour impossible (doublon ?)"); redirect('admin/agences/modifier?id='.$id); }
    Flash::set('success', "Agence mise à jour.");
    redirect('admin/agences');
  }
/**
 * Supprime une agence (POST + CSRF).
 * @return void
 */

  public function agencyDelete(): void {
    $this->requireAdmin();
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !CSRF::verify($_POST['_token'] ?? null)) {
      Flash::set('error', "Requête invalide."); redirect('admin/agences');
    }
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { Flash::set('error', "ID invalide."); redirect('admin/agences'); }
    $ok = (new AgencyModel())->delete($id);
    if ($ok) Flash::set('success', "Agence supprimée.");
    else     Flash::set('error',   "Suppression impossible (agence utilisée par des trajets ?).");
    redirect('admin/agences');
  }
/**
 * Liste les trajets.
 * @return void
 */

  public function trips(): void {
    $trips = (new TripModel())->findAllWithJoins(300);
    $this->renderAdmin('admin/trips', ['trips'=>$trips], 'Trajets');
  }
/**
 * Supprime un trajet (POST + CSRF).
 * @return void
 */
  public function tripDelete(): void {
    $this->requireAdmin();
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !CSRF::verify($_POST['_token'] ?? null)) {
      Flash::set('error', "Requête invalide."); redirect('admin/trajets');
    }
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { Flash::set('error', "ID invalide."); redirect('admin/trajets'); }
    $ok = (new TripModel())->deleteByAdmin($id);
    Flash::set($ok ? 'success' : 'error', $ok ? "Trajet supprimé." : "Suppression impossible.");
    redirect('admin/trajets');
  }
}
