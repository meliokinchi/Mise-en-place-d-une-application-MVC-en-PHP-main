<?php
/**
 * Touche pas au klaxon — covoit-mvc/views/admin/users.php
 *
 * @author Bilal
 */
?>
<main class="container py-5">
  <h1 class="h2 mb-3">Utilisateurs</h1>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Email</th>
          <th>Téléphone</th>
          <th>Admin</th>
          <th>Créé</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u['last_name']) ?></td>
          <td><?= htmlspecialchars($u['first_name']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['phone'] ?? '—') ?></td>
          <td><?= ($u['is_admin'] ? 'Oui' : 'Non') ?></td>
          <td><?= htmlspecialchars($u['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
