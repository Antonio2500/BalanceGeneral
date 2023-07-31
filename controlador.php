<?php

require 'funciones.php';

//if isset del boton guardar_cuenta con mensaje de exito y fracaso
if(isset($_POST['Guardar_Cuenta'])){
  $cuenta = $_POST['cuenta'];
  guardarCuenta($cuenta);
  echo '<script type="text/javascript">
  alert("Cuenta agregada con exito");
  window.location.href="cuentas.php";
  </script>';
}

//if isset del boton GuardarEsquema con mensaje de exito y fracaso
if(isset($_POST['GuardarEsquema'])){
  $nombre_esquema = $_POST['nombre_esquema'];
  $tipo_cuenta = $_POST['tipo_cuenta'];
  $numero = $_POST['numero'];
  $cuenta = $_POST['cuenta'];
  $ID = $_POST['ID'];
  
  guardarEsquema($nombre_esquema, $tipo_cuenta, $numero, $cuenta);
  guardarMontos($numero, $cuenta, $nombre_esquema, $ID);
  


  //redireccion a la pagina de esquemas con el id y el nombre de la cuenta
  echo '<script type="text/javascript">
  alert("Esquema agregado con exito");
  window.location.href="esquemas_M.php?ID='.$ID.'&cuenta='.$cuenta.'";
  </script>';
}










?>