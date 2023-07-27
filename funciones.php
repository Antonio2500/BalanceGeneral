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

  //obtener la cuenta
  $consulta = "SELECT * FROM cuentas WHERE cuenta = '$cuenta'";
  $resultado = mysqli_query($conexion, $consulta);
  $fila = mysqli_fetch_assoc($resultado);
  $tabla = $fila['cuenta'];

  // Insertar en la tabla esquemas
  $insertE = "INSERT INTO $tabla(NombreEsquema, TipoCuenta, Montos, cuenta) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($conexion, $insertE);
  mysqli_stmt_bind_param($stmt, "ssis", $nombre_esquema, $tipo_cuenta, $numero, $cuenta);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  mysqli_close($conexion);
}

//funcion para guardar los montos en la tabla relacionada
function guardarMontos($numero, $cuenta, $ID) {
  $conexion = conectarBD();

      // Iteramos sobre los datos enviados por el formulario y los guardamos en la base de datos
      for ($i = 1; $i <= $numero; $i++) {
        $campo = $_POST["campo" . $i];
        $columna = ($i % 2 === 0) ? "Monto_deudor" : "Monto_acreedor";

        // Escapamos los datos para prevenir inyecciones SQL
        $campo = $conexion->real_escape_string($campo);
        $columna = $conexion->real_escape_string($columna);

        // Creamos la consulta SQL para insertar los datos y el ID
        $insertMontos = "INSERT INTO ${cuenta}_relacionada ($columna, ${cuenta}_ID) VALUES ('$campo', '$ID')";
        $stmt = mysqli_prepare($conexion, $insertMontos);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);



    }
    

}






?>