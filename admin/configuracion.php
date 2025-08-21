<?php
// admin/configuracion.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!isAdmin()) { header("Location: /proyecto/login.php"); exit; }

$config = getConfig($mysqli);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save_settings') {
    // subir archivos si vienen
    $icon_main = uploadFile('icon_main');
    $icon_white = uploadFile('icon_white');
    $banner = uploadFile('banner_image');
    $quienes = uploadFile('quienes_image');

    $colorAzul = $_POST['colorAzul'] ?? $config['colorAzul'];
    $colorAmarillo = $_POST['colorAmarillo'] ?? $config['colorAmarillo'];
    $colorGris = $_POST['colorGris'] ?? $config['colorGris'];
    $colorBlanco = $_POST['colorBlanco'] ?? $config['colorBlanco'];
    $bannerMensaje = $_POST['bannerMensaje'] ?? $config['bannerMensaje'];
    $quienesSomos = $_POST['quienesSomos'] ?? $config['quienesSomos'];
    $facebook = $_POST['facebook'] ?? $config['facebook'];
    $instagram = $_POST['instagram'] ?? $config['instagram'];
    $youtube = $_POST['youtube'] ?? $config['youtube'];
    $direccion = $_POST['direccion'] ?? $config['direccion'];
    $telefono = $_POST['telefono'] ?? $config['telefono'];
    $email = $_POST['email'] ?? $config['email'];

    // set paths
    $icon_main = $icon_main ?: $config['iconoPrincipal'];
    $icon_white = $icon_white ?: $config['iconoBlanco'];
    $banner = $banner ?: $config['bannerImagen'];
    $quienes = $quienes ?: $config['quienesSomosImagen'];

    // actualizar la fila existente (usamos idConfiguracion si existe)
    $row = $mysqli->query("SELECT idConfiguracion FROM tablaConfiguracionPagina ORDER BY idConfiguracion DESC LIMIT 1")->fetch_assoc();
    $idCfg = $row['idConfiguracion'] ?? null;
    if ($idCfg) {
        $stmt = $mysqli->prepare("UPDATE tablaConfiguracionPagina SET colorAzul=?, colorAmarillo=?, colorGris=?, colorBlanco=?, iconoPrincipal=?, iconoBlanco=?, bannerImagen=?, bannerMensaje=?, quienesSomos=?, quienesSomosImagen=?, facebook=?, instagram=?, youtube=?, direccion=?, telefono=?, email=? WHERE idConfiguracion=?");
        $stmt->bind_param("ssssssssssssssssi",
            $colorAzul,$colorAmarillo,$colorGris,$colorBlanco,$icon_main,$icon_white,$banner,$bannerMensaje,$quienesSomos,$quienes,$facebook,$instagram,$youtube,$direccion,$telefono,$email,$idCfg
        );
        $stmt->execute();
        $stmt->close();
    } else {
        // no hay fila (raro), insertar
        $stmt = $mysqli->prepare("INSERT INTO tablaConfiguracionPagina (colorAzul,colorAmarillo,colorGris,colorBlanco,iconoPrincipal,iconoBlanco,bannerImagen,bannerMensaje,quienesSomos,quienesSomosImagen,facebook,instagram,youtube,direccion,telefono,email) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssssssssssss",$colorAzul,$colorAmarillo,$colorGris,$colorBlanco,$icon_main,$icon_white,$banner,$bannerMensaje,$quienesSomos,$quienes,$facebook,$instagram,$youtube,$direccion,$telefono,$email);
        $stmt->execute();
        $stmt->close();
    }
    $msg = "Configuración actualizada.";
    $config = getConfig($mysqli); // recargar
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="p-6">
  <a href="dashboard.php" class="inline-block mb-4 px-4 py-2 rounded border border-gray-300 bg-white hover:bg-gray-100">&larr; Regresar al panel</a>
  <h2 class="text-2xl font-bold mb-4">Configuración del Sitio</h2>
  <?php if(isset($msg)): ?><div class="p-2 bg-green-100 text-green-800 mb-3"><?= h($msg) ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded">
    <input type="hidden" name="action" value="save_settings">
    <div class="grid grid-cols-2 gap-2">
      <label>Color Azul<input name="colorAzul" type="color" value="<?= h($config['colorAzul']) ?>" class="w-full p-1"></label>
      <label>Color Amarillo<input name="colorAmarillo" type="color" value="<?= h($config['colorAmarillo']) ?>" class="w-full p-1"></label>
    </div>
    <div class="grid grid-cols-2 gap-2 mt-2">
      <label>Color Gris<input name="colorGris" type="color" value="<?= h($config['colorGris']) ?>" class="w-full p-1"></label>
      <label>Color Blanco<input name="colorBlanco" type="color" value="<?= h($config['colorBlanco']) ?>" class="w-full p-1"></label>
    </div>
    <label class="block mt-2">Mensaje banner<textarea name="bannerMensaje" class="w-full p-2 rounded"><?= h($config['bannerMensaje']) ?></textarea></label>
    <label class="block mt-2">Texto Quiénes Somos<textarea name="quienesSomos" class="w-full p-2 rounded"><?= h($config['quienesSomos']) ?></textarea></label>

    <div class="grid grid-cols-2 gap-2 mt-2">
      <label>Icono principal<input type="file" name="icon_main" class="w-full p-1"></label>
      <label>Icono blanco<input type="file" name="icon_white" class="w-full p-1"></label>
    </div>
    <div class="grid grid-cols-2 gap-2 mt-2">
      <label>Imagen banner<input type="file" name="banner_image" class="w-full p-1"></label>
      <label>Imagen Quiénes Somos<input type="file" name="quienes_image" class="w-full p-1"></label>
    </div>

    <h5 class="font-semibold mt-2">Redes Sociales</h5>
    <input name="facebook" placeholder="Facebook" value="<?= h($config['facebook']) ?>" class="w-full p-2 rounded">
    <input name="youtube" placeholder="YouTube" value="<?= h($config['youtube']) ?>" class="w-full p-2 rounded">
    <input name="instagram" placeholder="Instagram" value="<?= h($config['instagram']) ?>" class="w-full p-2 rounded">

    <h5 class="font-semibold mt-2">Contacto / Dirección</h5>
    <input name="direccion" placeholder="Dirección" value="<?= h($config['direccion']) ?>" class="w-full p-2 rounded">
    <div class="grid grid-cols-2 gap-2">
      <input name="telefono" placeholder="Teléfono" value="<?= h($config['telefono']) ?>" class="w-full p-2 rounded">
      <input name="email" placeholder="Email" value="<?= h($config['email']) ?>" class="w-full p-2 rounded">
    </div>

    <div class="flex gap-2 justify-end mt-2">
      <button type="submit" class="px-3 py-1 rounded btn-primary">Guardar</button>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
