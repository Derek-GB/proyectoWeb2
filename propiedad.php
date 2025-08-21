 <?php
// propiedad.php - detalle completo de una propiedad
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { die("ID inválido."); }

$prop = getPropertyById($mysqli, $id);
if (!$prop) { die("Propiedad no encontrada."); }

require_once __DIR__ . '/includes/header.php';
?>


<div class="container mx-auto px-6 py-12">
  <h1 class="text-3xl font-bold mb-4 border-b-4 border-[var(--primary)] pb-2 px-2 inline-block bg-white rounded"> <?= h($prop['tituloPropiedad']) ?> </h1>
  <img src="<?= h($prop['imagenDestacadaPropiedad']) ?>" class="mb-6 rounded border-4 border-[var(--primary)] shadow object-contain max-w-full max-h-80 bg-gray-100" alt="<?= h($prop['tituloPropiedad']) ?>">

  <div class="mb-4 p-4 bg-gray-50 border-2 border-[var(--primary)] rounded shadow">
    <?= nl2br(h($prop['descripcionLargaPropiedad'] ?? $prop['descripcionBrevePropiedad'])) ?>
  </div>
  <div class="mb-2 font-semibold text-xl p-2 border-l-4 border-[var(--accent)] bg-white rounded shadow inline-block">Precio: $<?= number_format((float)$prop['precioPropiedad'], 0, ',', '.') ?></div>
  <div class="mb-2 p-2 border-l-4 border-[var(--accent)] bg-white rounded shadow inline-block"> <span class="font-bold">Tipo:</span> <?= h($prop['tipoPropiedad']) ?></div>
  <div class="mb-2 p-2 border-l-4 border-[var(--accent)] bg-white rounded shadow inline-block"> <span class="font-bold">Ubicación:</span> <?= h($prop['ubicacionPropiedad']) ?></div>

  <?php if (!empty($prop['mapaPropiedad'])): ?>
    <div class="mt-6">
      <h3 class="font-semibold mb-2 text-lg text-[var(--primary)]">Mapa / Ubicación</h3>
      <img src="<?= h($prop['mapaPropiedad']) ?>" alt="Mapa de la propiedad" class="w-full max-w-3xl max-h-80 rounded shadow border object-contain bg-gray-100">
    </div>
  <?php endif; ?>

  <div class="mt-6 p-4 border-2 border-[var(--primary)] rounded bg-white shadow">
    <h3 class="font-bold">Agente: <?= h($prop['nombreUsuario']) ?></h3>
    <p>Tel: <span class="font-semibold"><?= h($prop['telefonoUsuario']) ?></span> | Email: <span class="font-semibold"><?= h($prop['emailUsuario']) ?></span></p>
  </div>

  <a href="index.php" class="mt-8 inline-block px-6 py-3 rounded-xl bg-[var(--primary)] text-white font-bold text-lg shadow hover:bg-[var(--accent)] hover:text-black transition">← Volver al inicio</a>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
