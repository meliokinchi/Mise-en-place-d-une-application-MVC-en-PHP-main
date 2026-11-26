<?php
/**
 * Touche pas au klaxon — covoit-mvc/views/auth/login.php
 *
 * @author Bilal
 */
use App\Core\CSRF;
use App\Core\Flash;
$err = \App\Core\Flash::get('error');
?>
<main class="container py-5" style="max-width: 520px;">
  <h1 class="h2 mb-4">Connexion</h1>

  <?php if ($err): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
  <?php endif; ?>

  <form method="post" action="<?= htmlspecialchars(url('connexion')) ?>" novalidate>
    <?= CSRF::input() ?>
    <div class="mb-3">
      <label class="form-label">Email professionnel</label>
      <input class="form-control" type="email" name="email" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Mot de passe</label>
      <input class="form-control" type="password" name="password" required>
    </div>
    <button class="btn btn-primary">Se connecter</button>
  </form>
</main>
