<?php
/**
 * Touche pas au klaxon — covoit-mvc/views/admin/agencies_edit.php
 *
 * @author Bilal
 */
use App\Core\CSRF;
$errors = $errors ?? [];
function invalid($k){ return isset($GLOBALS['errors'][$k]) ? ' is-invalid' : ''; }
function err($k){ return isset($GLOBALS['errors'][$k]) ? '<div class="invalid-feedback">'.$GLOBALS['errors'][$k].'</div>' : ''; }
?>
<main class="container py-5" style="max-width: 560px;">
  <h1 class="h2 mb-3">Modifier l’agence</h1>
  <form method="post" action="<?= htmlspecialchars(url('admin/agences/modifier')) ?>">
    <?= CSRF::input() ?>
    <input type="hidden" name="id" value="<?= (int)$agency['id'] ?>">
    <div class="mb-3">
      <label class="form-label">Nom de l’agence</label>
      <input class="form-control<?= invalid('name') ?>" name="name" value="<?= htmlspecialchars($agency['name'] ?? '') ?>" required>
      <?= err('name') ?>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-primary">Enregistrer</button>
      <a class="btn btn-outline-secondary" href="<?= htmlspecialchars(url('admin/agences')) ?>">Annuler</a>
    </div>
  </form>
</main>
