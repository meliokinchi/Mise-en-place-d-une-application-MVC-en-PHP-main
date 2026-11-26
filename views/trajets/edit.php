<?php
/**
 * Touche pas au klaxon — covoit-mvc/views/trajets/edit.php
 *
 * @author Bilal
 */
use App\Core\CSRF;
$old = $old ?? [];
$errors = $errors ?? [];
function oldv($k, $d=''){ return htmlspecialchars((string)($GLOBALS['old'][$k] ?? $d)); }
function invalid($k){ return isset($GLOBALS['errors'][$k]) ? ' is-invalid' : ''; }
function err($k){ return isset($GLOBALS['errors'][$k]) ? '<div class="invalid-feedback">'.$GLOBALS['errors'][$k].'</div>' : ''; }
?>
<main class="container py-5" style="max-width: 720px;">
  <h1 class="h2 mb-4">Modifier un trajet</h1>

  <form method="post" action="<?= htmlspecialchars(url('trajets/modifier')) ?>" novalidate>
    <?= CSRF::input() ?>
    <input type="hidden" name="id" value="<?= oldv('id') ?>">

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Agence de départ</label>
        <select name="dep_agency" class="form-select<?= invalid('dep_agency') ?>" required>
          <option value="">— Choisir —</option>
          <?php foreach ($agencies as $a): ?>
            <option value="<?= (int)$a['id'] ?>" <?= (int)($old['dep_agency'] ?? 0) === (int)$a['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($a['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?= err('dep_agency') ?>
      </div>

      <div class="col-md-6">
        <label class="form-label">Agence d’arrivée</label>
        <select name="arr_agency" class="form-select<?= invalid('arr_agency') ?>" required>
          <option value="">— Choisir —</option>
          <?php foreach ($agencies as $a): ?>
            <option value="<?= (int)$a['id'] ?>" <?= (int)($old['arr_agency'] ?? 0) === (int)$a['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($a['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?= err('arr_agency') ?>
      </div>

      <div class="col-md-6">
        <label class="form-label">Départ (date & heure)</label>
        <input type="datetime-local" name="dep_at" class="form-control<?= invalid('dep_at') ?>"
               value="<?= oldv('dep_at') ?>" required>
        <?= err('dep_at') ?>
      </div>

      <div class="col-md-6">
        <label class="form-label">Arrivée (date & heure)</label>
        <input type="datetime-local" name="arr_at" class="form-control<?= invalid('arr_at') ?>"
               value="<?= oldv('arr_at') ?>" required>
        <?= err('arr_at') ?>
      </div>

      <div class="col-md-6">
        <label class="form-label">Places totales</label>
        <input type="number" name="seats_total" min="1" class="form-control<?= invalid('seats_total') ?>"
               value="<?= oldv('seats_total', '1') ?>" required>
        <?= err('seats_total') ?>
      </div>

      <div class="col-md-6">
        <label class="form-label">Places disponibles</label>
        <input type="number" name="seats_avail" min="0" class="form-control<?= invalid('seats_avail') ?>"
               value="<?= oldv('seats_avail', '0') ?>" required>
        <?= err('seats_avail') ?>
      </div>
    </div>

    <div class="mt-4 d-flex gap-2">
      <button class="btn btn-primary">Enregistrer les modifications</button>
      <a class="btn btn-outline-secondary" href="<?= htmlspecialchars(url()) ?>">Annuler</a>
    </div>
  </form>
</main>
