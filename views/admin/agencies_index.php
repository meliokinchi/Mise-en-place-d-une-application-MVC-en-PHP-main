<?php
/**
 * Touche pas au klaxon — covoit-mvc/views/admin/agencies_index.php
 *
 * @author Bilal
 */
?>
<main class="container py-5">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h2 m-0">Agences</h1>
    <a class="btn btn-primary" href="<?= htmlspecialchars(url('admin/agences/nouveau')) ?>">Nouvelle agence</a>
  </div>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead><tr><th>Nom</th><th class="text-end">Actions</th></tr></thead>
      <tbody>
      <?php foreach ($agencies as $a): ?>
        <tr>
          <td><?= htmlspecialchars($a['name']) ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary me-2"
               href="<?= htmlspecialchars(url('admin/agences/modifier').'?id='.(int)$a['id']) ?>">Modifier</a>
            <form method="post" action="<?= htmlspecialchars(url('admin/agences/supprimer')) ?>" class="d-inline"
                  onsubmit="return confirm('Supprimer cette agence ?');">
              <?= \App\Core\CSRF::input() ?>
              <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
              <button class="btn btn-sm btn-outline-danger">Supprimer</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
