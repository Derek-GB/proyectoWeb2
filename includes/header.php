<?php
// includes/header.php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
$config = getConfig($mysqli);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= h($config['bannerMensaje'] ?? 'Proyecto') ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root{
      --primary: <?= h($config['colorAzul'] ?? '#1f2d6b') ?>;
      --accent:  <?= h($config['colorAmarillo'] ?? '#f0b429') ?>;
      --neutral: <?= h($config['colorGris'] ?? '#f3f4f6') ?>;
      --text: <?= h($config['colorBlanco'] ?? '#ffffff') ?>;
    }
    .btn-primary { background: var(--primary); color: var(--text); }
    .accent { color: var(--accent); }
    .muted { color: #6b7280; }
  </style>
</head>
<body class="antialiased" style="background:var(--neutral); color:#222;">
<nav class="p-4 flex items-center justify-between" style="background:var(--primary); color:var(--text);">
  <div class="flex items-center gap-3">
    <img src="<?= h($config['iconoPrincipal']) ?>" alt="logo" class="h-10 w-10 object-contain">
    <div>
      <div class="font-bold"><?= h($config['bannerMensaje']) ?></div>
      <div class="text-sm muted"><?= h($config['direccion']) ?></div>
    </div>
  </div>
  <div class="flex items-center gap-4">
    <a class="text-sm hover:underline" href="/proyecto/index.php#propiedades">PROPIEDADES</a>
    <a class="text-sm hover:underline" href="/proyecto/index.php#quienes">QUIENES SOMOS</a>
    <a class="text-sm hover:underline" href="/proyecto/index.php#contacto">CONTACTENOS</a>
    <?php if(isLoggedIn()): ?>
      <div class="flex items-center gap-3">
        <span class="text-sm"><?= h($_SESSION['user']['nombreUsuario']) ?></span>
        <?php if(isAdmin()): ?>
          <a href="/proyecto/admin/dashboard.php" class="px-3 py-1 rounded bg-white text-black text-sm">Admin</a>
        <?php endif; ?>
        <a href="/proyecto/logout.php" class="px-3 py-1 rounded bg-white text-black text-sm">Salir</a>
      </div>
    <?php else: ?>
      <a href="/proyecto/login.php" class="px-3 py-1 rounded bg-white text-black text-sm">Iniciar</a>
    <?php endif; ?>
  </div>
</nav>
