<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/Core/CSRF.php
 *
 * @package App.Core
 * @author Bilal
 */

namespace App\Core;
/**
 * Protection CSRF avec token en session et helper d’input caché.
 */

final class CSRF {
  public static function token(): string {
    if (empty($_SESSION['csrf'])) {
      $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
  }

  public static function input(): string {
    return '<input type="hidden" name="_token" value="' . htmlspecialchars(self::token(), ENT_QUOTES) . '">';
  }

  public static function verify(?string $token): bool {
    return is_string($token) && isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
  }
}
