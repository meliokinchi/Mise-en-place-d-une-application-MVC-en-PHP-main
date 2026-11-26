<footer class="mt-auto py-4 border-top text-center bg-white">
  <div class="container small text-muted">
    © <?= date('Y') ?> — CENEF - MVC PHP
  </div>
</footer>

<!-- Bootstrap JS (bundle = Popper inclus) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Pilotage robuste de la modale "Infos trajet" -->
<script>
(() => {
  const modalEl = document.getElementById('tripInfoModal');
  if (!modalEl) return;

  // Délégué : capte tous les clics sur un bouton porteur des data-*
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-trip-id]');
    if (!btn) return;

    // Renseigne le contenu de la modale
    modalEl.querySelector('#ti-creator').textContent = btn.getAttribute('data-creator-name') || '—';
    modalEl.querySelector('#ti-phone').textContent   = btn.getAttribute('data-creator-phone') || '—';
    modalEl.querySelector('#ti-email').textContent   = btn.getAttribute('data-creator-email') || '—';
    modalEl.querySelector('#ti-seats').textContent   = btn.getAttribute('data-seats-total') || '—';

    // Ouvre la modale via l'API Bootstrap (fiable partout)
    const m = bootstrap.Modal.getOrCreateInstance(modalEl);
    m.show();
  });
})();
</script>