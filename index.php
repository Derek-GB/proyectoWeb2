<?php
require_once "includes/config.php";
require_once "includes/functions.php";
require_once "includes/auth.php";
require "includes/header.php";

$featured = getProperties($mysqli, 'destacadas', 3);
$ventas = getProperties($mysqli, 'venta', 3);
$alquiler = getProperties($mysqli, 'alquiler', 3);


$config = getConfig($mysqli);
?>
<header class="relative">
  <img src="<?= h($config['bannerImagen']) ?>" alt="banner" class="w-full h-[420px]  block">
  <div class="absolute inset-0 flex items-center justify-center">
    <div class="bg-black/50 p-6 rounded text-center max-w-3xl">
      <h1 class="text-3xl md:text-4xl font-bold text-white"><?= h($config['bannerMensaje']) ?></h1>
    </div>
  </div>
</header>


<section id="quienes" class="py-12" style="background:var(--neutral); color:#111;">
  <div class="container mx-auto px-6">
    <h2 class="text-3xl font-bold text-center mb-6">QUIENES SOMOS</h2>
    <div class="md:flex md:items-center md:gap-6">
      <div class="md:w-2/3">
        <p class="text-justify muted"><?= nl2br(h($config['quienesSomos'] ?? '')) ?></p>
      </div>
      <div class="md:w-1/3 mt-4 md:mt-0">
        <?php if (!empty($config['quienesSomosImagen'])): ?>
          <img src="<?= h($config['quienesSomosImagen']) ?>" alt="Quienes somos" class="w-full rounded shadow">
        <?php else: ?>
          <div class="w-full h-48 bg-gray-200 rounded flex items-center justify-center">Sin imagen</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<section id="propiedades_destacadas" class="py-12" style="background:var(--primary); color:var(--text);">
  <div class="container mx-auto px-6">
    <h2 class="text-3xl font-bold text-center mb-6">PROPIEDADES DESTACADAS</h2>

    <div class="grid md:grid-cols-3 gap-6">
      <?php if (empty($featured)): ?>
        <div class="p-6 bg-white text-black rounded col-span-3 text-center">No hay propiedades destacadas. Inicia sesión
          como admin para añadir.</div>
      <?php endif; ?>

      <?php foreach ($featured as $p): ?>
        <a href="propiedad.php?id=<?= h($p['idPropiedad']) ?>"
          class="block bg-white rounded overflow-hidden shadow-lg hover:shadow-2xl transition">
          <img src="<?= h($p['imagenDestacadaPropiedad']) ?>" alt="<?= h($p['tituloPropiedad']) ?>"
            class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="font-semibold text-center italic mb-2" style="color:#222 !important; background: #fff;">
              <?= h($p['tituloPropiedad']) ?></h3>
            <p class="text-sm muted text-justify"><?= h($p['descripcionBrevePropiedad']) ?></p>
            <div class="mt-3 text-center font-bold accent">Precio:
              <?= '$' . number_format((float) $p['precioPropiedad'], 0, ',', '.') ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="mt-6 text-center">
      <a href="list.php?filter=destacadas" rel="noopener noreferrer"
        class="inline-block px-6 py-2 rounded border border-white/70 text-white hover:bg-white/10 transition">VER
        MAS...</a>
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
        <a href="propiedad.php?id=<?= h($p['idPropiedad']) ?>"
          class="block bg-white rounded shadow hover:shadow-lg transition">
          <img src="<?= h($p['imagenDestacadaPropiedad']) ?>" alt="<?= h($p['tituloPropiedad']) ?>"
            class="w-full h-40 object-cover rounded-t">
          <div class="p-4">
            <h3 class="font-semibold text-center italic mb-2" style="color:#222 !important; background: #fff;">
              <?= h($p['tituloPropiedad']) ?></h3>
            <p class="text-sm muted text-justify"><?= h($p['descripcionBrevePropiedad']) ?></p>
            <div class="mt-3 text-center font-bold" style="color: var(--primary);">Precio:
              <?= '$' . number_format((float) $p['precioPropiedad'], 0, ',', '.') ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="mt-6 text-center">
      <a href="list.php?filter=venta" rel="noopener noreferrer" class="inline-block px-6 py-2 rounded border"
        style="border-color:var(--primary); color:var(--primary)">VER MAS...</a>
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
        <a href="propiedad.php?id=<?= h($p['idPropiedad']) ?>"
          class="block bg-white rounded overflow-hidden shadow-lg hover:shadow-2xl transition">
          <img src="<?= h($p['imagenDestacadaPropiedad']) ?>" alt="<?= h($p['tituloPropiedad']) ?>"
            class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="font-semibold text-center italic mb-2" style="color:#222 !important; background: #fff;">
              <?= h($p['tituloPropiedad']) ?></h3>
            <p class="text-sm muted text-justify"><?= h($p['descripcionBrevePropiedad']) ?></p>
            <div class="mt-3 text-center font-bold accent">Precio:
              <?= '$' . number_format((float) $p['precioPropiedad'], 0, ',', '.') ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="mt-6 text-center">
      <a href="list.php?filter=alquiler" rel="noopener noreferrer"
        class="inline-block px-6 py-2 rounded border border-white/70 text-white hover:bg-white/10 transition">VER
        MAS...</a>
    </div>
  </div>
</section>

<?php require "includes/footer.php"; ?>