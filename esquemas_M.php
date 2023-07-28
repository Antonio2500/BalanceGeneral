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
    <a href="cuentas.php" class="flex items-center">
        <img src="https://flowbite.com/docs/images/logo.svg" class="h-8 mr-3" alt="Flowbite Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Volver</span>
    </a>
    <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
  </div>
</nav>

<center><h1>Agregar esquema de mayor</h1></center>

  <center>
    <button type="button" class="btn btn-success editbtn" data-bs-toggle="modal" data-bs-target="#btnAbrirModal">Agregar</button>
  </center>




  <!-- Modal -->
<div class="modal fade" id="btnAbrirModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Esquema nuevo</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
    <div class="modal-body">

    <form action="controlador.php" method="POST">
    <?php
    // obtener el id de la cuenta por la url
    $cuenta = $_GET['cuenta'];
    echo "<input type='hidden' name='cuenta' value='$cuenta'>";
    // obtener el id de la cuenta por la url
    $ID = $_GET['ID'];
    echo "<input type='hidden' name='ID' value='$ID'>";
    ?>

    <label style="font-family: calibri;font-size: 13px;"> <strong>Nombre del esquema</strong></label><br>
    <input required type="text" name="nombre_esquema" placeholder="Nombre del esquema">
    <br>
    <br>

    
    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">¿Qué tipo de cuenta sera?</label>
    <select required id="countries" name="tipo_cuenta" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    <option selected>Selecione una opcion</option>
    <option value="Activo">Activo</option>
    <option value="Pasivo">Pasivo</option>
    <option value="Venta">Venta</option>
    <option value="Costo">Costo</option>
    <option value="Gasto">Gasto</option>
    </select>
<br>


        <span class="close">&times;</span>
        <label for="numero">¿Cuántos montos se realizaran?</label><br>
        <input type="text" id="numero" name="numero" min="1" required>
        <button class="btn btn-success editbtn" id="btnGenerar">Generar</button><br><br>
        <div id="camposContainer"></div><br>
        <button class="btn btn-success editbtn" name="GuardarEsquema" id="btnEnviar" style="display: none;">Crear</button><br>
        </form>
    </div>
    </div>
  </div>
</div>
</div>
<br>




    <?php
    // Incluimos el archivo funciones.php
    include 'funciones.php';
    

    // Llamamos a la función para mostrar los esquemas
    consultarEsquemas();
    ?>






<script>
        // Obtener el modal
        var modal = document.getElementById("modal");

        // Obtener el botón para abrir el modal
        var btnAbrirModal = document.getElementById("btnAbrirModal");

        // Obtener el elemento <span> que cierra el modal
        var spanCerrar = document.getElementsByClassName("close")[0];

        // Cuando se haga clic en el botón, abrir el modal
        btnAbrirModal.onclick = function() {
            modal.style.display = "block";
        };

        // Cuando se haga clic en <span> (x), cerrar el modal
        spanCerrar.onclick = function() {
            modal.style.display = "none";
        };

        // Cuando se haga clic en el botón "Generar"
        document.getElementById("btnGenerar").onclick = function() {
            var cantidad = document.getElementById("numero").value;

            var camposContainer = document.getElementById("camposContainer");
            camposContainer.innerHTML = ""; // Limpiar los campos anteriores

            for (var i = 1; i <= cantidad; i++) {
                var label = document.createElement("label");
                label.textContent = "Campo " + i + ":";
                camposContainer.appendChild(label);

                var input = document.createElement("input");
                input.type = "text";
                input.name = "campo" + i;
                input.required = true;
                camposContainer.appendChild(input);

                camposContainer.appendChild(document.createElement("br"));
            }

            // Mostrar el botón "Enviar"
            document.getElementById("btnEnviar").style.display = "block";
        };

        // Cuando se haga clic en el botón "Enviar"
        document.getElementById("btnEnviar").onclick = function() {
            // Obtener los valores de los campos generados
            var campos = document.querySelectorAll("#camposContainer input");
            var datos = [];
            campos.forEach(function(campo) {
                datos.push(campo.value);
            });

            // Hacer algo con los datos
            console.log(datos);

            // Cerrar el modal
            modal.style.display = "none";
        };
    </script>
  <script src="script.js"></script>
  <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/flowbite.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
