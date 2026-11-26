<?php
/**
 * Touche pas au klaxon — covoit-mvc/views/trajets/new.php
 *
 * @author Bilal
 */

use App\Core\CSRF;

$old = $old ?? [];
$errors = $errors ?? [];

$escape = static fn (string $value): string =>
    htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

$oldv = static fn (string $key, string $default = ''): string =>
    $escape((string) ($old[$key] ?? $default));

$invalid = static fn (string $key): string =>
    isset($errors[$key]) ? ' is-invalid' : '';

$err = static fn (string $key): string =>
    isset($errors[$key])
        ? '<div class="invalid-feedback">' . $escape((string) $errors[$key]) . '</div>'
        : '';
?>

<main class="container py-5" style="max-width: 720px;">
  <h1 class="h2 mb-4">Publier un trajet</h1>

  <form method="post" action="<?= $escape(url('trajets/nouveau')) ?>" novalidate>
    <?= CSRF::input() ?>

    <div class="row g-3">
      <div class="col-md-6">
        <label for="dep_agency" class="form-label">Agence de départ</label>
        <select id="dep_agency" name="dep_agency"
                class="form-select<?= $invalid('dep_agency') ?>" required>
          <option value="">— Choisir —</option>
          <?php foreach ($agencies as $a): ?>
            <option value="<?= (int) $a['id'] ?>"
                <?= (int) ($old['dep_agency'] ?? 0) === (int) $a['id'] ? 'selected' : '' ?>>
              <?= $escape($a['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?= $err('dep_agency') ?>
      </div>

      <div class="col-md-6">
        <label for="arr_agency" class="form-label">Agence d’arrivée</label>
        <select id="arr_agency" name="arr_agency"
                class="form-select<?= $invalid('arr_agency') ?>" required>
          <option value="">— Choisir —</option>
          <?php foreach ($agencies as $a): ?>
            <option value="<?= (int) $a['id'] ?>"
                <?= (int) ($old['arr_agency'] ?? 0) === (int) $a['id'] ? 'selected' : '' ?>>
              <?= $escape($a['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?= $err('arr_agency') ?>
      </div>

      <div class="col-md-6">
        <label for="dep_at" class="form-label">Départ (date et heure)</label>
        <input id="dep_at" type="datetime-local" name="dep_at"
               class="form-control<?= $invalid('dep_at') ?>"
               value="<?= $oldv('dep_at') ?>" required>
        <?= $err('dep_at') ?>
      </div>

      <div class="col-md-6">
        <label for="arr_at" class="form-label">Arrivée (date et heure)</label>
        <input id="arr_at" type="datetime-local" name="arr_at"
               class="form-control<?= $invalid('arr_at') ?>"
               value="<?= $oldv('arr_at') ?>" required>
        <?= $err('arr_at') ?>
      </div>

      <div class="col-md-6">
        <label for="seats_total" class="form-label">Places totales</label>
        <input id="seats_total" type="number" name="seats_total" min="1"
               class="form-control<?= $invalid('seats_total') ?>"
               value="<?= $oldv('seats_total', '1') ?>" required>
        <?= $err('seats_total') ?>
      </div>

      <div class="col-md-6">
        <label for="seats_avail" class="form-label">Places disponibles</label>
        <input id="seats_avail" type="number" name="seats_avail" min="0"
               class="form-control<?= $invalid('seats_avail') ?>"
               value="<?= $oldv('seats_avail', '0') ?>" required>
        <?= $err('seats_avail') ?>
      </div>
    </div>

    <div class="mt-4 d-flex gap-2">
      <button type="submit" class="btn btn-primary">Publier le trajet</button>
      <a class="btn btn-outline-secondary" href="<?= $escape(url()) ?>">Annuler</a>
    </div>
  </form>
</main>