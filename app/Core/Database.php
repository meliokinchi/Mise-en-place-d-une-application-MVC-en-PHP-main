<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/Core/Database.php
 *
 * @package App.Core
 * @author Bilal
 */

namespace App\Core;

use PDO;
use PDOException;
/**
 * Fabrique de connexion PDO (singleton), configure les options et la timezone SQL.
 */

final class Database {
  private static ?PDO $pdo = null;

  public static function getConnection(): PDO {
    if (self::$pdo) return self::$pdo;

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
      self::$pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
      // Aligne la timezone SQL sur l'appli
      self::$pdo->exec("SET time_zone = '" . DB_TIMEZONE . "'");
    } catch (PDOException $e) {
      http_response_code(500);
      exit('Erreur de connexion BDD : ' . htmlspecialchars($e->getMessage()));
    }

    return self::$pdo;
  }
}
