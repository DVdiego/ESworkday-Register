<?php
/***************************************************************************
 *   Copyright (C) 2006 by Ken Papizan                                     *
 *   Copyright (C) 2008 by WorkTime Control Team                               *
 *   http://sourceforge.net/projects/WorkTime Control                          *
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

session_start();

include '../config.inc.php';
include 'header_colorpicker.php'; //tiene el script de color pick, el metodo get no lo necesita pero el post si. se podria cambiar y no usarlo.
include 'topmain.php';
echo "<title>$title - Edit Status</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

if (!isset($_SESSION['valid_user'])) {

echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Administration</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=admin'><u>here</u></a> to login.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}
include 'leftmain.php'; //esta despues de verficar la sesión para que no cargue el menú lateral sino esta autendicado.
if ($request == 'GET') {

if (!isset($_GET['statusname'])) {

echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Error!</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table width=300 border=0 cellpadding=5 cellspacing=0>\n";
echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Go back to the <a class=admin_headings href='statusadmin.php'>Office Summary</a> page to edit
            statuses.</td></tr>\n";
echo "      </table><br /></td></tr>
      </table>\n"; exit;
}

$get_status = $_GET['statusname'];

$query = "select * from ".$db_prefix."punchlist where punchitems = '".$get_status."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {

$punchitem = "".$row['punchitems']."";
$color = "".$row['color']."";
$in_or_out = "".$row['in_or_out']."";
}

echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info"> ';
echo '      <div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-exchange "></i> Editar Estado</h3>
            </div>
            <div class="box-body">';
echo "         <form name='form' action='$self' method='post'>\n";
echo "            <table align=center class=table width=60% border=0 cellpadding=3 cellspacing=0>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        &nbsp;*Nombre del estado:
                      </td>

                      <td class='table_rows' width=80% style='padding-left:20px;'>
                        <input type='text' size='20' maxlength='50' name='post_statusname' placeholder=\"$punchitem\">
                      </td>
                    </tr>\n";

echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        &nbsp;*Color:
                      </td>

                      <td class='table_rows' width=80% style='padding-left:20px;'>
                        <input type='color' name='post_color' value=\"$color\">
                      </td>
                    </tr>\n";

echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        ¿Se considera el estado de 'entrada' o 'salida'?</td>\n";

if ($in_or_out == '1') {
echo "                  <td class=table_rows align=left width=80% style='padding-left:20px;'>
                          <input checked type='radio' name='create_status' value='1'>Entrada
                          <input type='radio' name='create_status' value='0'>Salida
                        </td>
                      </tr>\n";
} elseif ($in_or_out == '0') {
echo "                  <td class=table_rows align=left width=80% style='padding-left:20px;'>
                          <input type='radio' name='create_status' value='1'>Entrada
                          <input checked type='radio' name='create_status' value='0'>Salida
                        </td>
                      </tr>\n";
} else {
exit;
}

echo "              <tr>
                      <td class=table_rows align=right colspan=3 style='font-size: 11px;'>*&nbsp;Campos requeridos&nbsp;
                      </td>
                    </tr>\n";
echo "            </table>\n";
//echo "            <script language=\"javascript\">cp.writeDiv()</script>\n";
echo "              <input type='hidden' name='get_status' value='$get_status'>\n";
echo '            <div class="box-footer">

                    <button type="button" id="formButtons" onclick="location=\'statusadmin.php\'" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                      <i class="fa fa-ban"></i>
                      Cancelar
                    </button>

                    <button id="formButtons" type="submit" name="submit" value="Edit Status" class="btn btn-success pull-right">
                      <i class="fa fa-edit"></i>
                      Editar estado
                    </button>
                  </div>';
echo "
                  </form>\n";
echo '      </div>
          </div>
        </div>
      </div>';
include '../theme/templates/endmaincontent.inc';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
include '../footer.php';exit;
}

elseif ($request == 'POST') {

$get_status = $_POST['get_status'];
$post_statusname = $_POST['post_statusname'];
$post_color = $_POST['post_color'];
$create_status = $_POST['create_status'];

// begin post validation //

if (!empty($get_status)) {
$query = "select * from ".$db_prefix."punchlist where punchitems = '".$get_status."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row=mysqli_fetch_array($result)) {
$getstatus = "".$row['punchitems']."";
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
if (!isset($getstatus)) {echo "Status is not defined.\n"; exit;}
}

if (($create_status !== '0') && ($create_status !== '1')) {exit;}

if (get_magic_quotes_gpc()) {$post_statusname = stripslashes($post_statusname);}
$post_statusname = addslashes($post_statusname);

$string = strstr($post_statusname, "\'");
$string2 = strstr($post_statusname, "\"");

// if ((empty($post_statusname)) || (empty($post_color)) || (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_statusname)) || ((!eregi ("^(#[a-fA-F0-9]{6})+$", $post_color)) && (!eregi ("^([a-fA-F0-9]{6})+$", $post_color))) || (!empty($string)) || (!empty($string2))) {

if ((empty($post_statusname)) || (empty($post_color)) || (!preg_match('/' . "^([[:alnum:]]| |-|_|.)+$" . '/i', $post_statusname)) || ((!preg_match('/' . "^(#[a-fA-F0-9]{6})+$" . '/i', $post_color)) && (!preg_match('/' . "^([a-fA-F0-9]{6})+$" . '/i', $post_color))) || (!empty($string)) || (!empty($string2))) {

//

if (empty($post_statusname)) {
  echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                      Se requiere un nombre para el estado.
                    </div>';
}
elseif (empty($post_color)) {
  echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                      Se requiere deleccionar un color.
                    </div>';
}
// elseif (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_statusname)) {
elseif (!preg_match('/' . "^([[:alnum:]]| |-|_|.)+$" . '/i', $post_statusname)) {
  echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                      No están permitidos caracteres alfanuméricos, acentos, apostrofes, comas y espacios para crear un estado.
                    </div>';
}
// elseif ((!eregi ("^(#[a-fA-F0-9]{6})+$", $post_color)) && (!eregi ("^([a-fA-F0-9]{6})+$", $post_color))) {
elseif ((!preg_match('/' . "^(#[a-fA-F0-9]{6})+$" . '/i', $post_color)) && (!preg_match('/' . "^([a-fA-F0-9]{6})+$" . '/i', $post_color))) {
  echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                      El símbolo # seguido de latras de la A a la F o numeros del 0 al 9 están permitidos para seleccionar un color.
                    </div>';
}elseif (!empty($string)) {
  echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                      Los apostrofes no están permitidos en el nombre de estado.
                    </div>';
}elseif (!empty($string2)) {
  echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                      Las dobles comillas no están permitidas en el nombre de estado.
                    </div>';
}

if (!empty($string)) {$post_statusname = stripslashes($post_statusname);}
if (!empty($string2)) {$post_statusname = stripslashes($post_statusname);}

echo "            <br />\n";

echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info"> ';
echo '      <div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-exchange"></i>  Editar Estado </h3>
            </div>
            <div class="box-body">';


echo "        <form name='form' action='$self' method='post'>\n";
echo "            <table align=center class=table width=60% border=0 cellpadding=3 cellspacing=0>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        &nbsp;*Nombre del estado:
                      </td>

                      <td class='table_rows' width=80% style='padding-left:20px;'>
                        <input type='text' size='20' maxlength='50' name='post_statusname' placeholder=\"$post_statusname\">
                      </td>
                    </tr>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        &nbsp;*Color:
                      </td>

                      <td class='table_rows' width=80% style='padding-left:20px;'>
                        <input type='color' size='20' maxlength='7' name='post_color' value=\"$post_color\">
                      </td>
                    </tr>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        ¿Se considera que el estado es de 'entrada' o 'salida'?
                      </td>\n";

if ($create_status == '1') {
echo "                  <td class=table_rows align=left width=80% style='padding-left:20px;'>
                          <input checked type='radio' name='create_status' value='1'>Entrada
                          <input type='radio' name='create_status' value='0'>Salida
                        </td>
                      </tr>\n";
} elseif ($create_status == '0') {
echo "                  <td class=table_rows align=left width=80% style='padding-left:20px;'>
                          <input type='radio' name='create_status' value='1'>Entrada
                          <input checked type='radio' name='create_status' value='0'>Salida
                      </td>
                      </tr>\n";
} else {
exit;
}

if (!empty($string)) {$post_statusname = stripslashes($post_statusname);}
if (!empty($string2)) {$post_statusname = stripslashes($post_statusname);}

echo "              <tr>
                      <td class=table_rows align=right colspan=3 style='font-size: 11px;'>*&nbsp;Campos requeridos&nbsp;
                      </td>
                    </tr>\n";
echo "            </table>\n";
echo "            <script language=\"javascript\">cp.writeDiv()</script>\n";
echo "              <input type='hidden' name='get_status' value='$get_status'>\n";
echo '            <div class="box-footer">

                    <button type="button" id="formButtons" onclick="location=\'statusadmin.php\'" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                      <i class="fa fa-ban"></i>
                      Cancelar
                    </button>

                    <button id="formButtons" type="submit" name="submit" value="Edit Status" class="btn btn-success pull-right">
                      <i class="fa fa-edit"></i>
                      Editar estado
                    </button>
                  </div>';
echo "         </form>\n";
echo '      </div>
          </div>
        </div>
      </div>';
include '../theme/templates/endmaincontent.inc';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
include '../footer.php';exit;
} else {

$query = "update ".$db_prefix."punchlist set punchitems = ('".$post_statusname."'), color = ('".$post_color."'), in_or_out = ('".$create_status."')
          where punchitems  = ('".$get_status."')";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

$query2 = "update ".$db_prefix."info set `inout` = ('".$post_statusname."') where `inout` = ('".$get_status."')";
$result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);

echo '       <div id="float_alert" class="col-md-10"><div class="alert alert-success alert-dismissible">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <h4><i class="icon fa fa-check-circle"></i>Estado editado!</h4>
                El estado ha sido editado satisfactoriamente.
             </div></div>';

echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info"> ';
echo '      <div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-exchange"></i> Editar Estado </h3>
            </div>
            <div class="box-body">';
echo "            <table align=center class=table width=60% border=0 cellpadding=3 cellspacing=0>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        Nombre del estado:
                      </td>

                      <td align=left class='table_rows' width=80% style='padding-left:20px;'>$post_statusname
                      </td>
                    </tr>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        Color:
                      </td>

                      <td align=left class='table_rows' width=80% style='padding-left:20px;'>$post_color
                      </td>
                    </tr>\n";

if ($create_status == '1') {
  $create_status_tmp = 'In';
  } else {
  $create_status_tmp = 'Out';
}

echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        Tipo de estado:
                      </td>

                      <td align=left class='table_rows' width=80% style='padding-left:20px;'>$create_status_tmp
                      </td>
                    </tr>\n";
echo "            </table>\n";
echo "            <div class='box-footer'>
                    <button id='formButtons' onclick='location=\"statusadmin.php\"' class='btn btn-success pull-right'>
                      Aceptar
                      <i class='fa fa-check'></i>
                    </button>
                  </div>\n";
}
echo '      </div>
          </div>
        </div>
      </div>';
include '../theme/templates/endmaincontent.inc';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
include '../footer.php';exit;
}
?>
