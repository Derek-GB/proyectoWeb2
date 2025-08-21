<?php
// agente/agregar_propiedad.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (!isLoggedIn() || ($_SESSION['user']['privilegioUsuario'] ?? '') !== 'agente') {
    header("Location: /proyecto/login.php");
    exit;
}

$idAgente = $_SESSION['user']['idUsuario'];
$msg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_property') {
    $tipo = $_POST['tipo'] ?? 'venta';
    $dest = isset($_POST['destacada']) ? 1 : 0;
    $titulo = $_POST['titulo'] ?? '';
    $brief = $_POST['brief'] ?? '';
    $precio = floatval($_POST['precio'] ?? 0);
    $descripcionLarga = $_POST['descripcionLarga'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    $img = uploadFile('imagen');
    $mapaImage = uploadFile('mapa_image');
    if (!$img) $img = getConfig($mysqli)['bannerImagen'];
    if (!$mapaImage) $mapaImage = null;
    $stmt = $mysqli->prepare("INSERT INTO tablaPropiedades (tipoPropiedad, propiedadDestacada, tituloPropiedad, descripcionBrevePropiedad, precioPropiedad, idAgente, imagenDestacadaPropiedad, descripcionLargaPropiedad, mapaPropiedad, ubicacionPropiedad) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sissdissss", $tipo, $dest, $titulo, $brief, $precio, $idAgente, $img, $descripcionLarga, $mapaImage, $ubicacion);
    $stmt->execute();
    $stmt->close();
    $msg = "Propiedad agregada.";
    header("Location: panel.php");
    exit;
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="p-6">
  <h2 class="text-2xl font-bold mb-4">Agregar Propiedad</h2>
  <?php if($msg): ?><div class="p-2 bg-green-100 text-green-800 mb-3"><?= h($msg) ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded mb-6">
    <input type="hidden" name="action" value="add_property">
    <div class="grid md:grid-cols-2 gap-2">
      <select name="tipo" class="p-2 rounded">
        <option value="venta">Venta</option>
        <option value="alquiler">Alquiler</option>
      </select>
      <label class="flex items-center gap-2"><input type="checkbox" name="destacada"> Destacada</label>
    </div>
    <input name="titulo" placeholder="Titulo" class="w-full p-2 rounded mt-2" required>
    <textarea name="brief" placeholder="Descripción breve" class="w-full p-2 rounded mt-2"></textarea>
    <input name="precio" placeholder="Precio" class="w-full p-2 rounded mt-2">
    <input name="ubicacion" placeholder="Ubicación" class="w-full p-2 rounded mt-2">
    <div class="grid md:grid-cols-2 gap-2 mt-2">
      <label class="block">Imagen destacada <input type="file" name="imagen" class="w-full p-1"></label>
      <label class="block">
        Mapa (imagen) 
        <input type="file" name="mapa_image" class="w-full p-1">
      </label>
    </div>
    <label class="block mt-2">Descripción larga <textarea name="descripcionLarga" class="w-full p-2 rounded"></textarea></label>
    <div class="flex justify-end mt-2">
      <button class="btn-primary px-4 py-2 rounded">Agregar</button>
    </div>
  </form>
  <a href="panel.php" class="inline-block px-4 py-2 rounded border">Volver al panel</a>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
