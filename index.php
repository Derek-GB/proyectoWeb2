<?php
require_once "includes/config.php";
require_once "includes/functions.php";
require_once "includes/auth.php";
require "includes/header.php";

$featured = getProperties($mysqli, 'destacadas', 3);
$ventas = getProperties($mysqli, 'venta', 3);
$alquiler = getProperties($mysqli, 'alquiler', 3);
?>

<section id="propiedades_destacadas" class="py-12" style="background:var(--primary); color:var(--text);">
  <div class="container mx-auto px-6">
    <h2 class="text-3xl font-bold text-center mb-6">PROPIEDADES DESTACADAS</h2>

    <div class="grid md:grid-cols-3 gap-6">
      <?php if (empty($featured)): ?>
        <div class="p-6 bg-white text-black rounded col-span-3 text-center">No hay propiedades destacadas. Inicia sesión como admin para añadir.</div>
      <?php endif; ?>

      <?php foreach ($featured as $p): ?>
        <a href="propiedad.php?id=<?= h($p['idPropiedad']) ?>" class="block bg-white rounded overflow-hidden shadow-lg hover:shadow-2xl transition">
          <img src="<?= h($p['imagenDestacadaPropiedad']) ?>" alt="<?= h($p['tituloPropiedad']) ?>" class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="font-semibold text-center italic mb-2"><?= h($p['tituloPropiedad']) ?></h3>
            <p class="text-sm muted text-justify"><?= h($p['descripcionBrevePropiedad']) ?></p>
            <div class="mt-3 text-center font-bold accent">Precio: <?= '$' . number_format((float)$p['precioPropiedad'], 0, ',', '.') ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="mt-6 text-center">
  <a href="list.php?filter=destacadas" target="_blank" rel="noopener noreferrer" class="inline-block px-6 py-2 rounded border border-white/70 text-white hover:bg-white/10 transition">VER MAS...</a>
</div>
</section>

<!-- PROPIEDADES EN VENTA -->
<section id="ventas" class="py-12" style="background:var(--neutral); color:#111;">
  <div class="container mx-auto px-6">
    <h2 class="text-3xl font-bold text-center mb-6">PROPIEDADES EN VENTA</h2>

    <div class="grid md:grid-cols-3 gap-6">
      <?php if (empty($ventas)): ?>
        <div class="p-6 bg-white rounded col-span-3 text-center">No hay propiedades en venta.</div>
      <?php endif; ?>

      <?php foreach ($ventas as $p): ?>
        <a href="propiedad.php?id=<?= h($p['idPropiedad']) ?>" class="block bg-white rounded shadow hover:shadow-lg transition">
          <img src="<?= h($p['imagenDestacadaPropiedad']) ?>" alt="<?= h($p['tituloPropiedad']) ?>" class="w-full h-40 object-cover rounded-t">
          <div class="p-4">
            <h3 class="font-semibold text-center italic mb-2"><?= h($p['tituloPropiedad']) ?></h3>
            <p class="text-sm muted text-justify"><?= h($p['descripcionBrevePropiedad']) ?></p>
            <div class="mt-3 text-center font-bold" style="color: var(--primary);">Precio: <?= '$' . number_format((float)$p['precioPropiedad'], 0, ',', '.') ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

  <div class="mt-6 text-center">
  <a href="list.php?filter=venta" target="_blank" rel="noopener noreferrer" class="inline-block px-6 py-2 rounded border" style="border-color:var(--primary); color:var(--primary)">VER MAS...</a>
</div>

  </div>
</section>

<!-- PROPIEDADES EN ALQUILER -->
<section id="alquiler" class="py-12" style="background:var(--primary); color:var(--text);">
  <div class="container mx-auto px-6">
    <h2 class="text-3xl font-bold text-center mb-6">PROPIEDADES EN ALQUILER</h2>

    <div class="grid md:grid-cols-3 gap-6">
      <?php if (empty($alquiler)): ?>
        <div class="p-6 bg-white text-black rounded col-span-3 text-center">No hay propiedades en alquiler.</div>
      <?php endif; ?>

      <?php foreach ($alquiler as $p): ?>
        <a href="propiedad.php?id=<?= h($p['idPropiedad']) ?>" class="block bg-white rounded overflow-hidden shadow-lg hover:shadow-2xl transition">
          <img src="<?= h($p['imagenDestacadaPropiedad']) ?>" alt="<?= h($p['tituloPropiedad']) ?>" class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="font-semibold text-center italic mb-2"><?= h($p['tituloPropiedad']) ?></h3>
            <p class="text-sm muted text-justify"><?= h($p['descripcionBrevePropiedad']) ?></p>
            <div class="mt-3 text-center font-bold accent">Precio: <?= '$' . number_format((float)$p['precioPropiedad'], 0, ',', '.') ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="mt-6 text-center">
  <a href="list.php?filter=alquiler" target="_blank" rel="noopener noreferrer" class="inline-block px-6 py-2 rounded border border-white/70 text-white hover:bg-white/10 transition">VER MAS...</a>
</div>
  </div>
</section>

<?php require "includes/footer.php"; ?>
