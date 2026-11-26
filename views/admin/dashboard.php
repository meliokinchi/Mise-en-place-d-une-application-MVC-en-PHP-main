<main class="container py-5">
  <h1 class="h2 mb-4">Tableau de bord</h1>
  <div class="row g-3">
    <div class="col-md-4">
      <a class="text-decoration-none" href="<?= htmlspecialchars(url('admin/utilisateurs')) ?>">
        <div class="card h-100">
          <div class="card-body">
            <h2 class="h5">Utilisateurs</h2>
            <p class="text-muted mb-0">Lister tous les utilisateurs.</p>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a class="text-decoration-none" href="<?= htmlspecialchars(url('admin/agences')) ?>">
        <div class="card h-100">
          <div class="card-body">
            <h2 class="h5">Agences</h2>
            <p class="text-muted mb-0">Lister, créer, modifier, supprimer.</p>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a class="text-decoration-none" href="<?= htmlspecialchars(url('admin/trajets')) ?>">
        <div class="card h-100">
          <div class="card-body">
            <h2 class="h5">Trajets</h2>
            <p class="text-muted mb-0">Lister & supprimer un trajet.</p>
          </div>
        </div>
      </a>
    </div>
  </div>
</main>
