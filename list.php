<?php
// Este archivo muestra todas las propiedades según el filtro que elijas (destacadas, venta, alquiler, etc)
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$allowed = ['destacadas', 'venta', 'alquiler', 'resultados'];

if (isset($_GET['filter'])) {
  $filter = strtolower(trim($_GET['filter']));
  if (!in_array($filter, $allowed)) {
    $filter = null;
  }
} else {
  $filter = null;
}

// Aquí armo el título de la página según el filtro seleccionado
$titles = [
  'destacadas' => 'PROPIEDADES DESTACADAS',
  'venta' => 'PROPIEDADES EN VENTA',
  'alquiler' => 'PROPIEDADES EN ALQUILER',
  'resultados' => 'RESULTADOS DE BÚSQUEDA'
];

$title = $filter && isset($titles[$filter]) ? $titles[$filter] : 'PROPIEDADES';

// Traigo todas las propiedades del tipo que pidió el usuario
// Puse un límite alto para mostrar muchas propiedades, si quieres paginación se puede agregar después
$search = isset($_GET['q']) ? trim($_GET['q']) : null;
$items = getPropiedades($mysqli, $filter, 1000, $search);

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
            <img src="<?= h($p['imagenDestacadaPropiedad']) ?>" alt="<?= h($p['tituloPropiedad']) ?>"
              class="w-full h-48 object-cover">

            <div class="p-4">
              <h3 class="font-semibold italic text-center mb-2"><?= h($p['tituloPropiedad']) ?></h3>
              <p class="text-sm muted text-justify"><?= h($p['descripcionBrevePropiedad']) ?></p>
              <div class="mt-3 text-center font-bold" style="color: var(--primary);">Precio:
                <?= '$' . number_format((float) $p['precioPropiedad'], 0, ',', '.') ?></div>
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