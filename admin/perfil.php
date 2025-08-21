<?php
// admin/perfil.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!isAdmin()) { header("Location: /proyecto/login.php"); exit; }

$idAdmin = $_SESSION['user']['idUsuario'];
$msg = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
  $nombre = trim($_POST['nombre'] ?? '');
  $telefono = trim($_POST['telefono'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $usuario = trim($_POST['usuario'] ?? '');
  $pass = $_POST['password'] ?? '';
  if ($nombre && $email && $usuario) {
    if ($pass) {
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $stmt = $mysqli->prepare("UPDATE tablaUsuarios SET nombreUsuario=?, telefonoUsuario=?, emailUsuario=?, usuarioLogin=?, contrasenaLogin=? WHERE idUsuario=?");
      $stmt->bind_param("sssssi", $nombre, $telefono, $email, $usuario, $hash, $idAdmin);
    } else {
      $stmt = $mysqli->prepare("UPDATE tablaUsuarios SET nombreUsuario=?, telefonoUsuario=?, emailUsuario=?, usuarioLogin=? WHERE idUsuario=?");
      $stmt->bind_param("ssssi", $nombre, $telefono, $email, $usuario, $idAdmin);
    }
    $stmt->execute();
    $stmt->close();
    $_SESSION['user']['nombreUsuario'] = $nombre;
    $_SESSION['user']['telefonoUsuario'] = $telefono;
    $_SESSION['user']['emailUsuario'] = $email;
    $_SESSION['user']['usuarioLogin'] = $usuario;
    $msg = "Datos actualizados.";
  }
}

$stmt = $mysqli->prepare("SELECT nombreUsuario, telefonoUsuario, emailUsuario, usuarioLogin FROM tablaUsuarios WHERE idUsuario=?");
$stmt->bind_param("i", $idAdmin);
$stmt->execute();
$stmt->bind_result($nombre, $telefono, $email, $usuario);
$stmt->fetch();
$stmt->close();

require_once __DIR__ . '/../includes/header.php';
?>
<div class="p-6">
  <div class="w-full max-w-xl mx-auto">
    <div class="flex">
      <a href="dashboard.php" class="inline-block mb-4 px-4 py-2 rounded border border-gray-300 bg-white hover:bg-gray-100">Volver al panel</a>
    </div>
    <h2 class="text-2xl font-bold mb-4 text-center">Mi Perfil (Administrador)</h2>
    <?php if($msg): ?><div class="p-2 bg-green-100 text-green-800 mb-3 text-center"><?= h($msg) ?></div><?php endif; ?>
    <form method="post" class="bg-white p-4 rounded shadow mb-6">
      <input type="hidden" name="action" value="update_profile">
      <label class="block mb-2">Nombre
        <input name="nombre" class="w-full p-2 rounded border mb-2" value="<?= h($nombre) ?>" required>
      </label>
      <label class="block mb-2">Teléfono
        <input name="telefono" class="w-full p-2 rounded border mb-2" value="<?= h($telefono) ?>">
      </label>
      <label class="block mb-2">Email
        <input name="email" class="w-full p-2 rounded border mb-2" value="<?= h($email) ?>" required>
      </label>
      <label class="block mb-2">Usuario
        <input name="usuario" class="w-full p-2 rounded border mb-2" value="<?= h($usuario) ?>" required>
      </label>
      <label class="block mb-2">Contraseña (dejar en blanco para no cambiar)
        <input name="password" type="password" class="w-full p-2 rounded border mb-2" autocomplete="new-password">
      </label>
      <div class="flex justify-end mt-2">
        <button class="btn-primary px-4 py-2 rounded">Actualizar datos</button>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
