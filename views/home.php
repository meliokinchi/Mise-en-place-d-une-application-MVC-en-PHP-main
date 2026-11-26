<?php $meId = \App\Core\Auth::id(); 
/** 
 * Touche pas au klaxon — covoit-mvc/views/home.php
 *
 * @author Bilal
*/
?>
<main class="container py-5">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h2 m-0">Liste des trajets</h1>
  </div>

  <?php if (!$meId): ?>
    <div class="alert alert-info">
      Pour obtenir plus d'informations sur un trajet, veuillez vous connecter.
    </div>
  <?php endif; ?>


  <?php if (empty($trips)): ?>
    <div class="alert alert-info">Aucun trajet disponible pour le moment.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Départ</th>
            <th>Arrivée</th>
            <th>Départ (GDH)</th>
            <th>Arrivée (GDH)</th>
            <th>Places</th>
            <?php if ($meId): ?><th class="text-end">Actions</th><?php endif; ?>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($trips as $t): ?>
          <?php
            $free  = (int)$t['seats_available'];
            $total = (int)$t['seats_total'];
            $badgeClass = $free <= 1 ? 'bg-navy' : 'bg-sage';
            $creatorId = (int)($t['creator_id'] ?? 0);
            $creatorName = trim(($t['creator_first_name'] ?? '') . ' ' . ($t['creator_last_name'] ?? ''));
            $creatorPhone = $t['creator_phone'] ?? '';
            $creatorEmail = $t['creator_email'] ?? '';
          ?>
          <tr>
            <td class="fw-semibold"><?= htmlspecialchars($t['departure_agency']) ?></td>
            <td><?= htmlspecialchars($t['arrival_agency']) ?></td>
            <td><span class="text-slate"><?= date('d/m/Y H:i', strtotime($t['departure_at'])) ?></span></td>
            <td><span class="text-slate"><?= date('d/m/Y H:i', strtotime($t['arrival_at'])) ?></span></td>
            <td>
              <span class="badge <?= $badgeClass ?>"><?= $free ?>/<?= $total ?></span>
            </td>

            <?php if ($meId): ?>
            <td class="text-end">
              <!-- Bouton Infos (modal) -->
              <button
                type="button"
                class="btn btn-sm btn-primary me-1"
                data-bs-toggle="modal"
                data-bs-target="#tripInfoModal"
                data-trip-id="<?= (int)$t['id'] ?>"
                data-creator-name="<?= htmlspecialchars($creatorName ?: '—', ENT_QUOTES) ?>"
                data-creator-phone="<?= htmlspecialchars($creatorPhone ?: '—', ENT_QUOTES) ?>"
                data-creator-email="<?= htmlspecialchars($creatorEmail ?: '—', ENT_QUOTES) ?>"
                data-seats-total="<?= (int)$t['seats_total'] ?>"
              >
                Infos
              </button>

              <?php if ($meId === $creatorId): ?>
                <a class="btn btn-sm btn-outline-primary me-1"
                   href="<?= htmlspecialchars(url('trajets/modifier') . '?id=' . (int)$t['id']) ?>">
                  Modifier
                </a>

                <form method="post" action="<?= htmlspecialchars(url('trajets/supprimer')) ?>" class="d-inline"
                      onsubmit="return confirm('Supprimer ce trajet ? Cette action est irréversible.');">
                  <?= \App\Core\CSRF::input() ?>
                  <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                  <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                </form>
              <?php endif; ?>
            </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</main>

<!-- Modal Infos Trajet (réutilisable) -->
<div class="modal fade" id="tripInfoModal" tabindex="-1" aria-labelledby="tripInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="tripInfoModalLabel">Informations du trajet</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <dl class="row mb-0">
          <dt class="col-sm-5">Proposé par</dt>
          <dd class="col-sm-7" id="ti-creator">—</dd>

          <dt class="col-sm-5">Téléphone</dt>
          <dd class="col-sm-7" id="ti-phone">—</dd>

          <dt class="col-sm-5">Email</dt>
          <dd class="col-sm-7" id="ti-email">—</dd>

          <dt class="col-sm-5">Places totales</dt>
          <dd class="col-sm-7" id="ti-seats">—</dd>
        </dl>
      </div>
    </div>
  </div>
</div>


