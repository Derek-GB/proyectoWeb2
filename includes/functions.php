<?php
// includes/functions.php
require_once __DIR__ . '/config.php';

function h($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

/* ------------------ Configuración (leer/crear por defecto) ------------------ */
function getConfig($mysqli) {
    $res = $mysqli->query("SELECT * FROM tablaConfiguracionPagina ORDER BY idConfiguracion DESC LIMIT 1");
    if ($res && $res->num_rows > 0) return $res->fetch_assoc();

    // defaults
    $defaults = [
        'colorAzul' => '#1f2d6b',
        'colorAmarillo' => '#f0b429',
        'colorGris' => '#f3f4f6',
        'colorBlanco' => '#ffffff',
        'iconoPrincipal' => 'assets/uploads/default_logo.png',
        'iconoBlanco' => 'assets/uploads/default_logo_white.png',
        'bannerImagen' => 'assets/uploads/default_banner.jpg',
        'bannerMensaje' => 'PERMITENOS AYUDARTE A CUMPLIR TUS SUEÑOS',
        'quienesSomos' => 'Somos una empresa dedicada a ayudarle a encontrar la propiedad ideal.',
        'quienesSomosImagen' => 'assets/uploads/default_quienes.jpg',
        'facebook' => '#','instagram' => '#','youtube' => '#',
        'direccion' => 'Ciudad, Calle Principal 123','telefono' => '0000-0000','email' => 'info@ejemplo.com'
    ];

    $stmt = $mysqli->prepare("INSERT INTO tablaConfiguracionPagina 
        (colorAzul,colorAmarillo,colorGris,colorBlanco,iconoPrincipal,iconoBlanco,bannerImagen,bannerMensaje,quienesSomos,quienesSomosImagen,facebook,instagram,youtube,direccion,telefono,email)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssssssssssss",
        $defaults['colorAzul'],$defaults['colorAmarillo'],$defaults['colorGris'],$defaults['colorBlanco'],
        $defaults['iconoPrincipal'],$defaults['iconoBlanco'],$defaults['bannerImagen'],$defaults['bannerMensaje'],
        $defaults['quienesSomos'],$defaults['quienesSomosImagen'],
        $defaults['facebook'],$defaults['instagram'],$defaults['youtube'],
        $defaults['direccion'],$defaults['telefono'],$defaults['email']
    );
    $stmt->execute();
    $stmt->close();
    return $defaults;
}

/* ------------------ Propiedades ------------------ */
function getProperties($mysqli, $filter = null, $limit = 3) {
    $sql = "SELECT p.*, u.nombreUsuario
            FROM tablaPropiedades p
            LEFT JOIN tablaUsuarios u ON p.idAgente = u.idUsuario";
    if ($filter === 'destacadas') {
        $sql .= " WHERE p.propiedadDestacada = 1";
    } elseif ($filter === 'venta') {
        $sql .= " WHERE p.tipoPropiedad = 'venta'";
    } elseif ($filter === 'alquiler') {
        $sql .= " WHERE p.tipoPropiedad = 'alquiler'";
    }
    $sql .= " ORDER BY p.idPropiedad DESC LIMIT " . intval($limit);
    $res = $mysqli->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function getPropertyById($mysqli, $id) {
    $stmt = $mysqli->prepare("SELECT p.*, u.nombreUsuario, u.telefonoUsuario, u.emailUsuario
                              FROM tablaPropiedades p
                              LEFT JOIN tablaUsuarios u ON p.idAgente = u.idUsuario
                              WHERE p.idPropiedad = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $res ?: null;
}
/* ------------------ Usuarios ------------------ */
function getUserByLogin($mysqli, $login) {
    $stmt = $mysqli->prepare("SELECT * FROM tablaUsuarios WHERE LOWER(usuarioLogin) = LOWER(?) LIMIT 1");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $u = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $u ?: null;
}

/* ------------------ Upload helper ------------------ */
function uploadFile($inputName) {
    $uploadsDir = __DIR__ . '/../assets/uploads';
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) return null;
    $name = time() . "_" . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($_FILES[$inputName]['name']));
    $target = $uploadsDir . '/' . $name;
    if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $target)) {
        return 'assets/uploads/' . $name;
    }
    return null;
}

/* ------------------ Crear admin por defecto si no hay usuarios ------------------ */
function ensureDefaultAdmin($mysqli) {
    $res = $mysqli->query("SELECT COUNT(*) AS c FROM tablaUsuarios");
    $count = 0;
    if ($res) {
        $count = $res->fetch_assoc()['c'] ?? 0;
    }
    if (intval($count) === 0) {
        $nombre = 'Administrador';
        $telefono = '0000-0000';
        $correo = 'admin@local';
        $usuario = 'Admin';
        $pass = password_hash('123', PASSWORD_DEFAULT);
        $priv = 'administrador';
        $stmt = $mysqli->prepare("INSERT INTO tablaUsuarios (nombreUsuario, telefonoUsuario, correoUsuario, emailUsuario, usuarioLogin, contrasenaLogin, privilegioUsuario, usuarioNuevo) VALUES (?,?,?,?,?,?,?,0)");
        $stmt->bind_param("sssssss", $nombre, $telefono, $correo, $correo, $usuario, $pass, $priv);
        $stmt->execute();
        $stmt->close();
    }
}

/* ------------------ Contact form write ------------------ */
function addContact($name, $email, $phone, $message) {
    $file = __DIR__ . '/../data/contacts.json';
    $arr = json_decode(file_get_contents($file), true);
    $arr[] = [
        'id' => time(),
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'message' => $message,
        'created' => date('c')
    ];
    file_put_contents($file, json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
