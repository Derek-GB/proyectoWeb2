<?php
// agente/panel.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (!isLoggedIn() || ($_SESSION['usuario']['privilegioUsuario'] ?? '') !== 'agente') {
  header("Location: /proyecto/login.php");
  exit;
}

$idAgente = $_SESSION['usuario']['idUsuario'];
$msg = null;

// Guardar cambios de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'actualizar_perfil') {
  $nombre = trim($_POST['nombre'] ?? '');
  $telefono = trim($_POST['telefono'] ?? '');
  $email = trim($_POST['email'] ?? '');
  if ($nombre && $email) {
    $stmt = $mysqli->prepare("UPDATE tablaUsuarios SET nombreUsuario=?, telefonoUsuario=?, emailUsuario=? WHERE idUsuario=?");
    $stmt->bind_param("sssi", $nombre, $telefono, $email, $idAgente);
    $stmt->execute();
    $stmt->close();
    $_SESSION['usuario']['nombreUsuario'] = $nombre;
    $_SESSION['usuario']['telefonoUsuario'] = $telefono;
    $_SESSION['usuario']['emailUsuario'] = $email;
    $msg = "Datos actualizados.";
  }
}

// Eliminar propiedad
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $stmt = $mysqli->prepare("DELETE FROM tablaPropiedades WHERE idPropiedad=? AND idAgente=?");
  $stmt->bind_param("ii", $id, $idAgente);
  $stmt->execute();
  $stmt->close();
  $msg = "Propiedad eliminada.";
}

$msg = null;

// Cargar todas las propiedades del agente
$res = $mysqli->query("SELECT * FROM tablaPropiedades WHERE idAgente=" . intval($idAgente) . " ORDER BY idPropiedad DESC");
$propiedades = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

require_once __DIR__ . '/../includes/header.php';
?>
<div class="p-6">
  <h2 class="text-2xl font-bold mb-4">Panel de Agente</h2>
  <?php if ($msg): ?>
    <div class="p-2 bg-green-100 text-green-800 mb-3"><?= h($msg) ?></div><?php endif; ?>

  <h3 class="font-bold mb-2">Mis datos</h3>
  <form method="post" class="bg-white p-4 rounded mb-6">
    <input type="hidden" name="action" value="actualizar_perfil">
    <input name="nombre" placeholder="Nombre" class="w-full p-2 rounded mb-2"
      value="<?= h($_SESSION['usuario']['nombreUsuario']) ?>" required>
    <input name="telefono" placeholder="Teléfono" class="w-full p-2 rounded mb-2"
      value="<?= h($_SESSION['usuario']['telefonoUsuario']) ?>">
    <input name="email" placeholder="Email" class="w-full p-2 rounded mb-2"
      value="<?= h($_SESSION['usuario']['emailUsuario']) ?>" required>
    <button class="btn-primary px-4 py-2 rounded">Actualizar datos</button>
  </form>

  <h3 class="font-bold mb-2">Mis propiedades</h3>
  <a href="agregar_propiedad.php" class="inline-block mb-3 px-4 py-2 bg-[var(--accent)] text-black rounded">Agregar
    propiedad</a>
  <ul class="space-y-2">
    <?php foreach ($propiedades as $p): ?>
      <li class="p-3 bg-white rounded flex justify-between items-center">
        <div>
          <strong><?= h($p['tituloPropiedad']) ?></strong><br>
          <span class="muted"><?= h($p['tipoPropiedad']) ?> - <?= h($p['precioPropiedad']) ?></span>
        </div>
        <div>
          <a href="editar_propiedad.php?id=<?= h($p['idPropiedad']) ?>"
            class="px-2 py-1 text-xs bg-yellow-100 rounded">Editar</a>
          <a href="?delete=<?= h($p['idPropiedad']) ?>" onclick="return confirm('Eliminar?')"
            class="px-2 py-1 text-xs bg-red-100 rounded">Eliminar</a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>