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
  <h1 class="text-3xl font-bold mb-4"><?= h($prop['tituloPropiedad']) ?></h1>
  <img src="<?= h($prop['imagenDestacadaPropiedad']) ?>" class="w-full h-80 object-cover rounded mb-6" alt="<?= h($prop['tituloPropiedad']) ?>">

  <p class="mb-4"><?= nl2br(h($prop['descripcionLargaPropiedad'] ?? $prop['descripcionBrevePropiedad'])) ?></p>
  <div class="mb-2 font-semibold text-xl">Precio: <?= h($prop['precioPropiedad']) ?></div>
  <div class="mb-2">Tipo: <?= h($prop['tipoPropiedad']) ?></div>
  <div class="mb-2">Ubicación: <?= h($prop['ubicacionPropiedad']) ?></div>

  <?php if (!empty($prop['mapaPropiedad'])): ?>
    <div class="mt-6">
      <h3 class="font-semibold mb-2">Mapa / Ubicación</h3>
      <img src="<?= h($prop['mapaPropiedad']) ?>" alt="Mapa de la propiedad" class="w-full max-w-3xl object-contain rounded shadow">
    </div>
  <?php endif; ?>

  <div class="mt-6 p-4 border rounded">
    <h3 class="font-bold">Agente: <?= h($prop['nombreUsuario']) ?></h3>
    <p>Tel: <?= h($prop['telefonoUsuario']) ?> | Email: <?= h($prop['emailUsuario']) ?></p>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
