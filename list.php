<?php
// list.php - muestra todas las propiedades según filtro (destacadas / venta / alquiler)
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$allowed = ['destacadas','venta','alquiler'];

$filter = null;

// Obtener título para la página
$titles = [
  'destacadas' => 'PROPIEDADES DESTACADAS',
  'venta' => 'PROPIEDADES EN VENTA',
  'alquiler' => 'PROPIEDADES EN ALQUILER'
];

$title = $filter && isset($titles[$filter]) ? $titles[$filter] : 'PROPIEDADES';

// Traer todas las propiedades del tipo solicitado
// Usamos un límite alto (1000). Si prefieres paginación, lo agregamos luego.
$search = isset($_GET['q']) ? trim($_GET['q']) : null;
$items = getProperties($mysqli, $filter, 1000, $search);

require_once __DIR__ . '/includes/header.php';
?>

<div class="container mx-auto px-6 py-12">
  <h1 class="text-3xl font-bold text-center mb-6"><?= h($title) ?></h1>

  <?php if (empty($items)): ?>
    <div class="p-6 bg-white rounded text-center">No se encontraron propiedades para esta sección.</div>
  <?php else: ?>
    <div class="grid md:grid-cols-3 gap-6">
      <?php foreach ($items as $p): ?>
        <div class="bg-white rounded shadow overflow-hidden">
          <a href="propiedad.php?id=<?= h($p['idPropiedad']) ?>">
            <img src="<?= h($p['imagenDestacadaPropiedad']) ?>" alt="<?= h($p['tituloPropiedad']) ?>" class="w-full h-48 object-cover">
          
          <div class="p-4">
            <h3 class="font-semibold italic text-center mb-2"><?= h($p['tituloPropiedad']) ?></h3>
            <p class="text-sm muted text-justify"><?= h($p['descripcionBrevePropiedad']) ?></p>
            <div class="mt-3 text-center font-bold" style="color: var(--primary);">Precio: <?= '$' . number_format((float)$p['precioPropiedad'], 0, ',', '.') ?></div>
          </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="mt-8 text-center">
    <a href="index.php" class="inline-block px-4 py-2 rounded border">Volver al inicio</a>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
