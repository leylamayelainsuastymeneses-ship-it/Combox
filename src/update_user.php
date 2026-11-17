<?php
include __DIR__ . '/../config/database.php';

// Recibir los datos del formulario
$id           = $_POST['id'];
$firstname    = $_POST['firstname'];
$lastname     = $_POST['lastname'];
$email        = $_POST['email'];
$mobile_phone = $_POST['mobile_phone'];

// ---------- PARTE 1: Actualizar los datos del usuario ----------
$sql = 'UPDATE users
SET firstname = $1, lastname = $2, email = $3, mobile_phone = $4
WHERE id = $5';

$result = pg_query_params($conn, $sql, array($firstname, $lastname, $email, $mobile_phone, $id));

if (!$result) {
    echo "Error al actualizar los datos";
    exit;
}

// ---------- PARTE 2: Subir foto SOLO si se eligió una ----------
if (!empty($_FILES['photo']['name'])) {
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    
    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
        $photo_filename = $mobile_phone . "." . $ext;
        $photo_url = "photos/" . $photo_filename;
        $full_path = __DIR__ . "/" . $photo_url;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $full_path)) {
            // Actualizar la ruta de la foto en la base de datos
            $sql_foto = "UPDATE users SET photo_url = $1 WHERE id = $2";
            pg_query_params($conn, $sql_foto, array($photo_url, $id));
        }
    }
}
// Si no se eligió foto, no hace nada → se mantiene la anterior ✅

// Redirigir a la lista de usuarios
header("Location: listusers.php");
exit;
?>
