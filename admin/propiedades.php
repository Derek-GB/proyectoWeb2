<?php
// Aquí puedes administrar todas las propiedades del sistema: agregar, editar o eliminar a tu gusto
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!isAdmin()) {
  header("Location: /proyecto/login.php");
  exit;
}

// Sección para agregar una nueva propiedad o editar una existente, según lo que el usuario quiera hacer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_property') {
  $id = intval($_POST['id'] ?? 0);
  $tipo = $_POST['tipo'] ?? 'venta';
  $dest = isset($_POST['destacada']) ? 1 : 0;
  $titulo = $_POST['titulo'] ?? '';
  $brief = $_POST['brief'] ?? '';
  $precio = floatval($_POST['precio'] ?? 0);
  $descripcionLarga = $_POST['descripcionLarga'] ?? '';
  $ubicacion = $_POST['ubicacion'] ?? '';
  $idAgente = intval($_POST['idAgente'] ?? ($_SESSION['user']['idUsuario'] ?? 1));

  // Aquí se suben la imagen principal y el mapa de la propiedad (el mapa ahora es un archivo)
  $img = uploadFile('imagen');           // Subo la imagen principal de la propiedad
  $mapaImage = uploadFile('mapa_image'); // Subo la imagen del mapa de la propiedad

  if ($id > 0) {
    // Si ya existe, actualizo la propiedad con los nuevos datos
    if ($img && $mapaImage) {
      $stmt = $mysqli->prepare("UPDATE tablaPropiedades SET tipoPropiedad=?, propiedadDestacada=?, tituloPropiedad=?, descripcionBrevePropiedad=?, precioPropiedad=?, idAgente=?, imagenDestacadaPropiedad=?, descripcionLargaPropiedad=?, mapaPropiedad=?, ubicacionPropiedad=? WHERE idPropiedad=?");
      $stmt->bind_param("sissdi ss ssi", $tipo, $dest, $titulo, $brief, $precio, $idAgente, $img, $descripcionLarga, $mapaImage, $ubicacion, $id);
    } elseif ($img && !$mapaImage) {
      $stmt = $mysqli->prepare("UPDATE tablaPropiedades SET tipoPropiedad=?, propiedadDestacada=?, tituloPropiedad=?, descripcionBrevePropiedad=?, precioPropiedad=?, idAgente=?, imagenDestacadaPropiedad=?, descripcionLargaPropiedad=?, ubicacionPropiedad=? WHERE idPropiedad=?");
      $stmt->bind_param("sissdi sssi", $tipo, $dest, $titulo, $brief, $precio, $idAgente, $img, $descripcionLarga, $ubicacion, $id);
    } elseif (!$img && $mapaImage) {
      $stmt = $mysqli->prepare("UPDATE tablaPropiedades SET tipoPropiedad=?, propiedadDestacada=?, tituloPropiedad=?, descripcionBrevePropiedad=?, precioPropiedad=?, idAgente=?, descripcionLargaPropiedad=?, mapaPropiedad=?, ubicacionPropiedad=? WHERE idPropiedad=?");
      $stmt->bind_param("sissdi sssi", $tipo, $dest, $titulo, $brief, $precio, $idAgente, $descripcionLarga, $mapaImage, $ubicacion, $id);
    } else {
      $stmt = $mysqli->prepare("UPDATE tablaPropiedades SET tipoPropiedad=?, propiedadDestacada=?, tituloPropiedad=?, descripcionBrevePropiedad=?, precioPropiedad=?, idAgente=?, descripcionLargaPropiedad=?, ubicacionPropiedad=? WHERE idPropiedad=?");
      $stmt->bind_param("sissdi ssi", $tipo, $dest, $titulo, $brief, $precio, $idAgente, $descripcionLarga, $ubicacion, $id);
    }

    $stmt->execute();
    $stmt->close();
    $msg = "Propiedad actualizada.";
  } else {
    // Si es nueva, la guardo en la base de datos
    if (!$img)
      $img = getConfig($mysqli)['bannerImagen'];
    if (!$mapaImage)
      $mapaImage = null; // Si no suben mapa, lo dejo vacío

    $stmt = $mysqli->prepare("INSERT INTO tablaPropiedades (tipoPropiedad, propiedadDestacada, tituloPropiedad, descripcionBrevePropiedad, precioPropiedad, idAgente, imagenDestacadaPropiedad, descripcionLargaPropiedad, mapaPropiedad, ubicacionPropiedad) VALUES (?,?,?,?,?,?,?,?,?,?)");
    // Tipos de datos para la consulta preparada (por si te pierdes: string, int, string, ...)
    $stmt->bind_param("sissdissss", $tipo, $dest, $titulo, $brief, $precio, $idAgente, $img, $descripcionLarga, $mapaImage, $ubicacion);
    $stmt->execute();
    $stmt->close();
    $msg = "Propiedad creada.";
  }
}

// Aquí borro una propiedad si el usuario lo pide
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $stmt = $mysqli->prepare("DELETE FROM tablaPropiedades WHERE idPropiedad=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
  header("Location: propiedades.php");
  exit;
}

// Cargo los datos de la propiedad para editarla si el usuario selecciona esa opción
$editing = null;
if (isset($_GET['edit'])) {
  $editId = intval($_GET['edit']);
  $editing = getPropiedadById($mysqli, $editId);
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="p-6">
  <a href="dashboard.php"
    class="inline-block mb-4 px-4 py-2 rounded border border-gray-300 bg-white hover:bg-gray-100">&larr; Regresar al
    panel</a>
  <h2 class="text-2xl font-bold mb-4">Administrar Propiedades</h2>
  <?php if (isset($msg)): ?>
    <div class="p-2 bg-green-100 text-green-800 mb-3"><?= h($msg) ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded mb-6">
    <input type="hidden" name="action" value="save_property">
    <input type="hidden" name="id" value="<?= h($editing['idPropiedad'] ?? 0) ?>">
    <div class="grid md:grid-cols-2 gap-2">
      <select name="tipo" class="p-2 rounded border focus:outline-none focus:ring-2 focus:ring-blue-300">
        <option value="venta" <?= (($editing['tipoPropiedad'] ?? '') === 'venta') ? 'selected' : '' ?>>Venta</option>
        <option value="alquiler" <?= (($editing['tipoPropiedad'] ?? '') === 'alquiler') ? 'selected' : '' ?>>Alquiler
        </option>
      </select>
      <label class="flex items-center gap-2"><input type="checkbox" name="destacada"
          class="rounded border focus:ring-2 focus:ring-blue-300" <?= (($editing['propiedadDestacada'] ?? 0) == 1) ? 'checked' : '' ?>> Destacada</label>
    </div>

    <input name="titulo" placeholder="Titulo"
      class="w-full p-2 rounded border mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
      value="<?= h($editing['tituloPropiedad'] ?? '') ?>" required>
    <textarea name="brief" placeholder="Descripción breve"
      class="w-full p-2 rounded border mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
      required><?= h($editing['descripcionBrevePropiedad'] ?? '') ?></textarea>
    <input type="number" name="precio" placeholder="Precio"
      class="w-full p-2 rounded border mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
      value="<?= h($editing['precioPropiedad'] ?? '') ?>" min="1" required>
    <input name="ubicacion" placeholder="Ubicación"
      class="w-full p-2 rounded border mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
      value="<?= h($editing['ubicacionPropiedad'] ?? '') ?>" required>

    <div class="grid md:grid-cols-2 gap-2 mt-2">
      <label class="block">Imagen destacada <input type="file" name="imagen"
          class="w-full p-1 rounded border focus:outline-none focus:ring-2 focus:ring-blue-300"></label>

      <label class="block">
        Mapa (imagen)
        <input type="file" name="mapa_image"
          class="w-full p-1 rounded border focus:outline-none focus:ring-2 focus:ring-blue-300">
        <?php if (!empty($editing['mapaPropiedad'])): ?>
          <div class="mt-2">
            <small>Mapa actual:</small><br>
            <img src="<?= h($editing['mapaPropiedad']) ?>" alt="Mapa actual" class="w-48 h-auto rounded border">
          </div>
        <?php endif; ?>
      </label>
    </div>

    <label class="block mt-2">Descripción larga <textarea name="descripcionLarga"
        class="w-full p-2 rounded border focus:outline-none focus:ring-2 focus:ring-blue-300"><?= h($editing['descripcionLargaPropiedad'] ?? '') ?></textarea></label>

    <div class="flex justify-end mt-2">
      <button class="btn-primary px-4 py-2 rounded">Guardar</button>
    </div>
  </form>

  <h3 class="font-bold mb-2">Listado de propiedades</h3>
  <?php
  $stmt = $mysqli->query("SELECT idPropiedad,tituloPropiedad,tipoPropiedad,precioPropiedad FROM tablaPropiedades ORDER BY idPropiedad DESC");
  $list = $stmt ? $stmt->fetch_all(MYSQLI_ASSOC) : [];
  ?>
  <ul class="space-y-2">
    <?php foreach ($list as $r): ?>
      <li class="p-3 bg-white rounded flex justify-between items-center">
        <div>
          <strong><?= h($r['tituloPropiedad']) ?></strong><br>
          <span class="muted"><?= h($r['tipoPropiedad']) ?> - <?= h($r['precioPropiedad']) ?></span>
        </div>
        <div>
          <a href="?edit=<?= h($r['idPropiedad']) ?>" class="px-2 py-1 text-xs bg-yellow-100 rounded">Editar</a>
          <a href="?delete=<?= h($r['idPropiedad']) ?>" onclick="return confirm('Eliminar?')"
            class="px-2 py-1 text-xs bg-red-100 rounded">Eliminar</a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>