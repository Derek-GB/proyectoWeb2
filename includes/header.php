<?php
// Este archivo arma la cabecera de la página, con los estilos, menú y redes sociales
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

  <!-- Aquí cargo Tailwind CSS para los estilos bonitos -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Variables de color para personalizar la apariencia de la web -->
  <style>
    :root {
      --primary:
        <?= h($config['colorAzul'] ?? '#1f2d6b') ?>
      ;
      --accent:
        <?= h($config['colorAmarillo'] ?? '#f0b429') ?>
      ;
      --neutral:
        <?= h($config['colorGris'] ?? '#f3f4f6') ?>
      ;
      --text:
        <?= h($config['colorBlanco'] ?? '#ffffff') ?>
      ;
    }

    .btn-primary {
      background: var(--primary);
      color: var(--text);
    }

    .accent {
      color: var(--accent);
    }

    .muted {
      color: #6b7280;
    }
  </style>
</head>

<body class="antialiased min-h-screen" style="background:var(--neutral); color:#222;">
  <div class="flex flex-col w-full">

    <!-- Aquí va la parte de arriba del header, con logo y usuario -->
    <header class="w-full px-8 py-3" style="background:var(--primary);">
      <div class="flex items-center justify-between w-full">
        <!-- Aquí se muestra el logo de la empresa -->
        <div class="flex items-center gap-2">
          <img src="<?= "/Proyecto/" . $config['iconoPrincipal'] ?>" alt="logo"
            class="h-14 w-14 object-contain bg-white rounded p-1" style="min-width:56px;">
        </div>

        <!-- Aquí aparecen los botones de usuario o admin -->
        <div class="flex items-center gap-3">
          <?php if (isLoggedIn()): ?>
            <?php if (isAdmin()): ?>
              <a href="/proyecto/admin/dashboard.php"
                class="px-3 py-1 rounded bg-[var(--accent)] text-black text-sm font-bold flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 
                  3.75 3.75 0 017.5 0zM4.5 19.5a7.5 
                  7.5 0 1115 0v.75A2.25 2.25 0 
                  0117.25 22.5h-10.5A2.25 2.25 
                  0 014.5 20.25v-.75z" />
                </svg>
                Administrador
              </a>
            <?php elseif (isset($_SESSION['user']['privilegioUsuario']) && $_SESSION['user']['privilegioUsuario'] === 'agente'): ?>
              <a href="/proyecto/agente/panel.php"
                class="px-3 py-1 rounded bg-[var(--accent)] text-black text-sm font-bold flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 
                           3.75 3.75 0 017.5 0zM4.5 19.5a7.5 
                           7.5 0 1115 0v.75A2.25 2.25 0 
                           0117.25 22.5h-10.5A2.25 2.25 
                           0 014.5 20.25v-.75z" />
                </svg>
                Agente
              </a>
            <?php endif; ?>

            <a href="/proyecto/logout.php" class="px-3 py-1 rounded bg-white text-black text-sm font-bold">
              Salir
            </a>

          <?php else: ?>
            <a href="/proyecto/login.php"
              class="px-3 py-1 rounded bg-white text-black text-sm font-bold flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                <circle cx="12" cy="8" r="4" />
                <path d="M12 14c-4.418 0-8 1.79-8 4v2h16v-2
                         c0-2.21-3.582-4-8-4z" />
              </svg>
              Iniciar
            </a>
          <?php endif; ?>
        </div>
      </div>
    </header>

    <!-- Menú de navegación, nombre de la empresa y menú hamburguesa para móviles -->
    <nav class="w-full" aria-label="Main navigation" style="background:var(--primary);">
      <div class="mx-auto px-8">

        <!-- Este input controla si el menú mobile está abierto o cerrado -->
        <input id="nav-toggle" type="checkbox" class="peer hidden md:hidden" />

        <div class="flex items-center justify-between py-3">
          <!-- Aquí va el nombre de la empresa a la izquierda -->
          <div class="text-white font-bold italic text-lg sm:text-xl tracking-wide">
            UTN SOLUTIONS
            <div class="text-xs sm:text-sm">REAL STATE</div>
          </div>

          <!-- Enlaces del menú para escritorio -->
          <div class="hidden md:flex items-center gap-3"
            style="color:var(--accent); font-weight:bold; font-style:italic; letter-spacing:0.5px;">
            <a class="hover:underline" href="/proyecto/index.php">INICIO</a><span>|</span>
            <a class="hover:underline" href="/proyecto/index.php#quienes">QUIENES SOMOS</a><span>|</span>
            <a class="hover:underline" href="/proyecto/list.php?filter=alquiler">ALQUILERES</a><span>|</span>
            <a class="hover:underline" href="/proyecto/list.php?filter=venta">VENTAS</a><span>|</span>
            <a class="hover:underline" href="/proyecto/index.php#contacto">CONTACTENOS</a>
          </div>

          <!-- Botón hamburguesa para abrir/cerrar el menú en móviles -->
          <div class="md:hidden">
            <label for="nav-toggle" class="p-2 rounded-md cursor-pointer inline-flex items-center">
              <!-- Icono de hamburguesa (se esconde cuando el menú está abierto) -->
              <svg class="h-6 w-6 peer-checked:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                stroke-width="2" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
              <!-- Icono de cerrar  -->
              <svg class="h-6 w-6 hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                stroke-width="2" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </label>
          </div>
        </div>

        <!-- Menú para móviles, aparece solo en pantallas pequeñas -->
        <div class="md:hidden overflow-hidden max-h-0 peer-checked:max-h-60 transition-all duration-300 ease-in-out"
          style="background:var(--primary); color:var(--accent); font-weight:bold; font-style:italic; letter-spacing:0.5px;">
          <nav class="flex flex-col px-2 py-3 gap-2">
            <a class="block px-3 py-2 hover:underline" href="/proyecto/index.php">INICIO</a>
            <a class="block px-3 py-2 hover:underline" href="/proyecto/index.php#quienes">QUIENES SOMOS</a>
            <a class="block px-3 py-2 hover:underline" href="/proyecto/list.php?filter=alquiler">ALQUILERES</a>
            <a class="block px-3 py-2 hover:underline" href="/proyecto/list.php?filter=venta">VENTAS</a>
            <a class="block px-3 py-2 hover:underline" href="/proyecto/index.php#contacto">CONTACTENOS</a>
          </nav>
        </div>

      </div>
    </nav>

    <!-- Acá van las redes sociales y la barra de búsqueda -->
    <div class="w-full flex items-center justify-between px-8 py-3 flex-wrap gap-3" style="background:var(--primary);">

      <!-- Aquí van los íconos de las redes sociales -->
      <div class="flex items-center gap-2">
        <a href="<?= h($config['facebook'] ?? '#') ?>" target="_blank" rel="noopener">
          <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" alt="Facebook"
            class="h-7 w-7 bg-white rounded-full p-1">
        </a>
        <a href="<?= h($config['youtube'] ?? '#') ?>" target="_blank" rel="noopener">
          <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/youtube.svg" alt="YouTube"
            class="h-7 w-7 bg-white rounded-full p-1">
        </a>
        <a href="<?= h($config['instagram'] ?? '#') ?>" target="_blank" rel="noopener">
          <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram"
            class="h-7 w-7 bg-white rounded-full p-1">
        </a>
      </div>

      <!-- Barra de búsqueda para encontrar propiedades-->
      <form action="/proyecto/list.php?filter=resultados" method="get"
        class="flex items-center gap-2 w-full md:w-auto md:flex-shrink-0">
        <div class="flex w-full md:w-auto">
          <input type="text" name="q" placeholder="Buscar..."
            class="rounded-l px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent)] w-full md:w-auto"
            style="min-width:0; max-width:100%;">
          <input type="hidden" name="filter" value="resultados">
          <button type="submit" class="bg-[var(--accent)] p-2 rounded-r hover:bg-yellow-500 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="w-5 h-5 text-black">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 
                       104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
            </svg>
          </button>
        </div>
      </form>
    </div>

  </div>