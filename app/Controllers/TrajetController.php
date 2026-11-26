<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/Controllers/TrajetController.php
 *
 * @package App.Controllers
 * @author Bilal
 */

namespace App\Controllers;

use App\Models\TripModel;
/**
 * Gestion des trajets côté utilisateur (créer / modifier / supprimer).
 */

final class TrajetController extends BaseController {

/**
 * Affiche le formulaire d’édition.
 * @return void
 */

public function editForm(): void {
  $uid = $this->requireLogin();

  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) {
    \App\Core\Flash::set('error', "Trajet introuvable.");
    redirect('trajets');
  }

  $trip = (new \App\Models\TripModel())->findByIdOwned($id, $uid);
  if (!$trip) {
    \App\Core\Flash::set('error', "Vous n’êtes pas autorisé à modifier ce trajet.");
    redirect('trajets');
  }

  $agencies = (new \App\Models\AgencyModel())->all();

  // Pré-remplissage (datetime-local attend Y-m-dTH:i)
  $old = [
    'id'          => (int)$trip['id'],
    'dep_agency'  => (int)$trip['departure_agency_id'],
    'arr_agency'  => (int)$trip['arrival_agency_id'],
    'dep_at'      => date('Y-m-d\TH:i', strtotime($trip['departure_at'])),
    'arr_at'      => date('Y-m-d\TH:i', strtotime($trip['arrival_at'])),
    'seats_total' => (int)$trip['seats_total'],
    'seats_avail' => (int)$trip['seats_available'],
  ];

  $this->render('trajets/edit', [
    'agencies' => $agencies,
    'old' => $old,
    'errors' => []
  ], 'Modifier un trajet');
}
/**
 * Met à jour l’entité après validations.
 * @return void
 */

public function update(): void {
  $uid = $this->requireLogin();

  if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !\App\Core\CSRF::verify($_POST['_token'] ?? null)) {
    \App\Core\Flash::set('error', "Requête invalide.");
    redirect('trajets');
  }

  $old = [
    'id'          => (int)($_POST['id'] ?? 0),
    'dep_agency'  => (int)($_POST['dep_agency'] ?? 0),
    'arr_agency'  => (int)($_POST['arr_agency'] ?? 0),
    'dep_at'      => (string)($_POST['dep_at'] ?? ''),
    'arr_at'      => (string)($_POST['arr_at'] ?? ''),
    'seats_total' => (int)($_POST['seats_total'] ?? 0),
    'seats_avail' => (int)($_POST['seats_avail'] ?? 0),
  ];
  if ($old['id'] <= 0) {
    \App\Core\Flash::set('error', "Trajet introuvable.");
    redirect('trajets');
  }

  $errors = [];
  $agencyModel = new \App\Models\AgencyModel();

  // Validations agences
  if ($old['dep_agency'] <= 0 || !$agencyModel->exists($old['dep_agency'])) {
    $errors['dep_agency'] = "Agence de départ invalide.";
  }
  if ($old['arr_agency'] <= 0 || !$agencyModel->exists($old['arr_agency'])) {
    $errors['arr_agency'] = "Agence d’arrivée invalide.";
  }
  if (($errors['dep_agency'] ?? null) === null && ($errors['arr_agency'] ?? null) === null) {
    if ($old['dep_agency'] === $old['arr_agency']) {
      $errors['arr_agency'] = "Départ et arrivée doivent être différents.";
    }
  }

  // Datetimes
  $depDT = \DateTime::createFromFormat('Y-m-d\TH:i', $old['dep_at']) ?: \DateTime::createFromFormat('Y-m-d H:i', $old['dep_at']);
  $arrDT = \DateTime::createFromFormat('Y-m-d\TH:i', $old['arr_at']) ?: \DateTime::createFromFormat('Y-m-d H:i', $old['arr_at']);
  if (!$depDT) { $errors['dep_at'] = "Date/heure de départ invalide."; }
  if (!$arrDT) { $errors['arr_at'] = "Date/heure d’arrivée invalide."; }
  if ($depDT && $arrDT && $arrDT < $depDT) {
    $errors['arr_at'] = "On ne peut pas arriver avant de partir.";
  }

  // Places
  if ($old['seats_total'] < 1) {
    $errors['seats_total'] = "Au moins 1 place au total.";
  }
  if ($old['seats_avail'] < 0) {
    $errors['seats_avail'] = "Places disponibles ne peut pas être négatif.";
  }
  if ($old['seats_total'] >= 1 && $old['seats_avail'] > $old['seats_total']) {
    $errors['seats_avail'] = "Disponibles > total : incohérent.";
  }

  if (!empty($errors)) {
    $agencies = $agencyModel->all();
    $this->render('trajets/edit', ['agencies' => $agencies, 'old' => $old, 'errors' => $errors], 'Modifier un trajet');
    return;
  }

  // Mise à jour (autorisation via WHERE id + owner)
  $ok = (new \App\Models\TripModel())->updateOwned(
    $old['id'], $uid,
    $old['dep_agency'], $depDT->format('Y-m-d H:i:s'),
    $old['arr_agency'], $arrDT->format('Y-m-d H:i:s'),
    $old['seats_total'], $old['seats_avail']
  );

  if (!$ok) {
    \App\Core\Flash::set('error', "Aucune modification ou droits insuffisants.");
    redirect('trajets/modifier?id=' . $old['id']);
  }

  \App\Core\Flash::set('success', "Trajet mis à jour avec succès.");
  redirect(); // Accueil
}
/**
 * Supprime l’entité (POST + CSRF).
 * @return void
 */


public function delete(): void {
  $uid = $this->requireLogin();
  if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !\App\Core\CSRF::verify($_POST['_token'] ?? null)) {
    \App\Core\Flash::set('error', "Requête invalide.");
    redirect();
  }
  $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
  if ($id <= 0) {
    \App\Core\Flash::set('error', "Identifiant de trajet manquant.");
    redirect();
  }
  $ok = (new \App\Models\TripModel())->deleteOwned($id, $uid);
  if ($ok) {
    \App\Core\Flash::set('success', "Trajet supprimé.");
  } else {
    \App\Core\Flash::set('error', "Suppression impossible (droits insuffisants ou trajet introuvable).");
  }
  redirect();
}
/**
 * Affiche le formulaire de création de trajet.
 * @return void
 */

public function newForm(): void {
  $this->requireLogin();
  $agencies = (new \App\Models\AgencyModel())->all();
  $this->render('trajets/new', ['agencies' => $agencies, 'old' => [], 'errors' => []], 'Publier un trajet');
}
/**
 * Crée un enregistrement.
 * @return void
 */

public function create(): void {
  $uid = $this->requireLogin();

  if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !\App\Core\CSRF::verify($_POST['_token'] ?? null)) {
    \App\Core\Flash::set('error', "Requête invalide.");
    redirect('trajets/nouveau');
  }

  // Récup inputs
  $old = [
    'dep_agency' => (int)($_POST['dep_agency'] ?? 0),
    'arr_agency' => (int)($_POST['arr_agency'] ?? 0),
    'dep_at'     => (string)($_POST['dep_at'] ?? ''),
    'arr_at'     => (string)($_POST['arr_at'] ?? ''),
    'seats_total'=> (int)($_POST['seats_total'] ?? 0),
    'seats_avail'=> (int)($_POST['seats_avail'] ?? 0),
  ];
  $errors = [];
  $agencyModel = new \App\Models\AgencyModel();

  // Validations
  if ($old['dep_agency'] <= 0 || !$agencyModel->exists($old['dep_agency'])) {
    $errors['dep_agency'] = "Agence de départ invalide.";
  }
  if ($old['arr_agency'] <= 0 || !$agencyModel->exists($old['arr_agency'])) {
    $errors['arr_agency'] = "Agence d’arrivée invalide.";
  }
  if (($errors['dep_agency'] ?? null) === null && ($errors['arr_agency'] ?? null) === null) {
    if ($old['dep_agency'] === $old['arr_agency']) {
      $errors['arr_agency'] = "Départ et arrivée doivent être différents.";
    }
  }

  // Datetimes (HTML datetime-local -> Y-m-d H:i:s)
  $depDT = \DateTime::createFromFormat('Y-m-d\TH:i', $old['dep_at']) ?: \DateTime::createFromFormat('Y-m-d H:i', $old['dep_at']);
  $arrDT = \DateTime::createFromFormat('Y-m-d\TH:i', $old['arr_at']) ?: \DateTime::createFromFormat('Y-m-d H:i', $old['arr_at']);
  if (!$depDT) { $errors['dep_at'] = "Date/heure de départ invalide."; }
  if (!$arrDT) { $errors['arr_at'] = "Date/heure d’arrivée invalide."; }
  if ($depDT && $arrDT && $arrDT < $depDT) {
    $errors['arr_at'] = "On ne peut pas arriver avant de partir.";
  }

  // Places
  if ($old['seats_total'] < 1) {
    $errors['seats_total'] = "Au moins 1 place au total.";
  }
  if ($old['seats_avail'] < 0) {
    $errors['seats_avail'] = "Places disponibles ne peut pas être négatif.";
  }
  if ($old['seats_total'] >= 1 && $old['seats_avail'] > $old['seats_total']) {
    $errors['seats_avail'] = "Disponibles > total : incohérent.";
  }

  if (!empty($errors)) {
    $agencies = $agencyModel->all();
    $this->render('trajets/new', ['agencies' => $agencies, 'old' => $old, 'errors' => $errors], 'Publier un trajet');
    return;
  }

  // Insert
  $tripId = (new \App\Models\TripModel())->create(
    $uid,
    $old['dep_agency'],
    $depDT->format('Y-m-d H:i:s'),
    $old['arr_agency'],
    $arrDT->format('Y-m-d H:i:s'),
    $old['seats_total'],
    $old['seats_avail']
  );

  if ($tripId === false) {
    \App\Core\Flash::set('error', "Erreur lors de l’enregistrement du trajet.");
    redirect('trajets/nouveau');
  }

  \App\Core\Flash::set('success', "Trajet publié avec succès.");
  redirect(); // redirection vers accueil
}


}
