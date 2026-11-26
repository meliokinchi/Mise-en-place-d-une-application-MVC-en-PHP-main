<?php
/**
 * Touche pas au klaxon — covoit-mvc/views/admin/trips.php
 *
 * @author Bilal
 */
?>
<main class="container py-5">
  <h1 class="h2 mb-3">Trajets</h1>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Départ</th><th>Arrivée</th>
          <th>GDH départ</th><th>GDH arrivée</th>
          <th>Places</th><th>Auteur</th><th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($trips as $t): ?>
        <?php $free=(int)$t['seats_available']; $total=(int)$t['seats_total']; ?>
        <tr>
          <td><?= htmlspecialchars($t['departure_agency']) ?></td>
          <td><?= htmlspecialchars($t['arrival_agency']) ?></td>
          <td><?= date('d/m/Y H:i', strtotime($t['departure_at'])) ?></td>
          <td><?= date('d/m/Y H:i', strtotime($t['arrival_at'])) ?></td>
          <td><span class="badge <?= $free<=1?'bg-navy':'bg-sage' ?>"><?= $free ?>/<?= $total ?></span></td>
          <td><?= htmlspecialchars(trim(($t['creator_first_name'] ?? '').' '.($t['creator_last_name'] ?? '')) ?: '—') ?></td>
          <td class="text-end">
            <form method="post" action="<?= htmlspecialchars(url('admin/trajets/supprimer')) ?>" class="d-inline"
                  onsubmit="return confirm('Supprimer ce trajet ?');">
              <?= \App\Core\CSRF::input() ?>
              <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
              <button class="btn btn-sm btn-outline-danger">Supprimer</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
