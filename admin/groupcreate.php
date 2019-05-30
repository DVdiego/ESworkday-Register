<?php

session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
echo "<title>$title - Create Group</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

if (!isset($_SESSION['valid_user'])) {

echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Administration</td></tr>\n";
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
                  Click <a class=admin_headings href='../login.php?login_action=admin'><u>here</u></a> to login.
                </td>
              </tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}

include 'leftmain.php'; //esta despues de verficar la sesión para que no cargue el menú lateral sino esta autendicado.


if ($request == 'GET') {

  echo '<div class="row">
          <div id="float_window" class="col-md-10">
            <div class="box box-info"> ';
  echo '      <div class="box-header with-border">
                   <h3 class="box-title"><i class="fa fa-users"></i> Create Group</h3>
              </div>
              <div class="box-body">';
echo "          <form name='form' action='$self' method='post'>\n";
echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";

echo "              <tr><td height=15></td></tr>\n";
echo "              <tr>
                      <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>
                        &nbsp;*Nombre del grupo:
                      </td>

                      <td colspan=2 align=left width=80% style='padding-left:20px;'>
                        <input type='text' size='25' maxlength='50' name='post_groupname'>
                      </td>
                    </tr>\n";

// query to populate dropdown with parent offices //

$query = "select * from ".$db_prefix."offices order by officename asc";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

echo "              <br />
                    <tr>
                      <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>
                        &nbsp;*Oficina:
                      </td>

                      <td colspan=2 align=left width=80% style='padding-left:20px;'>
                      <select name='select_office_name'>\n";
echo "                        <option value ='1'>Elige una</option>\n";

while ($row=mysqli_fetch_array($result)) {
  echo "                        <option>".$row['officename']."</option>\n";
}
echo "                </select></td>
                    </tr>\n";
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

echo "              <tr>
                      <td class=table_rows align=right colspan=3 style='font-weight: bold;font-family:Tahoma;font-size:10px;'>
                        *&nbsp;Campos requeridos&nbsp;
                      </td>
                    </tr>\n";
echo "            </table>\n";
echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
echo "              <tr><td height=40>&nbsp;</td></tr>\n";
echo "              <tr>
                      <td width=30>
                        <button id='formButtons' type='submit' name='submit' value='Create Group' class='btn btn-info'>
                          Crear Grupo
                        </button>

                      </td>

                      <td>
                        <button id='formButtons' class='btn btn-default pull-right'>
                          <a href='groupadmin.php'>
                            Cancelar
                          </a>
                        </button>
                      </td>
                    </tr>
                  </table>
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

$select_office_name = $_POST['select_office_name'];
$post_groupname = $_POST['post_groupname'];

/*FLAG*/
/*  puede ser necesaario implementar algun contenedor div para los mensajes de error, como estan sale bien pero se puede mejorar.
echo "    <td align=left class=right_main scope=col>\n";
echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
echo "        <tr class=right_main_text>\n";
echo "          <td valign=top>\n";
echo "            <br />\n";
*/
$post_groupname = stripslashes($post_groupname);
$select_office_name = stripslashes($select_office_name);
$post_groupname = addslashes($post_groupname);
$select_office_name = addslashes($select_office_name);

// begin post validation //

if (!empty($select_office_name)) {
$query = "select * from ".$db_prefix."offices where officename = '".$select_office_name."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row=mysqli_fetch_array($result)) {
$getoffice = "".$row['officename']."";
$officeid = "".$row['officeid']."";
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
}
if ((!isset($getoffice)) && ($select_office_name != '1')) {echo "Office is not defined for this user. Go back and associate this user with an office.\n";
exit;}

// check for duplicate groupnames with matching officeids //

$query = "select * from ".$db_prefix."groups where groupname = '".$post_groupname."' and officeid = '".@$officeid."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {
  $tmp_groupname = "".$row['groupname']."";
}

$string = strstr($post_groupname, "\'");
$string2 = strstr($post_groupname, "\"");

//if ((!empty($string)) || (empty($post_groupname)) || (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_groupname)) || ($select_office_name == '1') || (@$tmp_groupname == $post_groupname) || (!empty($string2))) {

if ((!empty($string)) || (empty($post_groupname)) || (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_groupname)) || ($select_office_name == '1') || (@$tmp_groupname == $post_groupname) || (!empty($string2))) {


    if (!empty($string)) {
      echo '            <div id="float_window" class="col-md-10 alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                        No están permitidos caracteres alfanuméricos, acentos, apostrofes, comas y espacios para crear un grupo.
                        </div>';
    }elseif (!empty($string2)) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                      No se permiten comillas, barras o espacios para crear una contraseña.
                  </div></div>';
    }elseif (empty($post_groupname)) {
    echo '            <div id="float_window" class="col-md-10 alert alert-warning alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                      Se requiere un nombre de grupo.
                      </div>';
    //}elseif (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_groupname)) {
    }elseif (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_groupname)) {
      echo '            <div id="float_window" class="col-md-10 alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                        No están permitidos caracteres alfanuméricos, acentos, apostrofes, comas y espacios para crear un grupo.
                        </div>';
    }elseif ($select_office_name == '1') {
      echo '            <div id="float_window" class="col-md-10 alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                        Debe elegir una oficina a la que pertenezca el grupo.
                        </div>';
    }elseif (@$tmp_groupname == $post_groupname) {
      echo '            <div id="float_window" class="col-md-10 alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                        El grupo ya existe. Por favor introduzca otro nombre.
                        </div>';
    }
    echo "            <br />\n";

    // end post validation //

    if (!empty($string)) {$post_groupname = stripslashes($post_groupname);}
    if (!empty($string2)) {$post_groupname = stripslashes($post_groupname);}

    echo '<div class="row">
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-users"></i> Create Group</h3>
                </div>
                <div class="box-body">';
    echo "         <form name='form' action='$self' method='post'>\n";
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";

    echo "              <tr><td height=15></td></tr>\n";
    echo "              <tr>
                          <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>
                            &nbsp;*Nombre del grupo:
                          </td>

                          <td colspan=2 align=left width=80% style='padding-left:20px;'>
                            <input type='text' size='25' maxlength='50' name='post_groupname' value=\"$post_groupname\">
                          </td>
                        </tr>\n";

    if (!empty($string)) {$post_groupname = addslashes($post_groupname);}
    if (!empty($string2)) {$post_groupname = addslashes($post_groupname);}

    // query to populate dropdown with parent offices //

    $query = "select * from ".$db_prefix."offices order by officename asc";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    echo "              <tr>
                          <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>
                            &nbsp;*Oficina:
                          </td>

                          <td colspan=2 align=left width=80% style='padding-left:20px;'>
                            <select name='select_office_name'>\n";
    echo "                        <option value ='1'>Elige una</option>\n";

    while ($row=mysqli_fetch_array($result)) {
      if ("".$row['officename']."" == $select_office_name) {
      echo "                        <option selected>".$row['officename']."</option>\n";
      } else {
      echo "                        <option>".$row['officename']."</option>\n";
      }
    }
    echo "                      </select></td></tr>\n";
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

    echo "              <tr>
                          <td class=table_rows align=right colspan=3 style='font-weight: bold;font-family:Tahoma;font-size:10px;'>
                            *&nbsp;Campos requeridos&nbsp;
                          </td>
                        </tr>\n";
    echo "            </table>\n";
    echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr>
                          <td height=40>
                            &nbsp;
                          </td>
                        </tr>\n";
    echo "
                        <tr>
                          <td width=30>
                            <button id='formButtons' type='submit' name='submit' value='Create Group' class='btn btn-info'>
                              Crear Grupo
                            </button>
                          </td>

                          <td>
                            <button id='formButtons' class='btn btn-default pull-right'>
                              <a href='groupadmin.php'>
                                Cancelar
                              </a>
                            </button>
                          </td>
                        </tr>
                      </table>
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

} else {

    $query = "insert into ".$db_prefix."groups (groupname, officeid) values ('".$post_groupname."', '".$officeid."')";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);


    echo '       <div class="col-md-10"><div class="alert alert-success alert-dismissible">
    			       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    			       <h4><i class="icon fa fa-check-circle"></i>¡Grupo creado!</h4>
    			          El grupo ha sido creado satisfactoriamente.
    				     </div></div>';
    echo '<div class="row">
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';


    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-users"></i> Create Group</h3>
                </div>
                <div class="box-body">';
    echo "            <br />\n";
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";

    echo "              <tr><td height=15></td></tr>\n";
    echo "              <tr>
                          <td class=table_rows width=20% height=25 style='padding-left:32px;' nowrap>
                            Nombre del grupo:
                          </td>

                          <td class=table_rows width=80% style='padding-left:20px;' colspan=2>$post_groupname</td>
                        </tr>\n";
    echo "
                        <tr>
                          <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>
                            Oficina:
                          </td>

                          <td class=table_rows width=80% style='padding-left:20px;' colspan=2>$select_office_name</td>
                        </tr>\n";
    echo "
                        <tr><td height=15></td></tr>\n";
    echo "            </table>\n";
    echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr>
                          <td height=20 align=left>
                            &nbsp;
                          </td>
                        </tr>\n";
    echo "
                        <tr>
                          <td>
                            <button id='formButtons' class='btn btn-info pull-right'>
                              <a href='groupadmin.php' style='color: white; font-weight: bold;' >
                                Aceptar
                              </a>
                            </button>
                          </td>
                        </tr>
                      </table>\n";
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
}
?>
