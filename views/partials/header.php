<?php $me = \App\Core\Auth::user(); 
/**
 * Touche pas au klaxon — covoit-mvc/views/partials/header.php
 *
 * @author Bilal
 */
?>
<header>
  <nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
      <a class="navbar-brand fw-semibold" href="<?= htmlspecialchars(url()) ?>">Touche pas au klaxon</a>

      <div class="ms-auto d-flex align-items-center gap-2">
        <?php if ($me): ?>
          <a class="btn btn-primary" href="<?= htmlspecialchars(url('trajets/nouveau')) ?>">
            Publier un trajet
          </a>

          <?php if (\App\Core\Auth::isAdmin()): ?>
            <a class="btn btn-outline-dark" href="<?= htmlspecialchars(url('admin')) ?>">
              Accès admin
            </a>
          <?php else: ?>
            <button class="btn btn-outline-dark" type="button" disabled title="Réservé à l’administrateur">
              Accès admin
            </button>
          <?php endif; ?>

          <span class="small text-slate">
            <?= htmlspecialchars($me['first_name'] . ' ' . $me['last_name']) ?>
          </span>
          <a class="btn btn-outline-secondary" href="<?= htmlspecialchars(url('deconnexion')) ?>">
            Déconnexion
          </a>
        <?php else: ?>
          <a class="btn btn-primary" href="<?= htmlspecialchars(url('connexion')) ?>">
            Connexion
          </a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <?php if ($msg = \App\Core\Flash::get('success')): ?>
    <div class="container mt-3"><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div></div>
  <?php endif; ?>
  <?php if ($msg = \App\Core\Flash::get('error')): ?>
    <div class="container mt-3"><div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div></div>
  <?php endif; ?>
</header>
