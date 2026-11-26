<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/Models/UserModel.php
 *
 * @package App.Models
 * @author Bilal
 */

namespace App\Models;

use App\Core\Database;
use PDO;
/**
 * Accès aux utilisateurs (lecture, liste, mise à jour du hash de mot de passe).
 */

final class UserModel {
/**
 * Retourne un utilisateur par email.
 * @param string $email
 * @return ?array
 */
  public function getByEmail(string $email): ?array {
    $sql = "SELECT id, first_name, last_name, phone, email, password_hash, is_admin
            FROM users WHERE email = :email LIMIT 1";
    $stmt = Database::getConnection()->prepare($sql);
    $stmt->execute([':email' => $email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
  }
/**
 * Retourne un enregistrement par identifiant.
 * @param int $id
 * @return ?array
 */
  public function getById(int $id): ?array {
    $sql = "SELECT id, first_name, last_name, phone, email, is_admin
            FROM users WHERE id = :id LIMIT 1";
    $stmt = Database::getConnection()->prepare($sql);
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
  }
/**
 * Retourne la liste complète.
 * @return array
 */
  public function all(): array {
    $sql = "SELECT id, first_name, last_name, phone, email, is_admin, created_at
            FROM users ORDER BY last_name, first_name";
    return Database::getConnection()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }
}
