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

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
echo "<title>$title - Eliminar Estado</title>\n";

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
echo "        <tr class=right_main_text><td align=center>Go back to the <a class=admin_headings href='statusadmin.php'>Status Summary</a> page
                to edit statuses.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}

$get_status = $_GET['statusname'];

$query = "select * from ".$db_prefix."punchlist where punchitems = '".$get_status."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {

$punchitem = "".$row['punchitems']."";
$color = "".$row['color']."";
$in_or_out = "".$row['in_or_out']."";
}

if ($in_or_out == '1') {
  $in_or_out_tmp = 'In';
} elseif ($in_or_out == '0') {
  $in_or_out_tmp = 'Out';
} else {
  exit;
}


echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info"> ';
echo '      <div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-exchange"></i> Eliminar Estado</h3>
            </div>
            <div class="box-body">';

echo "         <form name='form' action='$self' method='post'>\n";
echo "            <table align=center class=table width=60% border=0 cellpadding=3 cellspacing=0>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        Nombre del estado:
                      </td>

                      <td align=left width=80% style='padding-left:20px;' class=table_rows>
                        <input type='hidden' name='post_statusname' value=\"$punchitem\">$punchitem
                      </td>
                    </tr>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        Color:
                      </td>

                      <td align=left class=table_rows width=80% style='padding-left:20px;'>
                        <input type='hidden' name='post_color' value=\"$color\">$color
                      </td>
                    </tr>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        Tipo de estado:
                      </td>

                      <td align=left class=table_rows width=80% style='padding-left:20px;'>
                        <input type='hidden' name='post_in_out' value=\"$in_or_out_tmp\">$in_or_out_tmp
                      </td>
                    </tr>\n";
echo "            </table>\n";
echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
echo "              <tr>
                      <td class=table_rows_output height=53 align=left colspan=2 >
                        Eliminando este estado, no se elimina de la base de datos, simplemente se elimina de la lista de estados disponibles.
                    </td>
                  </tr>
                  </table>\n";
echo '            <div class="box-footer">
                    <button type="button" id="formButtons" onclick="location=\'statusadmin.php\'" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                      <i class="fa fa-ban"></i>
                      Cancelar
                    </button>

                    <button id="formButtons" type="submit" name="submit" value="Delete Status" class="btn btn-danger pull-right">
                      <i class="fa fa-trash"></i>
                      Eliminar estado
                    </button>
                  </div>
                </form>';
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

$post_statusname = $_POST['post_statusname'];
$post_color = $_POST['post_color'];
$post_in_out = $_POST['post_in_out'];

if ($post_in_out == 'In') {
  $post_in_out = '1';
} elseif ($post_in_out == 'Out') {
  $post_in_out = '0';
} else {
  exit;
}

$query = "select * from ".$db_prefix."punchlist where punchitems = '".$post_statusname."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {

$punchitem = "".$row['punchitems']."";
$color = "".$row['color']."";
$in_or_out = "".$row['in_or_out']."";
}

if (($post_statusname != $punchitem) || ($post_color != $color) || ($post_in_out != $in_or_out)) {
exit;
}

$query2 = "delete from ".$db_prefix."punchlist where punchitems = ('".$post_statusname."')";
$result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);

if ($post_in_out == '1') {
  $post_in_out = 'In';
} elseif ($post_in_out == '0') {
  $post_in_out = 'Out';
} else {
  exit;
}


echo '       <div id="float_alert" class="col-md-10"><div class="alert alert-success alert-dismissible">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <h4><i class="icon fa fa-check-circle"></i>Estado eliminado!</h4>
                El estado ha sido eliminado de la lista de estados satisfactoriamente.
             </div></div>';

echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info"> ';
echo '      <div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-exchange"></i>  Eliminar Estado</h3>
            </div>
            <div class="box-body">';

echo "            <table align=center class=table width=60% border=0 cellpadding=3 cellspacing=0>\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        Nombre del estado:
                      </td>

                      <td align=left width=80% style='padding-left:20px;' class=table_rows>
                        <input type='hidden' name='post_statusname' value=\"$post_statusname\">$post_statusname
                      </td>
                    </tr>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        Color:
                      </td>

                      <td align=left class=table_rows width=80% style='padding-left:20px;'>
                        <input type='hidden' name='post_color' value=\"$post_color\">$post_color
                      </td>
                    </tr>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        Tipo de estado:
                      </td>

                      <td align=left class=table_rows width=80% style='padding-left:20px;'>
                        <input type='hidden' name='post_in_out' value=\"$post_in_out\">$post_in_out
                      </td>
                    </tr>\n";
echo "            </table>\n";
echo "            <div class='box-footer'>
                    <button type='button' id='formButtons' onclick='location=\"statusadmin.php\"' class='btn btn-success pull-right'>
                      Aceptar
                      <i class='fa fa-check'></i>
                    </button>
                  </div>\n";
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
