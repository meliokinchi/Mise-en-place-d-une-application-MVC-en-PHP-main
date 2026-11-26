<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/Models/AgencyModel.php
 *
 * @package App.Models
 * @author Bilal
 */

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;
/**
 * Accès aux agences (CRUD).
 */

final class AgencyModel {
/**
 * Retourne la liste complète.
 * @return array
 */
  public function all(): array {
    $sql = "SELECT id, name FROM agencies ORDER BY name ASC";
    return Database::getConnection()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }
/**
 * Retourne un enregistrement par identifiant.
 * @param int $id
 * @return ?array
 */
  public function getById(int $id): ?array {
    $stmt = Database::getConnection()->prepare("SELECT id, name FROM agencies WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
  }
/**
 * Indique si l’entité existe.
 * @param int $id
 * @return bool
 */
  public function exists(int $id): bool {
    $stmt = Database::getConnection()->prepare("SELECT 1 FROM agencies WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return (bool)$stmt->fetchColumn();
  }
/**
 * Crée un enregistrement.
 * @param string $name
 * @return int|false
 */
  public function create(string $name): int|false {
    $stmt = Database::getConnection()->prepare("INSERT INTO agencies (name) VALUES (:n)");
    try {
      $ok = $stmt->execute([':n' => trim($name)]);
      return $ok ? (int)Database::getConnection()->lastInsertId() : false;
    } catch (PDOException $e) { return false; } // ex: doublon
  }
/**
 * Met à jour l’entité après validations.
 * @param int $id
 * @param string $name
 * @return bool
 */
  public function update(int $id, string $name): bool {
    $stmt = Database::getConnection()->prepare("UPDATE agencies SET name = :n WHERE id = :id");
    try {
      $stmt->execute([':n' => trim($name), ':id' => $id]);
      return $stmt->rowCount() >= 0;
    } catch (PDOException $e) { return false; } // ex: doublon
  }
/**
 * Supprime l’entité (POST + CSRF).
 * @param int $id
 * @return bool
 */
  public function delete(int $id): bool {
    $stmt = Database::getConnection()->prepare("DELETE FROM agencies WHERE id = :id");
    try {
      $stmt->execute([':id' => $id]);
      return $stmt->rowCount() === 1;
    } catch (PDOException $e) { return false; } // FK RESTRICT (trajets liés)
  }
}
