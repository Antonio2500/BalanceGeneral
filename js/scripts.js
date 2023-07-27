function mostrarDialogo() {
  var dialogo = new bootstrap.Modal(document.getElementById('dialogo'));
  dialogo.show();
}

function agregarRegistros() {
  var numRegistros = document.getElementById('inputRegistros').value;
  if (numRegistros.trim() === '') {
    alert('Por favor, ingrese un número válido de registros.');
  } else {
    alert('Se agregarán ' + numRegistros + ' registros.');
  }
}
