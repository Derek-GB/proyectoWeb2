<?php
// admin/dashboard.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (!isAdmin()) {
  header("Location: /proyecto/login.php");
  exit;
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="p-6">

  <h2 class="text-2xl font-bold mb-4">Panel Administrador</h2>
  <div class="grid md:grid-cols-4 gap-4">
    <a href="propiedades.php" class="p-4 bg-white rounded shadow">Administrar Propiedades</a>
    <a href="usuarios.php" class="p-4 bg-white rounded shadow">Administrar Usuarios</a>
    <a href="configuracion.php" class="p-4 bg-white rounded shadow">Configuración del Sitio</a>
    <a href="perfil.php" class="p-4 bg-white rounded shadow">Mi Perfil</a>
  </div>

  <div class="mt-6">
    <h3 class="font-bold mb-2">Últimas propiedades</h3>
    <?php
    $lista = getPropiedades($mysqli, null, 10);
    ?>
    <ul class="space-y-2">
      <?php foreach ($lista as $fila): ?>
        <li class="p-3 bg-white rounded flex justify-between items-center">
          <div>
            <strong><?= h($fila['tituloPropiedad']) ?></strong><br>
            <span class="muted"><?= h($fila['tipoPropiedad']) ?> - <?= h($fila['precioPropiedad']) ?></span>
          </div>
          <div>
            <a href="propiedades.php?edit=<?= h($fila['idPropiedad']) ?>"
              class="px-2 py-1 text-xs bg-yellow-100 rounded">Editar</a>
            <a href="propiedades.php?delete=<?= h($fila['idPropiedad']) ?>" onclick="return confirm('Eliminar?')"
              class="px-2 py-1 text-xs bg-red-100 rounded">Eliminar</a>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>