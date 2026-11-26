<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/Core/Flash.php
 *
 * @package App.Core
 * @author Bilal
 */

namespace App\Core;
/**
 * Messages flash basés session (set/get).
 */

final class Flash {
  public static function set(string $key, string $message): void {
    $_SESSION['flash'][$key] = $message;
  }
  public static function get(string $key): ?string {
    if (!isset($_SESSION['flash'][$key])) return null;
    $msg = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $msg;
  }
}
