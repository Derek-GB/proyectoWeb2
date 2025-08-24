<?php
// Aquí están todas las funciones que uso en el proyecto, desde helpers hasta consultas a la base de datos
require_once __DIR__ . '/config.php';

// Esta función sirve para escapar caracteres especiales y evitar problemas de seguridad 
function h($s)
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

/* Acá manejo la configuración general de la página: leo de la base de datos o pongo valores por defecto si no hay nada */
function getConfig($mysqli)
{
    $res = $mysqli->query("SELECT * FROM tablaConfiguracionPagina ORDER BY idConfiguracion DESC LIMIT 1");
    if ($res && $res->num_rows > 0)
        return $res->fetch_assoc();

    // Si no hay configuración en la base de datos, uso estos valores por defecto
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
        'facebook' => '#',
        'instagram' => '#',
        'youtube' => '#',
        'direccion' => 'Ciudad, Calle Principal 123',
        'telefono' => '0000-0000',
        'email' => 'info@ejemplo.com'
    ];

    $stmt = $mysqli->prepare("INSERT INTO tablaConfiguracionPagina 
        (colorAzul,colorAmarillo,colorGris,colorBlanco,iconoPrincipal,iconoBlanco,bannerImagen,bannerMensaje,quienesSomos,quienesSomosImagen,facebook,instagram,youtube,direccion,telefono,email)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param(
        "ssssssssssssssss",
        $defaults['colorAzul'],
        $defaults['colorAmarillo'],
        $defaults['colorGris'],
        $defaults['colorBlanco'],
        $defaults['iconoPrincipal'],
        $defaults['iconoBlanco'],
        $defaults['bannerImagen'],
        $defaults['bannerMensaje'],
        $defaults['quienesSomos'],
        $defaults['quienesSomosImagen'],
        $defaults['facebook'],
        $defaults['instagram'],
        $defaults['youtube'],
        $defaults['direccion'],
        $defaults['telefono'],
        $defaults['email']
    );
    $stmt->execute();
    $stmt->close();
    return $defaults;
}

/* Acá están las funciones para trabajar con las propiedades: traer, buscar, etc. */
function getProperties($mysqli, $filter = null, $limit = 3, $search = null)
{
    // Armo la consulta para traer propiedades y también el nombre del agente
    $sql = "SELECT p.*, u.nombreUsuario FROM tablaPropiedades p LEFT JOIN tablaUsuarios u ON p.idAgente = u.idUsuario";
    $where = [];
    // Filtro según lo que el usuario pidió: destacadas, venta o alquiler
    if ($filter === 'destacadas') {
        $where[] = "p.propiedadDestacada = 1";
    } elseif ($filter === 'venta' || $filter === 'alquiler') {
        if (!$search) {
            $where[] = "p.tipoPropiedad = '" . $mysqli->real_escape_string($filter) . "'";
        }
    }
    // Si el usuario está buscando algo, armo el filtro de búsqueda
    if ($search) {
        $s = $mysqli->real_escape_string($search);
        $searchFields = [
            "p.tituloPropiedad LIKE '%$s%'",
            "p.descripcionBrevePropiedad LIKE '%$s%'",
            "p.precioPropiedad LIKE '%$s%'",
            "p.ubicacionPropiedad LIKE '%$s%'",
            "p.tipoPropiedad LIKE '%$s%'"
        ];
        $where[] = '(' . implode(' OR ', $searchFields) . ')';
    }
    // Si hay filtros, los agrego a la consulta
    if ($where) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }
    $sql .= " ORDER BY p.idPropiedad DESC LIMIT " . intval($limit);
    $res = $mysqli->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

// Esta función trae una propiedad por su ID, junto con los datos del agente
function getPropertyById($mysqli, $id)
{
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
/* Acá están las funciones para trabajar con los usuarios: login, buscar, etc. */
function getUserByLogin($mysqli, $login)
{
    $stmt = $mysqli->prepare("SELECT * FROM tablaUsuarios WHERE LOWER(usuarioLogin) = LOWER(?) LIMIT 1");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $u = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $u ?: null;
}

/* Función para subir archivos al servidor (imágenes, mapas, etc.) */
function uploadFile($inputName)
{
    $uploadsDir = __DIR__ . '/../assets/uploads';
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK)
        return null;
    $name = time() . "_" . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($_FILES[$inputName]['name']));
    $target = $uploadsDir . '/' . $name;
    if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $target)) {
        return 'assets/uploads/' . $name;
    }
    return null;
}

