<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/Controllers/AuthController.php
 *
 * @package App.Controllers
 * @author Bilal
 */

namespace App\Controllers;

use App\Core\Auth;
use App\Core\CSRF;
use App\Core\Flash;
/**
 * Gestion de l’authentification (affichage login, soumission, logout).
 */

final class AuthController extends BaseController {
/**
 * Affiche le formulaire de connexion.
 * @return void
 */
  public function loginForm(): void {
    // Si déjà connecté, retourne à l'accueil
    if (Auth::check()) { redirect(); }
    $this->render('auth/login', [], 'Connexion');
  }
/**
 * Traite la soumission du formulaire de connexion.
 * @return void
 */

  public function loginSubmit(): void {
    // Vérifier méthode + CSRF
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !CSRF::verify($_POST['_token'] ?? null)) {
      Flash::set('error', "Requête invalide, merci de réessayer.");
      redirect('connexion');
    }

    $email = trim($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
      Flash::set('error', "Veuillez renseigner l’email et le mot de passe.");
      redirect('connexion');
    }

    if (!Auth::login($email, $password)) {
      Flash::set('error', "Identifiants incorrects.");
      redirect('connexion');
    }

    Flash::set('success', "Bienvenue !");
    redirect(); // accueil
  }
/**
 * Déconnecte l’utilisateur.
 * @return void
 */

  public function logout(): void {
    Auth::logout();
    Flash::set('success', "Vous êtes déconnecté.");
    redirect();
  }
}
