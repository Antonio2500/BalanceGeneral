<?php



// Incluimos el archivo funciones.php
include 'funciones.php';

// obtener el id de la cuenta por la url
$cuenta = $_GET['cuenta'];
$ID = $_GET['ID'];


    $conexion = conectarBD();

    // Verificar si la cuenta existe antes de eliminarla
    $consulta = "SELECT COUNT(*) AS cuenta_existente FROM cuentas WHERE ID = $ID";
    $resultado = $conexion->query($consulta);
    $fila = $resultado->fetch_assoc();

    if ($fila['cuenta_existente'] == 0) {
        // La cuenta no existe, no es necesario eliminar nada
        $conexion->close();
        return;
    }

    // Eliminar registros de tablas relacionadas
    $nombre_cuenta = ""; // Asegúrate de obtener el nombre de la cuenta desde la tabla cuentas
    $sql_relacional = "DELETE FROM ${nombre_cuenta}_relacionada WHERE ${nombre_cuenta}_ID = $cuentaID";
    $conexion->query($sql_relacional);

    // Eliminar registro de la tabla cuentas
    $sql_cuentas = "DELETE FROM cuentas WHERE ID = $cuentaID";
    $conexion->query($sql_cuentas);

    // Cerrar la conexión
    $conexion->close();

    // Redirigir a la página de cuentas después de eliminar
    header("Location: pagina_de_cuentas.php");
    exit(); // Asegúrate de terminar la ejecución del script después de la redirección










?>