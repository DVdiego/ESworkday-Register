<?php
/***************************************************************************
 *   Copyright (C) 2006 by Ken Papizan                                     *
 *   Copyright (C) 2008 by phpTimeClock Team                               *
 *   http://sourceforge.net/projects/phptimeclock                          *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA.             *
 ***************************************************************************/

/**
 * This module creates the regular header for the interface.
 */

include 'functions.php';

ob_start();
echo "<html>";

// grab the connecting IP address. //
$connecting_ip = get_ipaddress();
if (empty($connecting_ip)) {
    return FALSE;
}

// determine if connecting IP address is allowed to connect to PHP Timeclock //
if ($restrict_ips == "yes") {
    for ($x = 0; $x < count($allowed_networks); $x++) {
        $is_allowed = ip_range($allowed_networks[$x], $connecting_ip);
        if (! empty($is_allowed)) {
            $allowed = TRUE;
        }
    }
    if (! isset($allowed)) {
        echo "You are not authorized to view this page.";
        exit;
    }
}

// connect to db anc check for correct db version //
if ($use_persistent_connection == "yes") {
    @ $db = ($GLOBALS["___mysqli_ston"] = mysqli_connect($db_hostname,  $db_username,  $db_password));
} else {
    @ $db = ($GLOBALS["___mysqli_ston"] = mysqli_connect($db_hostname,  $db_username,  $db_password));
}
if (! $db) {
    echo "Error: Could not connect to the database. Please try again later.";
    exit;
}
((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE " . $db_name));

$table = "dbversion";
$result = mysqli_query($GLOBALS["___mysqli_ston"], "SHOW TABLES LIKE '".$db_prefix.$table."'");
@$rows = mysqli_num_rows($result);
if ($rows == "1") {
    $dbexists = "1";
} else {
    $dbexists = "0";
}

$db_version_result = mysqli_query($GLOBALS["___mysqli_ston"], "select * from ".$db_prefix."dbversion");
while (@$row = mysqli_fetch_array($db_version_result)) {
    @$my_dbversion = "".$row["dbversion"]."";
}

// include css and timezone offset//
if (($use_client_tz == "yes") && ($use_server_tz == "yes")) {
    $use_client_tz = '$use_client_tz';
    $use_server_tz = '$use_server_tz';
    echo "Please reconfigure your config.inc.php file, you cannot have both $use_client_tz AND $use_server_tz set to 'yes'";
    exit;
}

echo "
   <head>";
if ($use_client_tz == "yes") {
    if (! isset($_COOKIE['tzoffset'])) {
        include 'tzoffset.php';
        echo "
      <meta http-equiv='refresh' content='0;URL=timeclock.php'>";
    }
}

echo "
	<title>$title</title>";
      include 'theme/templates/header.inc';
echo "
      <link rel='stylesheet' type='text/css' media='print' href='css/print.css' />";
// set refresh rate for each page //
if ($refresh == "none") {
    echo '
   </head>';
} else {
    echo "
      <meta http-equiv='refresh' content=\"$refresh;URL=timeclock.php\">
      <script language=\"javascript\" src=\"scripts/pnguin_timeclock.js\">
      </script>



   </head>";
}

global $use_client_tz;
global $use_server_tz;

// Set timezone information
if ($use_client_tz == "yes") {
    if (isset($_COOKIE['tzoffset'])) {
        $tzo = $_COOKIE['tzoffset'];
        settype($tzo, "integer");
        $tzo = $tzo * 60;
    }
} elseif ($use_server_tz == "yes") {
    $tzo = date('Z');
} else {
    $tzo = "1";
}
?>
<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=true'></script>
<script type="text/javascript">// <![CDATA[
if (navigator.geolocation) {
  var tiempo_de_espera = 3000;
  navigator.geolocation.getCurrentPosition(mostrarCoordenadas, mostrarError, { enableHighAccuracy: true, timeout: tiempo_de_espera, maximumAge: 0 } );
}
else {
  alert("La Geolocalización no es soportada por este navegador");
}

function mostrarCoordenadas(position) {
  var lat = position.coords.latitude;
  var lon = position.coords.longitude;

  //var lat = "40.9369015";
  //var lon = "-4.1117624";
  //alert("google");
  //alert("Latitud: " + lat + ", Longitud: " + lon);
  //mostrarDireccion(position.coords.latitude,position.coords.longitude);
  var dir = "";
	var latlng = new google.maps.LatLng(lat, lon);
  //var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
	geocoder = new google.maps.Geocoder();
  alert("google:"+latlng);
	geocoder.geocode({'location': latlng}, function(results, status)
	{
		if (status === 'OK')
		{
			if (results[0])
			{
				dir = "<p><strong>Dirección: </strong>" + results[0].formatted_address + "</p>";
        alert("Dirección:"+results[0].formatted_address);
			}
			else
			{
				dir = "<p>No se ha podido obtener ninguna dirección en esas coordenadas.</p>";
        alert("ninguna dirección");
			}
		}
		else
		{
			dir = "<p>El Servicio de Codificación Geográfica ha fallado con el siguiente error: " + status + ".</p>";
      alert("error"+status);
		}

		//content.innerHTML = "<p><strong>Latitud:</strong> " + lat + "</p><p><strong>Longitud:</strong> " + lon + "</p>" + dir;
	});
}

function mostrarDireccion(lat, lon){

  var dir = "";
			var latlng = new google.maps.LatLng(lat, lon);
			geocoder = new google.maps.Geocoder();
			geocoder.geocode({"latLng": latlng}, function(results, status)
			{
				if (status == google.maps.GeocoderStatus.OK)
				{
					if (results[0])
					{
						dir = "<p><strong>Dirección: </strong>" + results[0].formatted_address + "</p>";
            alert("Dirección:"+results[0].formatted_address);
					}
					else
					{
						dir = "<p>No se ha podido obtener ninguna dirección en esas coordenadas.</p>";
            alert("ninguna dirección");
					}
				}
				else
				{
					dir = "<p>El Servicio de Codificación Geográfica ha fallado con el siguiente error: " + status + ".</p>";
          alert("error");
				}

				content.innerHTML = "<p><strong>Latitud:</strong> " + lat + "</p><p><strong>Longitud:</strong> " + lon + "</p>" + dir;
			});


}

function mostrarError(error) {
  var errores = {1: 'Permiso denegado', 2: 'Posición no disponible', 3: 'Expiró el tiempo de respuesta'};
  alert("Error: " + errores[error.code]);
}
// ]]></script>
