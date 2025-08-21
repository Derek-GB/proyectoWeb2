<?php
// agente/editar_propiedad.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (!isLoggedIn() || ($_SESSION['user']['privilegioUsuario'] ?? '') !== 'agente') {
    header("Location: /proyecto/login.php");
    exit;
}

$idAgente = $_SESSION['user']['idUsuario'];
$msg = null;

// Cargar propiedad a editar
$editing = null;
if (isset($_GET['id'])) {
    $editId = intval($_GET['id']);
    $stmt = $mysqli->prepare("SELECT * FROM tablaPropiedades WHERE idPropiedad=? AND idAgente=?");
    $stmt->bind_param("ii", $editId, $idAgente);
    $stmt->execute();
    $res = $stmt->get_result();
    $editing = $res->fetch_assoc();
    $stmt->close();
    if (!$editing) {
        header("Location: panel.php");
        exit;
    }
}

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_property') {
    $id = intval($_POST['id'] ?? 0);
    $tipo = $_POST['tipo'] ?? 'venta';
    $dest = isset($_POST['destacada']) ? 1 : 0;
    $titulo = $_POST['titulo'] ?? '';
    $brief = $_POST['brief'] ?? '';
    $precio = floatval($_POST['precio'] ?? 0);
    $descripcionLarga = $_POST['descripcionLarga'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    $img = uploadFile('imagen');
    $mapaImage = uploadFile('mapa_image');

    if ($id > 0) {
        // actualizar
        if ($img && $mapaImage) {
            $stmt = $mysqli->prepare("UPDATE tablaPropiedades SET tipoPropiedad=?, propiedadDestacada=?, tituloPropiedad=?, descripcionBrevePropiedad=?, precioPropiedad=?, imagenDestacadaPropiedad=?, descripcionLargaPropiedad=?, mapaPropiedad=?, ubicacionPropiedad=? WHERE idPropiedad=? AND idAgente=?");
            $stmt->bind_param("sissdsssssi", $tipo, $dest, $titulo, $brief, $precio, $img, $descripcionLarga, $mapaImage, $ubicacion, $id, $idAgente);
        } elseif ($img && !$mapaImage) {
            $stmt = $mysqli->prepare("UPDATE tablaPropiedades SET tipoPropiedad=?, propiedadDestacada=?, tituloPropiedad=?, descripcionBrevePropiedad=?, precioPropiedad=?, imagenDestacadaPropiedad=?, descripcionLargaPropiedad=?, ubicacionPropiedad=? WHERE idPropiedad=? AND idAgente=?");
            $stmt->bind_param("sissdsssii", $tipo, $dest, $titulo, $brief, $precio, $img, $descripcionLarga, $ubicacion, $id, $idAgente);
        } elseif (!$img && $mapaImage) {
            $stmt = $mysqli->prepare("UPDATE tablaPropiedades SET tipoPropiedad=?, propiedadDestacada=?, tituloPropiedad=?, descripcionBrevePropiedad=?, precioPropiedad=?, descripcionLargaPropiedad=?, mapaPropiedad=?, ubicacionPropiedad=? WHERE idPropiedad=? AND idAgente=?");
            $stmt->bind_param("sissdss sii", $tipo, $dest, $titulo, $brief, $precio, $descripcionLarga, $mapaImage, $ubicacion, $id, $idAgente);
        } else {
            $stmt = $mysqli->prepare("UPDATE tablaPropiedades SET tipoPropiedad=?, propiedadDestacada=?, tituloPropiedad=?, descripcionBrevePropiedad=?, precioPropiedad=?, descripcionLargaPropiedad=?, ubicacionPropiedad=? WHERE idPropiedad=? AND idAgente=?");
            $stmt->bind_param("sissds sii", $tipo, $dest, $titulo, $brief, $precio, $descripcionLarga, $ubicacion, $id, $idAgente);
        }
        $stmt->execute();
        $stmt->close();
        $msg = "Propiedad actualizada.";
        header("Location: panel.php");
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="p-6">
  <h2 class="text-2xl font-bold mb-4">Editar Propiedad</h2>
  <?php if($msg): ?><div class="p-2 bg-green-100 text-green-800 mb-3"><?= h($msg) ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded mb-6">
    <input type="hidden" name="action" value="save_property">
    <input type="hidden" name="id" value="<?= h($editing['idPropiedad'] ?? 0) ?>">
    <div class="grid md:grid-cols-2 gap-2">
      <select name="tipo" class="p-2 rounded">
        <option value="venta" <?= ( ($editing['tipoPropiedad'] ?? '') === 'venta') ? 'selected':'' ?>>Venta</option>
        <option value="alquiler" <?= ( ($editing['tipoPropiedad'] ?? '') === 'alquiler') ? 'selected':'' ?>>Alquiler</option>
      </select>
      <label class="flex items-center gap-2"><input type="checkbox" name="destacada" <?= (($editing['propiedadDestacada'] ?? 0)==1)?'checked':'' ?>> Destacada</label>
    </div>
    <input name="titulo" placeholder="Titulo" class="w-full p-2 rounded mt-2" value="<?= h($editing['tituloPropiedad'] ?? '') ?>" required>
    <textarea name="brief" placeholder="Descripción breve" class="w-full p-2 rounded mt-2"><?= h($editing['descripcionBrevePropiedad'] ?? '') ?></textarea>
    <input name="precio" placeholder="Precio" class="w-full p-2 rounded mt-2" value="<?= h($editing['precioPropiedad'] ?? '') ?>">
    <input name="ubicacion" placeholder="Ubicación" class="w-full p-2 rounded mt-2" value="<?= h($editing['ubicacionPropiedad'] ?? '') ?>">
    <div class="grid md:grid-cols-2 gap-2 mt-2">
      <label class="block">Imagen destacada <input type="file" name="imagen" class="w-full p-1"></label>
      <label class="block">
        Mapa (imagen) 
        <input type="file" name="mapa_image" class="w-full p-1">
        <?php if(!empty($editing['mapaPropiedad'])): ?>
          <div class="mt-2">
            <small>Mapa actual:</small><br>
            <img src="<?= h($editing['mapaPropiedad']) ?>" alt="Mapa actual" class="w-48 h-auto rounded border">
          </div>
        <?php endif; ?>
      </label>
    </div>
    <label class="block mt-2">Descripción larga <textarea name="descripcionLarga" class="w-full p-2 rounded"><?= h($editing['descripcionLargaPropiedad'] ?? '') ?></textarea></label>
    <div class="flex justify-end mt-2">
      <button class="btn-primary px-4 py-2 rounded">Guardar</button>
    </div>
  </form>
  <a href="panel.php" class="inline-block px-4 py-2 rounded border">Volver al panel</a>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
