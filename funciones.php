<?php

function conectarBD(){

  $servidor="127.0.0.1:3306";
  $baseDatos="negocios_v";
  $usuario="root";
  $contrasena="";


  //Ejecutar la conexion

  $conexion = mysqli_connect($servidor, $usuario, $contrasena, $baseDatos)or die("Error de conexion");

  //respuesta de la funtion
  return $conexion;

}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//funcion para agregar nueva cuenta a la tabla cuentas
function guardarCuenta($cuenta) {
    $conexion = conectarBD();
  
    // Crear la tabla principal
    $createTablaPrincipal = "CREATE TABLE $cuenta (
      ID INT PRIMARY KEY AUTO_INCREMENT,
      NombreEsquema VARCHAR(50),
      TipoCuenta VARCHAR(50),
      Montos INT,
      Cuenta VARCHAR(50)
    )";
    mysqli_query($conexion, $createTablaPrincipal);
  
    // Insertar en la tabla principal
    $insertC = "INSERT INTO cuentas(cuenta) VALUES (?)";
    $stmt = mysqli_prepare($conexion, $insertC);
    mysqli_stmt_bind_param($stmt, "s", $cuenta);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  
    // Crear la tabla relacionada con la llave foránea
    $createTablaRelacionada = "CREATE TABLE ${cuenta}_montos (
      ID INT PRIMARY KEY AUTO_INCREMENT,
      Monto_deudor VARCHAR(50),
      Monto_acreedor VARCHAR(50),
      NombreEsquema VARCHAR(50),
      ${cuenta}_ID INT,
      FOREIGN KEY (${cuenta}_ID) REFERENCES $cuenta(ID)
    )";
    mysqli_query($conexion, $createTablaRelacionada);

    //crear otra tabla relacionada con la llave foranea
    $createTablaRelacionada2 = "CREATE TABLE ${cuenta}_balanza (
      ID INT PRIMARY KEY AUTO_INCREMENT,
      Total_deudor VARCHAR(50),
      Total_acreedor VARCHAR(50),
      Total_saldo_deudor VARCHAR(50),
      Total_saldo_acreedor VARCHAR(50),
      NombreEsquema VARCHAR(50),
      ${cuenta}_ID INT,
      FOREIGN KEY (${cuenta}_ID) REFERENCES $cuenta(ID)
    )";
    mysqli_query($conexion, $createTablaRelacionada2);
  
    mysqli_close($conexion);
  }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  
    //funcion para consultar las cuentas
    function consultarCuentas() {
      $conexion = conectarBD();
      $consulta = "SELECT * FROM cuentas";
      $tabla = mysqli_query($conexion, $consulta);
      mysqli_close($conexion);
      return $tabla;
    }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    //funcion para agregar nuevo esquema a la tabla esquemas
function guardarEsquema($nombre_esquema, $tipo_cuenta, $numero, $cuenta) {
  $conexion = conectarBD();
  // Insertar en la tabla esquemas
  $insertE = "INSERT INTO $cuenta (NombreEsquema, TipoCuenta, Montos, Cuenta) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($conexion, $insertE);
  mysqli_stmt_bind_param($stmt, "ssis", $nombre_esquema, $tipo_cuenta, $numero, $cuenta);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  mysqli_close($conexion);
}




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




//funcion para guardar los montos en la tabla relacionada
function guardarMontos($numero, $cuenta, $nombre_esquema, $ID) {
  $conexion = conectarBD();

  try {
      // Iniciamos la transacción
      $conexion->begin_transaction();

      // Preparamos la consulta SQL para insertar los datos y el ID y el nombre del esquema
      $insertMontos = "INSERT INTO ${cuenta}_montos (Monto_deudor, Monto_acreedor, ${cuenta}_ID, NombreEsquema) VALUES (?, ?, ?, ?)";
      $stmt = mysqli_prepare($conexion, $insertMontos);

      // Iteramos sobre los datos enviados por el formulario y los guardamos en la base de datos
      for ($i = 1; $i <= $numero; $i += 2) {
          $campo1 = $_POST["campo" . $i];
          $campo2 = $_POST["campo" . ($i + 1)];

          // Escapamos los datos para prevenir inyecciones SQL
          $campo1 = $conexion->real_escape_string($campo1);
          $campo2 = $conexion->real_escape_string($campo2);

          // Asignamos los parámetros y ejecutamos la consulta para cada par de campos
          mysqli_stmt_bind_param($stmt, "ssss", $campo1, $campo2, $ID, $nombre_esquema);
          mysqli_stmt_execute($stmt);
      }

      // Terminamos la transacción (confirmamos los cambios)
      $conexion->commit();

      // Cerramos el statement
      mysqli_stmt_close($stmt);
  } catch (Exception $e) {
      // Si ocurrió algún error, revertimos la transacción
      $conexion->rollback();
      echo "Error al guardar los montos: " . $e->getMessage();
  }

  // Cerramos la conexión
  $conexion->close();
}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




//funcion para consultar los esquemas
  function consultarEsquemas() {
    $conexion = conectarBD();
    // Obtenemos el nombre de la cuenta
    $cuenta = $_GET['cuenta'];
    $ID = $_GET['ID'];

    // se obtienen los datos de la tabla cuentas
    $sql_cuentas = "SELECT * FROM cuentas WHERE cuenta = '$cuenta'";
    $resultado = $conexion->query($sql_cuentas);
    $name = $_GET['cuenta'];

    // Obtenemos los datos de la tabla prueba_montos
    $sql_relacional = "SELECT * FROM ${name}_montos";
    $resultado_relacional = $conexion->query($sql_relacional);

    // Creamos un arreglo para agrupar los datos de los esquemas
    $esquemas = array();

    // Contador para el número de tablas generadas
    $tabla_contador = 0;

    // Recorremos los resultados y agrupamos los datos por el valor de 'NombreEsquema'
    while ($fila = $resultado_relacional->fetch_assoc()) {
        $nombre_esquema = $fila['NombreEsquema'];

        // Verificamos si el nombre del esquema ya existe en el arreglo
        if (array_key_exists($nombre_esquema, $esquemas)) {
            // Si ya existe, agregamos el monto a la entrada correspondiente
            $esquemas[$nombre_esquema]['Monto_deudor'][] = $fila['Monto_deudor'];
            $esquemas[$nombre_esquema]['Monto_acreedor'][] = $fila['Monto_acreedor'];
        } else {
            // Si no existe, creamos una nueva entrada en el arreglo
            $esquemas[$nombre_esquema] = array(
                'Monto_deudor' => array($fila['Monto_deudor']),
                'Monto_acreedor' => array($fila['Monto_acreedor'])
            );
        }
    }

  // Iniciamos el contenedor para las tablas
  echo "<div style='display: flex; flex-wrap: wrap; justify-content: center;'>";


  // Mostramos los datos agrupados en una sola tabla por cada esquema
  foreach ($esquemas as $nombre_esquema => $datos) {
      // Código para generar la tabla
      echo "<div style='margin: 10px;'>";
      echo "<table class='table table-striped table-dark'><thead>";
      echo "<tr>";
      echo "<th colspan='2' scope='col' style='text-align: center;'>$nombre_esquema</th>";
      echo "</tr><tr>";
      echo "<th scope='col' style='text-align: center;'>Monto Deudor</th>";
      echo "<th scope='col' style='text-align: center;'>Monto Acreedor</th>";
      echo "</tr></thead>";
      echo "<tbody>";

      // Resto del código para mostrar los montos en filas
      $num_filas = count($datos['Monto_deudor']);
      for ($i = 0; $i < $num_filas; $i++) {
          echo "<tr>";
          echo "<td style='text-align: center;'>" . $datos['Monto_deudor'][$i] . "</td>";
          echo "<td style='text-align: center;'>" . $datos['Monto_acreedor'][$i] . "</td>";
          echo "</tr>";
      }

      // Mostramos la fila con las sumas
      $suma_deudor = array_sum($datos['Monto_deudor']);
      $suma_acreedor = array_sum($datos['Monto_acreedor']);
      $resta_deudor_acreedor = $suma_deudor - $suma_acreedor;

      echo "<tr>";
      echo "<td style='text-align: center;'><strong>Total: $suma_deudor</strong></td>";
      echo "<td style='text-align: center;'><strong>Total: $suma_acreedor</strong></td>";
      echo "</tr><tr>";
      echo "<td colspan='2' style='text-align: center;'><strong>Total: $resta_deudor_acreedor</strong></td>";
      echo "</tr>";

      echo "</tbody></table>";
      echo "</div>";


      // Insertar los totales en la tabla de balanza si el registro no existe
    $sql_check_existence = "SELECT COUNT(*) AS count FROM ${cuenta}_balanza WHERE NombreEsquema = '$nombre_esquema' AND ${cuenta}_ID = $ID";
    $resultado_check = $conexion->query($sql_check_existence);
    $fila_existence = $resultado_check->fetch_assoc();
    $registro_existe = $fila_existence['count'] > 0;

    if (!$registro_existe) {
        $sql_insert_balanza = "INSERT INTO ${cuenta}_balanza (Total_deudor, Total_acreedor, Total_saldo_deudor, Total_saldo_acreedor, NombreEsquema, ${cuenta}_ID) 
        VALUES ($suma_deudor, $suma_acreedor, " . max(0, $resta_deudor_acreedor) . ", " . max(0, -$resta_deudor_acreedor) . ", '$nombre_esquema', $ID)";
        $conexion->query($sql_insert_balanza);
    }


      // Insertar un salto de línea (nueva línea) después de cada grupo de 3 tablas
      if ($tabla_contador % 3 === 0) {
          echo "<br>";
      }
  }

  // Cerramos el contenedor de tablas
  echo "</div>";


  // Cerramos la conexión
  $conexion->close();
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




?>