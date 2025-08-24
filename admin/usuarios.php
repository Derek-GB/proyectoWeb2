<?php
// admin/usuarios.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!isAdmin()) {
  header("Location: /proyecto/login.php");
  exit;
}

// Crear usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'crear_usuario') {
  $nombre = $_POST['nombre'] ?? '';
  $usuario = $_POST['usuario'] ?? '';
  $telefono = $_POST['telefono'] ?? '';
  $email = $_POST['email'] ?? '';
  $pass = $_POST['password'] ?? '';
  $priv = $_POST['priv'] ?? 'agente';
  if ($nombre && $usuario && $pass) {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $usuarioNuevo = 1;
    $stmt = $mysqli->prepare("INSERT INTO tablaUsuarios (nombreUsuario, telefonoUsuario, correoUsuario, emailUsuario, usuarioLogin, contrasenaLogin, privilegioUsuario, usuarioNuevo) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssssi", $nombre, $telefono, $email, $email, $usuario, $hash, $priv, $usuarioNuevo);
    $stmt->execute();
    $stmt->close();
    $msg = "Usuario creado.";
  } else {
    $msg = "Rellena nombre, usuario y contraseña.";
  }
}

// Eliminar
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $stmt = $mysqli->prepare("DELETE FROM tablaUsuarios WHERE idUsuario = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
  header("Location: usuarios.php");
  exit;
}

// Listado
$res = $mysqli->query("SELECT idUsuario,nombreUsuario,usuarioLogin,privilegioUsuario FROM tablaUsuarios ORDER BY idUsuario DESC");
$usuarios = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

require_once __DIR__ . '/../includes/header.php';
?>
<div class="p-6">
  <a href="dashboard.php"
    class="inline-block mb-4 px-4 py-2 rounded border border-gray-300 bg-white hover:bg-gray-100">&larr; Regresar al
    panel</a>
  <h2 class="text-2xl font-bold mb-4">Administrar Usuarios</h2>
  <?php if (isset($msg)): ?>
    <div class="p-2 bg-green-100 text-green-800 mb-3"><?= h($msg) ?></div><?php endif; ?>

  <form method="post" class="bg-white p-4 rounded shadow mb-6">
    <input type="hidden" name="action" value="crear_usuario">
    <input name="nombre" placeholder="Nombre"
      class="w-full p-2 rounded border border-gray-300 mb-2 focus:outline-none focus:ring-2 focus:ring-[var(--accent)]"
      required>
    <input name="usuario" placeholder="Usuario login"
      class="w-full p-2 rounded border border-gray-300 mb-2 focus:outline-none focus:ring-2 focus:ring-[var(--accent)]"
      required>
    <input name="telefono" placeholder="Telefono"
      class="w-full p-2 rounded border border-gray-300 mb-2 focus:outline-none focus:ring-2 focus:ring-[var(--accent)]">
    <input name="email" placeholder="Email"
      class="w-full p-2 rounded border border-gray-300 mb-2 focus:outline-none focus:ring-2 focus:ring-[var(--accent)]">
    <input name="password" placeholder="Contraseña" type="password"
      class="w-full p-2 rounded border border-gray-300 mb-2 focus:outline-none focus:ring-2 focus:ring-[var(--accent)]"
      required>
    <select name="priv"
      class="w-full p-2 rounded border border-gray-300 mb-2 focus:outline-none focus:ring-2 focus:ring-[var(--accent)]">
      <option value="agente">Agente</option>
      <option value="administrador">Administrador</option>
    </select>
    <div class="flex justify-end"><button class="btn-primary px-4 py-2 rounded">Crear usuario</button></div>
  </form>

  <h3 class="font-bold mb-2">Usuarios actuales</h3>
  <ul class="space-y-2">
    <?php foreach ($usuarios as $u): ?>
      <li class="p-3 bg-white rounded flex justify-between items-center">
        <div><?= h($u['nombreUsuario']) ?> - <?= h($u['usuarioLogin']) ?> - <?= h($u['privilegioUsuario']) ?></div>
        <div>
          <a href="editar_usuario.php?id=<?= h($u['idUsuario']) ?>"
            class="px-2 py-1 text-xs bg-yellow-100 rounded mr-2">Editar</a>
          <?php if (intval($u['idUsuario']) !== intval($_SESSION['usuario']['idUsuario'])): ?>
            <a href="?delete=<?= h($u['idUsuario']) ?>" onclick="return confirm('Eliminar usuario?')"
              class="px-2 py-1 text-xs bg-red-100 rounded">Eliminar</a>
          <?php endif; ?>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>