<?php $me = \App\Core\Auth::user(); 
/**
 * Touche pas au klaxon — covoit-mvc/views/partials/header_admin.php
 *
 * @author Bilal
 */
?>
<header>
  <nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
      <!-- Lien marque vers le dashboard admin -->
      <a class="navbar-brand fw-semibold" href="<?= htmlspecialchars(url('admin')) ?>">Touche pas au klaxon</a>

      <div class="ms-auto d-flex align-items-center gap-3 flex-wrap">
        <!-- Bouton retour au site public -->
        <a class="btn btn-outline-dark" href="<?= htmlspecialchars(url()) ?>">Site public</a>

        <!-- Menu admin -->
        <a class="nav-link" href="<?= htmlspecialchars(url('admin/utilisateurs')) ?>">Utilisateurs</a>
        <a class="nav-link" href="<?= htmlspecialchars(url('admin/agences')) ?>">Agences</a>
        <a class="nav-link" href="<?= htmlspecialchars(url('admin/trajets')) ?>">Trajets</a>

        <!-- Identité + Déconnexion -->
        <span class="small text-slate">
          <?= htmlspecialchars(($me['first_name'] ?? '') . ' ' . ($me['last_name'] ?? '')) ?> (Admin)
        </span>
        <a class="btn btn-outline-secondary" href="<?= htmlspecialchars(url('deconnexion')) ?>">Déconnexion</a>
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
