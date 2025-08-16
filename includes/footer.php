<?php
// includes/footer.php
?>
<footer id="footer" class="py-12" style="background:var(--accent); color:var(--text);">
  <div class="container mx-auto px-6 text-center">
    <div>Contacto: <?= h($config['telefono']) ?> - <?= h($config['email']) ?></div>
    <div class="mt-3 muted">&copy; <?= date('Y') ?> - Derechos Reservados</div>
  </div>
</footer>
</body>
</html>
