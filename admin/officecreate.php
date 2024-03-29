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
include 'header.php';
include 'topmain.php';

echo "<title>$title - Create Office</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

if (!isset($_SESSION['valid_user'])) {

  echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
  echo "  <tr class=right_main_text>
            <td height=10 align=center valign=top scope=row class=title_underline>
              WorkTime Control Administration
            </td>
          </tr>\n";
  echo "  <tr class=right_main_text>\n";
  echo "    <td align=center valign=top scope=row>\n";
  echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
  echo "        <tr class=right_main_text>
                  <td align=center>
                  You are not presently logged in, or do not have permission to view this page.
                  </td>
                </tr>\n";

  echo "        <tr class=right_main_text>
                  <td align=center>
                    Click <a class=admin_headings href='../login.php?login_action=admin'>
                      <u>here</u>
                      </a> to login.
                  </td>
                  </tr>\n";
  echo "      </table><br />
            </td>
          </tr>
        </table>\n"; exit;
}
include 'leftmain.php'; //esta despues de verficar la sesión para que no cargue el menú lateral sino esta autendicado.
if ($request == 'GET') {
  echo '<div class="row">
          <div id="float_window" class="col-md-10">
            <div class="box box-info"> ';
  echo '      <div class="box-header with-border">
                   <h3 class="box-title"><i class="fa fa-suitcase"></i> Crear oficina</h3>
              </div>
              <div class="box-body">';
echo "          <form name='form' action='$self' method='post'>\n";
echo "            <table align=center class=table_hover>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                        Nombre de la oficina:&nbsp;*
                      </td>

                      <td class=table_rows width=80% style='padding-left:20px;'>
                        <input type='text' size='25' maxlength='50' name='post_officename' required>
                      </td>
                    </tr>\n";

echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                        ¿Desea crear grupos para la oficina?
                      </td>\n";
echo "
                      <td class=table_rows align=left width=80% style='padding-left:20px;'>
                        <input type='radio' name='create_groups' value='1' onFocus=\"javascript:form.how_many.disabled=false;form.how_many.style.background='#ffffff';\">Si
                        <input checked type='radio' name='create_groups' value='0' onFocus=\"javascript:form.how_many.disabled=true;form.how_many.style.background='#eeeeee';\">No
                      </td>
                    </tr>\n";

echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                        ¿Cuántos?
                      </td>

                      <td class=table_rows width=80% style='padding-left:20px;'>
                        <input disabled type='text' size='2' maxlength='1' name='how_many' style='background:#eeeeee;'>
                      </td>
                    </tr>\n";
echo "              <tr>
											<td class=table_rows align=right colspan=3 style='font-size: 11px;'>*&nbsp;Campos requeridos&nbsp;
											</td>
										</tr>\n";
echo "            </table>\n";
echo "            <div class='box-footer'>
                    <button type='button' id='formButtons' onclick='location=\"officeadmin.php\"' class='btn btn-default pull-right' style='margin: 0px 10px 0px 10px;'>
                      <i class='fa fa-ban'></i>
                      Cancelar
                    </button>

                    <button id='formButtons' type='submit' name='submit' value='Create Office' class='btn btn-success pull-right'>
                      <i class='fa fa-plus'></i>
                      Crear oficina
                    </button>
                  </div>
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

  $post_officename = $_POST['post_officename'];
  $create_groups = $_POST['create_groups'];
  @$how_many = $_POST['how_many'];
  @$input_group_name = $_POST['input_group_name'];



  if (get_magic_quotes_gpc()) {$post_officename = stripslashes($post_officename);}
  $post_officename = addslashes($post_officename);

  // begin post validation //

  // check for duplicate officenames //

  $query = "select * from ".$db_prefix."offices where officename = '".$post_officename."'";
  $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

  while ($row=mysqli_fetch_array($result)) {
    $tmp_officename = "".$row['officename']."";
  }

  // error checking: check for duplicate names, disallow certain characters for some fields, etc... //

  $string = strstr($post_officename, "\'");
  $string2 = strstr($post_officename, "\"");

  // if ((@$tmp_officename == $post_officename) || (empty($post_officename)) || (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_officename)) || ((!eregi ("^([0-9])$", @$how_many)) && (isset($how_many))) || (@$how_many == '0') || (($create_groups != '1') && (!empty($create_groups))) || (!empty($string)) || (!empty($string2))) {


  if ((@$tmp_officename == $post_officename) || (empty($post_officename)) || (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_officename)) || ((!preg_match('/' . "^([0-9])$" . '/i', $how_many)) && (isset($how_many))) || (@$how_many == '0') || (($create_groups != '1') && (!empty($create_groups))) || (!empty($string)) || (!empty($string2))) {
    if (empty($post_officename)) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                     Se requiere un nombre de oficina.
                  </div></div>';

    }
    elseif (!empty($string)) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                     No están permitidos los apostrofes en el nombre.
                  </div></div>';
    }
    elseif (!empty($string2)) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                     No se permiten las dobles comillas en el nombre.
                  </div></div>';
    }
    elseif (@$tmp_officename == $post_officename) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                     La oficina ya existe, introduzca otro nombre.
                  </div></div>';
    }
    // elseif (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_officename)) {
    elseif (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_officename)) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                    No se permiten guiones, guines bajos, espacios o caracteres alfanuméricos en el nombre.
                </div></div>';
    }
    elseif (($create_groups == '1') && (empty($how_many))) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                     Por favor introduzca el número de grupos para la oficina ' . $post_officename .'.
                  </div></div>';
    }
    elseif (($create_groups == '1') && ($how_many == '0')) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                     Ha decidido crear grupos, por favor introduzc un número diferente de 0.
                  </div></div>';
    }
    // elseif (!eregi ("^([0-9])$", $how_many)) {
    elseif (!preg_match('/' . "^([0-9])$" . '/i', $how_many)) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                     Solo se permiten caracteres numéricos en el campo de grupos.
                  </div></div>';
    }elseif (($create_groups != '1') && (!empty($create_groups))) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                     Por favor, elija \'si\' o \'no\' para la pregunta <i>¿Cuántos grupos tiene la oficina?.
                  </div></div>';
    }

    if (!empty($string)) {$post_officename = stripslashes($post_officename);}
    if (!empty($string2)) {$post_officename = stripslashes($post_officename);}


    echo '<div class="row">
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-suitcase"></i> Crear Oficina</h3>
                </div>
                <div class="box-body">';

//Segundo formulario si salta un error.
    echo "         <form name='form' action='$self' method='post'>\n";
    echo "            <table align=center class=table width=60% border=0 cellpadding=3 cellspacing=0>\n";
    echo "              <tr>
                          <td class=table_rows_output height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                            &nbsp;*Nombre de la oficina:
                          </td>

                          <td class=table_rows width=80% style='padding-left:20px;'>
                            <input type='text' size='25' maxlength='50' name='post_officename' value=\"$post_officename\">
                          </td>
                        </tr>\n";

    if (!empty($string)) {$post_officename = addslashes($post_officename);}
    if (!empty($string2)) {$post_officename = addslashes($post_officename);}

    echo "              <tr>
                          <td class=table_rows_output height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                            ¿Desea crear grupos para la oficina?
                          </td>\n";

    if ($create_groups == '1') {

    echo "                  <td class=table_rows align=left width=80% style='padding-left:20px;'>
                              <input type='radio' name='create_groups' value='1' checked onFocus=\"javascript:form.how_many.disabled=false;form.how_many.style.background='#ffffff';\">Si
                              <input type='radio' name='create_groups' value='0' onFocus=\"javascript:form.how_many.disabled=true;form.how_many.style.background='#eeeeee';\">No
                            </td>
                        </tr>\n";
    } else {

    echo "                  <td class=table_rows align=left width=80% style='padding-left:20px;'>
                              <input type='radio' name='create_groups' value='1' onFocus=\"javascript:form.how_many.disabled=false;form.how_many.style.background='#ffffff';\">Si
                              <input checked type='radio' name='create_groups' value='0' onFocus=\"javascript:form.how_many.disabled=true;form.how_many.style.background='#eeeeee';\">No
                            </td>
                        </tr>\n";
    }

    echo "              <tr>
                          <td class=table_rows_output height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                            ¿Cuántos?
                          </td>

                          <td class=table_rows width=80% style='padding-left:20px;'>\n";

    if ($create_groups == '1') {
    echo "                      <input type='text' size='2' maxlength='1' name='how_many' value='$how_many'></td></tr>\n";
    } else {
    echo "                      <input disabled type='text' size='2' maxlength='1' name='how_many' style='background:#eeeeee;' value='$how_many'></td></tr>\n";
    }

    echo "              <tr>
													<td class=table_rows align=right colspan=3 style='font-size: 11px;'>*&nbsp;Campos requeridos&nbsp;
													</td>
												</tr>\n";
    echo "            </table>\n";
    echo "            <div class='box-footer'>
                        <button type='button' id='formButtons' onclick='location=\"officeadmin.php\"' class='btn btn-default pull-right' style='margin: 0px 10px 0px 10px;'>
                          <i class='fa fa-ban'></i>
                          Cancelar
                        </button>

                        <button id='formButtons' type='submit' name='submit' value='Create Office' class='btn btn-info pull-right'>
                          <i class='fa fa-plus'></i>
                          Crear oficina
                        </button>
                      </div>
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

  // end post validation //

  if (isset($input_group_name)) {

      for ($x=0;$x<$how_many;$x++) {
        $z = $x+1;
      // begin post validation //
        if (empty($input_group_name[$z])) {$empty_groupname = '1';}
      //if (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $input_group_name[$z])) {$evil_groupname = '1';}
        if (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $input_group_name[$z])) {$evil_groupname = '1';}

      }

      @$groupname_array_cnt = count($input_group_name);
      @$unique_groupname_array = array_unique($input_group_name);
      @$unique_groupname_array_cnt = count($unique_groupname_array);

      if ((@$empty_groupname != '1') && (@$evil_groupname != '1') && (@$groupname_array_cnt == @$unique_groupname_array_cnt)) {

        $query = "insert into ".$db_prefix."offices (officename) values ('".$post_officename."')";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        $query2 = "select * from ".$db_prefix."offices where officename = '".$post_officename."'";
        $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);

        while ($row=mysqli_fetch_array($result2)) {
        $tmp_officeid = "".$row['officeid']."";
        }
        ((mysqli_free_result($result2) || (is_object($result2) && (get_class($result2) == "mysqli_result"))) ? true : false);

        for ($x=0;$x<$how_many;$x++) {
          $y = $x+1;
          $query3 = "insert into ".$db_prefix."groups (groupname, officeid) values ('".$input_group_name[$y]."', '".$tmp_officeid."')";
          $result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);
        }


      //Notificaciones de crear grupos con la oficina.
      if (@$empty_groupname == '1')  {
        echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                       Se requiere un nombre de grupo.
                    </div></div>';
      } elseif (@$evil_groupname == '1') {
        echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                        No están permitidos caracteres alfanuméricos, acentos, apostrofes, comas y espacios para crear un usuario.
                    </div></div>';
      } elseif (@$groupname_array_cnt != @$unique_groupname_array_cnt) {
        echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                        El grupo ya existe. Introduce otro nombre.
                    </div></div>';
      }

      if ((@$empty_groupname != '1') && (@$evil_groupname != '1') && (@$groupname_array_cnt == @$unique_groupname_array_cnt)) {
        if ($how_many == '1') {
          echo '       <div id="float_alert" class="col-md-10"><div class="alert alert-success alert-dismissible">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                       <h4><i class="icon fa fa-check-circle"></i>Grupo creado!</h4>
                          '. $how_many .' grupo ha sido creado satisfactoriamente para la oficina '. $post_officename .'.
                       </div></div>';
        } elseif ($how_many > '1') {
          echo '       <div id="float_alert" class="col-md-10"><div class="alert alert-success alert-dismissible">
      		             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      		             <h4><i class="icon fa fa-check-circle"></i>Grupos creados!</h4>
      		                '. $how_many .' grupos han sido creados satisfactoriamente para la oficina '. $post_officename .'.
      		             </div></div>';
        }
      }

      echo '<div class="row">
              <div id="float_window" class="col-md-10">
                <div class="box box-info"> ';
      echo '      <div class="box-header with-border">
                       <h3 class="box-title"><i class="fa fa-suitcase"></i> Crear Oficina</h3>
                  </div>
                  <div class="box-body">';
      }

      //TRADUCIR
      echo "         <form name='form' action='$self' method='post'>\n";
      echo "            <table align='center' class='table'>\n";
      echo "              <tr>
                            <td class=table_rows_output height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                              Nombre de la oficina:&nbsp;*
                            </td>

                            <td class=table_rows colspan=2 width=80% style='padding-left:20px;'>
                              <input type='hidden' name='post_officename' value='$post_officename'>$post_officename
                            </td>
                          </tr>\n";

      echo "              <tr>
                            <td class=table_rows_output height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                              ¿Desea crear grupos para la oficina?
                            </td>

                            <td class=table_rows colspan=2 width=80% style='padding-left:20px;'>
                              <input type='hidden' name='create_groups' value='$create_groups'>$create_groups
                            </td>
                          </tr>\n";

      echo "              <tr>
                            <td class=table_rows_output height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                              ¿Cuántos?
                            </td>

                            <td class=table_rows colspan=2 width=80% style='padding-left:20px;'>
                              <input type='hidden' name='how_many' value='$how_many'>$how_many
                            </td>
                          </tr>\n";
      echo "            </table>\n";
      echo "            <table align=center class='table' valign=top width=60% border=0 cellpadding=0 cellspacing=3>\n";

      for ($x=0;$x<$how_many;$x++) {
        $y = $x+1;

        if ((@$empty_groupname == '1') || (@$evil_groupname == '1') || (@$groupname_array_cnt != @$unique_groupname_array_cnt)) {
        echo "            <tr><td class=table_rows colspan=2>$y.&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type='text' size='25' maxlength='50' name='input_group_name[$y]' value=\"$input_group_name[$y]\"></td>
                            </tr>\n";
        } else {
        echo "            <tr><td class=table_rows colspan=2>$y.&nbsp;&nbsp;&nbsp;&nbsp;$input_group_name[$y]</td></tr>\n";
        }
      } // end for loop

      echo "            </table>\n";

      if ((@$empty_groupname == '1') || (@$evil_groupname == '1') || (@$groupname_array_cnt != @$unique_groupname_array_cnt)) {
      echo "            <div class='box-footer'>
                          <button type='button' id='formButtons' onclick='location=\"officeadmin.php\"' class='btn btn-default pull-right' style='margin: 0px 10px 0px 10px;'>
                            <i class='fa fa-ban'></i>
                            Cancelar
                          </button>

                          <button id='formButtons' type='submit' name='submit' value='Create Office' class='btn btn-info pull-right'>
                            <i class='fa fa-plus'></i>
                            Crear oficina
                          </button>
                        </div>
                    </form>\n";
      echo '     </div>
              </div>
            </div>
          </div>';
          include '../theme/templates/endmaincontent.inc';
          include '../footer.php';
          include '../theme/templates/controlsidebar.inc';
          include '../theme/templates/endmain.inc';
          include '../theme/templates/adminfooterscripts.inc';exit;

      } else {

      echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
      echo "              <tr>
                            <td>
                            <div class='box-footer'>
                              <button id='formButtons' type='button' onclick='location=\"officeadmin.php\"' class='btn btn-success pull-right'>
                                Aceptar
                                <i class='fa fa-check'></i>
                              </button>
                            </div>
                            </td>
                          </tr>
                        </table>
                    </form>\n";
      echo '     </div>
              </div>
            </div>
          </div>';
          include '../theme/templates/endmaincontent.inc';
          include '../footer.php';
          include '../theme/templates/controlsidebar.inc';
          include '../theme/templates/endmain.inc';
          include '../theme/templates/adminfooterscripts.inc';exit;
      }

  } else {

      if (!isset($how_many)) {
        $query = "insert into ".$db_prefix."offices (officename) values ('".$post_officename."')";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        }

    echo '       <div id="float_window" class="col-md-10"><div class="alert alert-success alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 <h4><i class="icon fa fa-check-circle"></i>Oficina creada!</h4>
                    La oficina ha sido creada satisfactoriamente.
                 </div></div>';
    echo '<div class="row">
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-suitcase"></i> Crear Oficina</h3>
                </div>
                <div class="box-body">';

    echo "          <form name='form' action='$self' method='post'>\n";
    echo "            <table class=table_border>\n";
    echo "              <tr>
                          <td class=table_rows_output height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                            Nombre de la oficina:
                          </td>

                          <td class=table_rows colspan=2 width=80% style='padding-left:20px;'>
                            <input type='hidden' name='post_officename' value='$post_officename'>$post_officename
                          </td>
                        </tr>\n";

    echo "              <tr>
                          <td class=table_rows_output height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                            ¿Ha creado grupos para la oficina?
                          </td>

                          <td class=table_rows colspan=2 width=80% style='padding-left:20px;'>\n";

    if ($create_groups == "1") {$tmp_create_groups = "Yes";}
    else {$tmp_create_groups = "No";}

    echo "                <input type='hidden' name='create_groups' value='$create_groups'>$tmp_create_groups</td>
                        </tr>\n";

    if (!isset($how_many)) {

    echo "            </table>\n";
    echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    //DIEGO cambie la referencia officeadmin.php
    echo "              <tr>
                          <td
                          <div class='box-footer'>
                            <button id='formButtons' type='button' onclick='location=\"officeadmin.php\"' class='btn btn-success pull-right'>
                              Aceptar
                              <i class='fa fa-check'></i>
                            </button>
                          </div>
                          </td>
                        </tr>
                      </table>
                    </form>\n";
    echo '         </div>
                  </div>
              </div>
          </div>';
    include '../theme/templates/endmaincontent.inc';
    include '../theme/templates/controlsidebar.inc';
    include '../theme/templates/endmain.inc';
    include '../theme/templates/adminfooterscripts.inc';
    include '../footer.php';exit;

    }

    if (isset($how_many)) {

    echo "              <tr>
                          <td class=table_rows_output height=20 width=20% style='padding-left:32px;' nowrap>
                            ¿Cuántos?
                          </td>

                          <td class=table_rows width=80% style='padding-left:20px;'>
                            <input type='hidden' name='how_many' value='$how_many'>$how_many
                          </td>
                        </tr>\n";
    echo "            </table>\n";
    echo "            <table align=center class=table>\n";

        if ($how_many == '1') {

    echo "              <tr>
                          <td class=table_rows width80%>
                            Ha elegido crear <b>$how_many</b> grupo para la oficina
                            <b>$post_officename</b>. Por favor, introduce el nombre del grupo.
                          </td>
                        </tr>\n";
        } elseif ($how_many > '1') {

    echo "              <tr>
                          <td height=40 class=table_rows colspan=2>
                            Ha elegido crear <b>$how_many</b> grupos para la oficina
                            <b>$post_officename</b>. Por favor, introduce los nombres de los grupos.
                          </td>
                        </tr>\n";
        }

        for ($x=0;$x<$how_many;$x++) {
          $y = $x+1;
    echo "              <tr><td class=table_rows colspan=2>$y.&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type='text' style='margin: 0px 0px 10px 20px;' required='true' size='25' maxlength='50' name='input_group_name[$y]'></td></tr>\n";

        }
    echo "</table>\n";

    }

    echo "            <div class='box-footer'>
                        <button type='button' id='formButtons' onclick='location=\"officeadmin.php\"' class='btn btn-default pull-right' style='margin: 0px 10px 0px 10px;'>
                          <i class='fa fa-ban'></i>
                          Cancelar
                        </button>

                        <button id='formButtons' type='submit' name='submit' value='Create Office' class='btn btn-success pull-right'>
                          <i class='fa fa-plus'></i>
                          Crear oficina
                        </button>
                      </div>
                    </form>\n";
    echo '      </div>
            </div>
          </div>
        </div>';
    include '../theme/templates/endmaincontent.inc';
    include '../footer.php';
    include '../theme/templates/controlsidebar.inc';
    include '../theme/templates/endmain.inc';
    include '../theme/templates/adminfooterscripts.inc';exit;
    }
}
?>
