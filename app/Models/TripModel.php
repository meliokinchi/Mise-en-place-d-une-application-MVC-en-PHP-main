<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/Models/TripModel.php
 *
 * @package App.Models
 * @author Bilal
 */

namespace App\Models;

use App\Core\Database;
use PDO;
/**
 * Accès aux trajets (lecture/écriture et opérations propriétaires/admin).
 */

final class TripModel {
/**
 * Retourne les prochains trajets (triés).
 * @param int $limit
 * @return array
 */
    public function findUpcoming(int $limit = 20): array
    {
        $sql = <<<SQL
        SELECT
          t.id, t.departure_at, t.arrival_at,
          t.seats_total, t.seats_available,
          da.name AS departure_agency,
          aa.name AS arrival_agency
        FROM trips t
        JOIN agencies da ON da.id = t.departure_agency_id
        JOIN agencies aa ON aa.id = t.arrival_agency_id
        WHERE t.departure_at >= NOW()
        ORDER BY t.departure_at ASC
        LIMIT :limit
        SQL;

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
/**
 * Retourne les prochains trajets avec places disponibles.
 * @param int $limit
 * @return array
 */

    public function findUpcomingAvailable(int $limit = 20): array
    {
        $sql = <<<SQL
        SELECT
          t.id, t.departure_at, t.arrival_at,
          t.seats_total, t.seats_available,
          da.name AS departure_agency,
          aa.name AS arrival_agency
        FROM trips t
        JOIN agencies da ON da.id = t.departure_agency_id
        JOIN agencies aa ON aa.id = t.arrival_agency_id
        WHERE t.departure_at >= NOW()
          AND t.seats_available > 0
        ORDER BY t.departure_at ASC
        LIMIT :limit
        SQL;

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
/**
 * Retourne les prochains trajets avec places disponibles et infos auteur.
 * @param int $limit
 * @return array
 */

    public function findUpcomingAvailableWithAuthor(int $limit = 20): array
    {
    $sql = <<<SQL
    SELECT
      t.id, t.departure_at, t.arrival_at,
      t.seats_total, t.seats_available,
      t.created_by_user_id AS creator_id,
      da.name AS departure_agency,
      aa.name AS arrival_agency,
      u.first_name AS creator_first_name,
      u.last_name  AS creator_last_name,
      u.phone      AS creator_phone,
      u.email      AS creator_email
    FROM trips t
    JOIN agencies da ON da.id = t.departure_agency_id
    JOIN agencies aa ON aa.id = t.arrival_agency_id
    LEFT JOIN users u ON u.id = t.created_by_user_id
    WHERE t.departure_at >= NOW()
      AND t.seats_available > 0
    ORDER BY t.departure_at ASC
    LIMIT :limit
    SQL;

    $pdo = \App\Core\Database::getConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
    }
/**
 * Supprime un trajet appartenant à un utilisateur.
 * @param int $tripId
 * @param int $ownerUserId
 * @return bool
 */

    public function deleteOwned(int $tripId, int $ownerUserId): bool
    {
        $sql = "DELETE FROM trips WHERE id = :id AND created_by_user_id = :uid";
        $pdo = \App\Core\Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $tripId, ':uid' => $ownerUserId]);
        return $stmt->rowCount() === 1;
    }
/**
 * Crée un enregistrement.
 * @param int $creatorId
 * @param int $departureAgencyId
 * @param string $departureAt
 * @param mixed $arrivalAgencyId
 * @param string $arrivalAt
 * @param mixed $seatsTotal
 * @param int $seatsAvailable
 * @return int|false
 */

    public function create(
      int $creatorId,
      int $departureAgencyId,
      string $departureAt,   // 'Y-m-d H:i:s'
      int $arrivalAgencyId,
      string $arrivalAt,     // 'Y-m-d H:i:s'
      int $seatsTotal,
      int $seatsAvailable
    ): int|false {
      $sql = "INSERT INTO trips
        (departure_agency_id, departure_at, arrival_agency_id, arrival_at, seats_total, seats_available, created_by_user_id)
        VALUES (:da, :dat, :aa, :aat, :st, :sa, :uid)";
      $pdo = \App\Core\Database::getConnection();
      $stmt = $pdo->prepare($sql);
      $ok = $stmt->execute([
        ':da'  => $departureAgencyId,
        ':dat' => $departureAt,
        ':aa'  => $arrivalAgencyId,
        ':aat' => $arrivalAt,
        ':st'  => $seatsTotal,
        ':sa'  => $seatsAvailable,
        ':uid' => $creatorId,
      ]);
      return $ok ? (int)$pdo->lastInsertId() : false;
    }
/**
 * Retourne un trajet appartenant à un utilisateur.
 * @param int $tripId
 * @param int $ownerUserId
 * @return ?array
 */

    public function findByIdOwned(int $tripId, int $ownerUserId): ?array {
    $sql = "SELECT id, departure_agency_id, departure_at, arrival_agency_id, arrival_at, seats_total, seats_available
            FROM trips WHERE id = :id AND created_by_user_id = :uid LIMIT 1";
    $pdo = \App\Core\Database::getConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $tripId, ':uid' => $ownerUserId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}
/**
 * Met à jour un trajet appartenant à un utilisateur.
 * @param int $tripId
 * @param int $ownerUserId
 * @param int $departureAgencyId
 * @param string $departureAt
 * @param int $arrivalAgencyId
 * @param string $arrivalAt
 * @param int $seatsTotal
 * @param int $seatsAvailable
 * @return bool
 */

public function updateOwned(
  int $tripId, int $ownerUserId,
  int $departureAgencyId, string $departureAt,
  int $arrivalAgencyId, string $arrivalAt,
  int $seatsTotal, int $seatsAvailable
): bool {
    $sql = "UPDATE trips
            SET departure_agency_id = :da,
                departure_at        = :dat,
                arrival_agency_id   = :aa,
                arrival_at          = :aat,
                seats_total         = :st,
                seats_available     = :sa
            WHERE id = :id AND created_by_user_id = :uid";
    $pdo = \App\Core\Database::getConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':da' => $departureAgencyId, ':dat' => $departureAt,
      ':aa' => $arrivalAgencyId,   ':aat' => $arrivalAt,
      ':st' => $seatsTotal,        ':sa'  => $seatsAvailable,
      ':id' => $tripId,            ':uid' => $ownerUserId
    ]);
    return $stmt->rowCount() === 1;
}
/**
 * Retourne tous les trajets avec jointures pour l’administration.
 * @param int $limit
 * @return array
 */

public function findAllWithJoins(int $limit = 200): array {
  $sql = "SELECT t.id, t.departure_at, t.arrival_at, t.seats_total, t.seats_available,
                 da.name AS departure_agency, aa.name AS arrival_agency,
                 u.first_name AS creator_first_name, u.last_name AS creator_last_name
          FROM trips t
          JOIN agencies da ON da.id = t.departure_agency_id
          JOIN agencies aa ON aa.id = t.arrival_agency_id
          LEFT JOIN users u ON u.id = t.created_by_user_id
          ORDER BY t.departure_at DESC
          LIMIT :limit";
  $pdo = \App\Core\Database::getConnection();
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll();
}
/**
 * Supprime un trajet (administration).
 * @param int $tripId
 * @return bool
 */

public function deleteByAdmin(int $tripId): bool {
  $pdo = \App\Core\Database::getConnection();
  $stmt = $pdo->prepare("DELETE FROM trips WHERE id = :id");
  $stmt->execute([':id' => $tripId]);
  return $stmt->rowCount() === 1;
}


}
