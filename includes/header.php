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


<body class="antialiased min-h-screen" style="background:var(--neutral); color:#222;">
  <div class="flex">
<!-- Header superior: logo, nombre, redes, usuario/admin -->
  <div class="w-full px-8 py-3" style="background:var(--primary);">
    <div class="flex items-center justify-between w-full">
      <!-- Logo, nombre y redes en columna -->
      <div class="flex flex-col items-start gap-0">
        <img src="<?= $config['iconoPrincipal'] ?>" alt="logo" class="h-14 w-14 object-contain bg-white rounded p-1 mb-1" style="min-width:56px;">
        <div class="flex flex-col items-start leading-tight mb-1">
          <span class="text-white font-bold text-base sm:text-lg" style="font-style:italic; letter-spacing:1px;">UTN SOLUTIONS</span>
          <span class="text-white font-bold text-xs sm:text-sm" style="font-style:italic; letter-spacing:1px;">REAL STATE</span>
        </div>
        <div class="flex items-center gap-2 mt-1">
          <a href="#" class=""><img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" alt="Facebook" class="h-7 w-7 bg-white rounded-full p-1"></a>
          <a href="#" class=""><img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/youtube.svg" alt="YouTube" class="h-7 w-7 bg-white rounded-full p-1"></a>
          <a href="#" class=""><img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram" class="h-7 w-7 bg-white rounded-full p-1"></a>
        </div>
      </div>
      
      <!-- Usuario/admin -->
      <div class="flex items-center gap-3">
        <?php if(isLoggedIn()): ?>
          <span class="text-white text-sm font-semibold flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-7 h-7 bg-[var(--accent)] rounded-full p-1"><circle cx="12" cy="8" r="4"/><path d="M12 14c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z"/></svg>
          </span>
          <?php if(isAdmin()): ?>
            <a href="/proyecto/admin/dashboard.php" class="px-3 py-1 rounded bg-[var(--accent)] text-black text-sm font-bold flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 19.5a7.5 7.5 0 1115 0v.75A2.25 2.25 0 0117.25 22.5h-10.5A2.25 2.25 0 014.5 20.25v-.75z" /></svg> Admin</a>
          <?php endif; ?>
          <a href="/proyecto/logout.php" class="px-3 py-1 rounded bg-white text-black text-sm font-bold">Salir</a>
        <?php else: ?>
          <a href="/proyecto/login.php" class="px-3 py-1 rounded bg-white text-black text-sm font-bold flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><circle cx="12" cy="8" r="4"/><path d="M12 14c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z"/></svg> Iniciar</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Navbar alineado a la derecha -->
  <nav class="w-full flex items-center justify-end gap-3 py-3 pr-8" style="background:var(--primary); color:var(--accent); font-weight:bold; font-style:italic; letter-spacing:0.5px;">
    <a class="hover:underline" href="/proyecto/index.php">INICIO</a>
    <span>|</span>
    <a class="hover:underline" href="/proyecto/index.php#quienes">QUIENES SOMOS</a>
    <span>|</span>
    <a class="hover:underline" href="/proyecto/index.php#alquiler">ALQUILERES</a>
    <span>|</span>
    <a class="hover:underline" href="/proyecto/index.php#ventas">VENTAS</a>
    <span>|</span>
    <a class="hover:underline" href="/proyecto/index.php#contacto">CONTACTENOS</a>
  </nav>

  <!-- Barra de búsqueda debajo a la derecha -->
  <div class="w-full flex justify-end pr-8 pb-2" style="background:var(--primary);">
    <form action="/proyecto/list.php" method="get" class="flex items-center gap-2">
      <input type="text" name="q" placeholder="Buscar..." class="rounded px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent)]" style="min-width:220px;">
      <button type="submit" class="bg-[var(--accent)] p-2 rounded-full hover:bg-yellow-500 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-black">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
        </svg>
      </button>
    </form>
  </div>
  </div>
