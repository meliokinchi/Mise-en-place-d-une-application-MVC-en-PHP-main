<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/Core/Auth.php
 *
 * @package App.Core
 * @author Bilal
 */

namespace App\Core;

use App\Models\UserModel;
/**
 * Service d’authentification basé session (login/logout, utilisateur courant, rôle admin).
 */

final class Auth {
  private const KEY = 'auth_user_id';

  public static function login(string $email, string $password): bool {
    $email = trim(mb_strtolower($email));
    $user = (new UserModel())->getByEmail($email);
    if (!$user || empty($user['password_hash'])) return false;
    if (!password_verify($password, $user['password_hash'])) return false;

    $_SESSION[self::KEY] = (int)$user['id'];
    return true;
  }

  public static function logout(): void {
    unset($_SESSION[self::KEY]);
  }

  public static function check(): bool {
    return isset($_SESSION[self::KEY]);
  }

  public static function id(): ?int {
    return self::check() ? (int)$_SESSION[self::KEY] : null;
  }

  public static function user(): ?array {
    $id = self::id();
    if (!$id) return null;
    return (new UserModel())->getById($id);
  }

  public static function isAdmin(): bool {
  $u = self::user();
  return (bool)($u['is_admin'] ?? false);
  }

}
