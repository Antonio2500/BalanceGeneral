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
    $createTablaRelacionada = "CREATE TABLE ${cuenta}_relacionada (
      ID INT PRIMARY KEY AUTO_INCREMENT,
      Monto_deudor VARCHAR(50),
      Monto_acreedor VARCHAR(50),
      NombreEsquema VARCHAR(50),
      ${cuenta}_ID INT,
      FOREIGN KEY (${cuenta}_ID) REFERENCES $cuenta(ID)
    )";
    mysqli_query($conexion, $createTablaRelacionada);
  
    mysqli_close($conexion);
  }
  
    //funcion para consultar las cuentas
    function consultarCuentas() {
      $conexion = conectarBD();
      $consulta = "SELECT * FROM cuentas";
      $tabla = mysqli_query($conexion, $consulta);
      mysqli_close($conexion);
      return $tabla;
    }


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

//funcion para guardar los montos en la tabla relacionada
function guardarMontos($numero, $cuenta, $nombre_esquema, $ID) {
  $conexion = conectarBD();

  try {
      // Iniciamos la transacción
      $conexion->begin_transaction();

      // Preparamos la consulta SQL para insertar los datos y el ID y el nombre del esquema
      $insertMontos = "INSERT INTO ${cuenta}_relacionada (Monto_deudor, Monto_acreedor, ${cuenta}_ID, NombreEsquema) VALUES (?, ?, ?, ?)";
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




//funcion para consultar los esquemas
function consultarEsquemas() {
  $conexion = conectarBD();
  // Obtenemos el nombre de la cuenta
  $cuenta = $_GET['cuenta'];

  // se obtienen los datos de la tabla cuentas
  $sql_cuentas = "SELECT * FROM cuentas WHERE cuenta = '$cuenta'";
  $resultado = $conexion->query($sql_cuentas);
  $name = $_GET['cuenta'];

  // Obtenemos los datos de la tabla prueba_relacionada
  $sql_relacional = "SELECT * FROM ${name}_relacionada";
  $resultado_relacional = $conexion->query($sql_relacional);

  // Creamos un arreglo para agrupar los datos de los esquemas
  $esquemas = array();

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

  // Mostramos los datos agrupados en una sola tabla por cada esquema
  foreach ($esquemas as $nombre_esquema => $datos) {
      echo "<table class='table table-striped table-dark'><thead>";
      echo "<tr>";
      echo "<th colspan='2' scope='col' style='text-align: center;'>$nombre_esquema</th>";
      echo "</tr><tr>";
      echo "<th scope='col' style='text-align: center;'>Monto Deudor</th>";
      echo "<th scope='col' style='text-align: center;'>Monto Acreedor</th>";
      echo "</tr></thead>";
      echo "<tbody>";

      // Mostramos los montos de cada esquema en filas separadas
      $num_filas = count($datos['Monto_deudor']);
      for ($i = 0; $i < $num_filas; $i++) {
          echo "<tr>";
          echo "<td style='text-align: center;'>" . $datos['Monto_deudor'][$i] . "</td>";
          echo "<td style='text-align: center;'>" . $datos['Monto_acreedor'][$i] . "</td>";
          echo "</tr>";
      }

      echo "</tbody></table>";
  }

  // Cerramos la conexión
  $conexion->close();
}






?>