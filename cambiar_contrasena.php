<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nueva = $_POST['nueva'] ?? '';
    $repetir = $_POST['repetir'] ?? '';
    if ($nueva === '' || $repetir === '') {
        $msg = 'Completa ambos campos.';
    } elseif ($nueva !== $repetir) {
        $msg = 'Las contraseñas no coinciden.';
    } else {
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('UPDATE tablaUsuarios SET contrasenaLogin=?, usuarioNuevo=0 WHERE idUsuario=?');
        $stmt->bind_param('si', $hash, $usuario['idUsuario']);
        $stmt->execute();
        $stmt->close();
        // Actualizar session
        $_SESSION['usuario']['usuarioNuevo'] = 0;
        header('Location: index.php?cambio=ok');
        exit;
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="container mx-auto px-6 py-12">
    <h2 class="text-2xl font-bold mb-6">Cambiar Contraseña</h2>
    <?php if ($msg): ?>
        <div class="bg-red-200 p-2 rounded mb-3"><?= h($msg) ?></div><?php endif; ?>
    <form method="post" class="max-w-md">
        <input type="password" name="nueva" placeholder="Nueva contraseña" class="w-full p-2 rounded mb-2" required>
        <input type="password" name="repetir" placeholder="Repetir contraseña" class="w-full p-2 rounded mb-2" required>
        <button type="submit" class="btn-primary px-4 py-2 rounded">Actualizar</button>
    </form>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>