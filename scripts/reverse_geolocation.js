

if (navigator.geolocation) {
  var tiempo_de_espera = 3000;
  navigator.geolocation.getCurrentPosition(mostrarDireccion, mostrarError, { enableHighAccuracy: true, timeout: tiempo_de_espera, maximumAge: 0 } );
}
else {
  alert("La Geolocalización no es soportada por este navegador");
}


function mostrarDireccion(position){
  var lat = position.coords.latitude;
  var lon = position.coords.longitude;
  alert(lat,lon);
  get_address(lat, lon);
}

function get_address(lat, lon){
  var request = new XMLHttpRequest();
  request.onreadystatechange = function() {
    var address = request.response;
    var inf = JSON.parse(address);
    alert(inf.display_name);
  }

  var requestURL = 'https://nominatim.openstreetmap.org/reverse?json_callback&format=json&addressdetails=0&zoom=18&lat='+lat+'&lon='+lon;
  request.open('GET', requestURL,  /* async = */ false);
  request.send();
}



function mostrarError(error) {
  var errores = {1: 'Permiso denegado', 2: 'Posición no disponible', 3: 'Expiró el tiempo de respuesta'};
  alert("Error: " + errores[error.code]);
}
