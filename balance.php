<!DOCTYPE html>
<html>
<head>
  <title>Formulario de Agregar</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/flowbite.min.css"  rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    /* Estilos para el contenedor */
    .container-centered {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      height: 100vh;
    }
  </style>
</head>
<body>

<nav class="bg-white border-gray-200 dark:bg-gray-900">
  <div class="max-w-screen-xl flex flex-wrap items-flex justify-between mx-auto p-1">
    <?php
    // obtener el id de la cuenta por la url
    $cuenta = $_GET['cuenta'];
    $ID = $_GET['ID'];

    echo "<a href='esquemas_M.php?ID=$ID&cuenta=$cuenta' class='flex items-center'>";
    echo "<img src='imagenes/Fondo_Form.jpg' class='h-8 mr-3' alt='Flowbite Logo' />";
    echo "<span class='self-center text-2xl font-semibold whitespace-nowrap dark:text-white'>Volver</span>";
    echo "</a>";
    ?>
    <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
  </div>
</nav>


<center><h1>Balance General</h1></center>
<br>
<br>


<?php

//incluir el archivo de conexion
include 'funciones.php';

//conexion a la base de datos
$conexion = conectarBD();

//encontramos el nombre Costo de ventas, Ventas y Gastos de operacion de la tabla ${cuenta}_balanza
$sql = "SELECT * FROM ${cuenta}_balanza WHERE NombreEsquema = 'Costo de ventas' OR NombreEsquema = 'Ventas' OR NombreEsquema = 'Gastos de operacion' AND ${cuenta}_ID = $ID";

//ejecucion de la consulta
$resultado = $conexion->query($sql);

//inicializamos las variables para las sumas
$suma_ventas_deudor = 0;
$suma_ventas_acreedor = 0;
$suma_costo_de_ventas_deudor = 0;
$suma_costo_de_ventas_acreedor = 0;
$suma_gastos_de_operacion_deudor = 0;
$suma_gastos_de_operacion_acreedor = 0;

//si el resultado es mayor a 0
if ($resultado->num_rows > 0) {
    //mientras existan filas
    while ($fila = $resultado->fetch_assoc()) {
        // Realizamos las sumas para las filas específicas
        if ($fila['NombreEsquema'] === 'Ventas') {
            $suma_ventas_deudor += $fila['Total_saldo_deudor'];
            $suma_ventas_acreedor += $fila['Total_saldo_acreedor'];
        } elseif ($fila['NombreEsquema'] === 'Costo de ventas') {
            $suma_costo_de_ventas_deudor += $fila['Total_saldo_deudor'];
            $suma_costo_de_ventas_acreedor += $fila['Total_saldo_acreedor'];
        } elseif ($fila['NombreEsquema'] === 'Gastos de operación') {
            $suma_gastos_de_operacion_deudor += $fila['Total_saldo_deudor'];
            $suma_gastos_de_operacion_acreedor += $fila['Total_saldo_acreedor'];
        }
    }
}

//cerrar la conexion
$conexion->close();

//imprimir la tabla con los totales
echo "<div class='container'>";
echo "<div class='table-container'>";
echo "<table class='table table-hover table-dark'>";
echo "<tr>";
echo "<th colspan='2' style='text-align: center;'>Balanza de saldos</th>";
echo "</tr>";
echo "<tr>";
echo "<th style='text-align: center;'>Nombre de las cuentas</th>";
echo "<th style='text-align: center;'>Total saldo deudor</th>";
echo "</tr>";

// Filas para Ventas
echo "<tr>";
echo "<td style='text-align: center;'>Ventas</td>";
echo "<td style='text-align: center;'>$suma_ventas_acreedor</td>";
echo "</tr>";

// Filas para Costo de ventas
echo "<tr>";
echo "<td style='text-align: center;'>Costo de ventas</td>";
echo "<td style='text-align: center;'>$suma_costo_de_ventas_deudor</td>";
echo "</tr>";

// Total General
$total_deudor = $suma_ventas_acreedor - $suma_costo_de_ventas_deudor;
echo "<tr>";
echo "<td style='text-align: center;'><strong></strong></td>";
echo "<td style='text-align: center;'><strong>$total_deudor</strong></td>";
echo "</tr>";

// Filas para Gastos de operacion
echo "<tr>";
echo "<td style='text-align: center;'>Gastos de operacion</td>";
echo "<td style='text-align: center;'>$suma_gastos_de_operacion_deudor</td>";
echo "</tr>";

$total_general = $total_deudor - $suma_gastos_de_operacion_deudor;
// Filas para Gastos de operacion
echo "<tr>";
echo "<td style='text-align: center;'></td>";
echo "<td style='text-align: center;'><strong>$total_general</strong></td>";
echo "</tr>";

echo "</table>";
echo "</div>";
echo "</div>";

?>


  <script src="script.js"></script>
  <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/flowbite.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
