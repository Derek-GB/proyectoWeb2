<?php
// login.php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $error = "Rellena usuario y contraseña.";
    } else {
        $user = getUserByLogin($mysqli, $usuario);
        if ($user) {
            $stored = $user['contrasenaLogin'];
            $ok = false;
            // Compatibilidad MD5 antiguo
            if (strlen($stored) === 32 && ctype_xdigit($stored)) {
                if (md5($password) === $stored) $ok = true;
            } else {
                if (password_verify($password, $stored)) $ok = true;
            }
            if ($ok) {
                // establecer session (no guardar contraseña en session)
                        if (isset($user['usuarioNuevo']) && $user['usuarioNuevo'] == 1) {
                            unset($user['contrasenaLogin']);
                            $_SESSION['user'] = $user;
                            header("Location: /proyecto/cambiarContra.php");
                            exit;
                        } else {
                            unset($user['contrasenaLogin']);
                            $_SESSION['user'] = $user;
                            header("Location: /proyecto/index.php");
                            exit;
                        }
            }
        }
        $error = "Usuario o contraseña incorrectos.";
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="container mx-auto px-6 py-12">
  <h2 class="text-2xl font-bold mb-6">Iniciar Sesión</h2>
  <?php if(isset($error)): ?><div class="bg-red-200 p-2 rounded mb-3"><?= h($error) ?></div><?php endif; ?>
  <form method="post" class="max-w-md">
    <input name="usuario" placeholder="Usuario" class="w-full p-2 rounded mb-2" required>
    <input type="password" name="password" placeholder="Contraseña" class="w-full p-2 rounded mb-2" required>
    <button type="submit" class="btn-primary px-4 py-2 rounded">Entrar</button>
  </form>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
