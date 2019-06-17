<script>

    //llama a la función  de geolocalización cuando carga la página
    window.addEventListener('load',function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(insertPosition, funcError, {});
        } else {
            alert('No geolocation supported');
        }
    },false);


    //inserta en el formulario
    function insertPosition(position) {
        $("input[name='latitude']").val(position.coords.latitude);
        $("input[name='longitude']").val(position.coords.longitude);
    }

    function funcError(err) {
      alert(err.message);
    }
</script>
<?php
session_start();

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
$current_page = "time_punch_employees.php";
include '../config.inc.php';



// Make sure they are a valid user
if (!isset($_SESSION['valid_user'])) {
  include 'header.php';
  include 'topmain.php';


    echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
    echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Administration</td></tr>\n";
    echo "  <tr class=right_main_text>\n";
    echo "    <td align=center valign=top scope=row>\n";
    echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
    echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
    echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=admin'><u>here</u></a> to login.</td></tr>\n";
    echo "      </table><br /></td></tr></table>\n";
    exit;
}

include 'header.php';
include 'topmain.php';
include 'leftmain.php';

echo "<title>$title - Daily Time Report</title>\n";

if($request == 'GET'){

  include 'header_get_reports.php';



  echo "<div class='row'>
            <div id='float_window' class='col-md-10'>
                <div class='box box-info'>
                    <div class='box-header with-border'>
                    <h3 class='box-title'><i class='fa fa-book'></i> Register multiple workday at a time</h3>
                </div>
                <div class='box-body'>";

  echo "            <form name='form' action='$self' method='post' onsubmit=\"return isFromOrToDate();\" >\n";

  echo "              <input type='hidden' name='date_format' value='$js_datefmt'>\n";
  if ($username_dropdown_only == "yes") {

      $query = "select * from ".$db_prefix."employees order by empfullname asc";
      $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

  echo "                <div class='form-group'>
                          <label> Username: </label>
                            <select name='user_name' class='form-control select2 pull-right' style='width: 50%;'>\n";
  echo "                        <option value ='All'>All</option>\n";

      while ($row=mysqli_fetch_array($result)) {
        $tmp_empfullname = stripslashes("".$row['empfullname']."");
        echo "                  <option>$tmp_empfullname</option>\n";
      }

  echo "                    </select>
                        </div> &nbsp;*\n";
      ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
  } else {

  echo "                <div class='form-group'>
                          <label style='margin-right:38px'>Choose Office: </label>
                            <select name='office_name' class='form-control select2 pull-right' style='width: 50%;' onchange='group_names();'>
                            </select>
                        </div>";

  echo "                <div class='form-group'>
                          <label style='margin-right:35px'>Choose Group: </label>
                            <select name='group_name' class='form-control select2 pull-right' style='width: 50%;' onchange='user_names();'>

                            </select>
                        </div>\n";

  echo "                <div class='form-group'>
                          <label style='margin-right:10px'>Choose Username: </label>
                            <select name='user_name' class='form-control select2 pull-right' style='width: 50%;'>

                            </select>
                        </div>\n";

  }

  echo "               <div class='form-group' style='display: flex;'><label>Status:</label>";

  // query to populate dropdown with punchlist items //
  $query = "select punchitems from ".$db_prefix."punchlist";
  $punchlist_result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

  echo "                  <select class='form-control' name='post_statusname' style='margin-left: 7px;width: 149px;'>
                              <option value =''>
                                ...
                              </option>";

  while ($row = mysqli_fetch_array($punchlist_result)) {
  echo "                      <option> ".$row['punchitems']."
                              </option>";
  }

  echo "                 </select>
                      </div>";
  ((mysqli_free_result( $punchlist_result ) || (is_object( $punchlist_result ) && (get_class( $punchlist_result ) == "mysqli_result"))) ? true : false);

  echo "              <div class='form-group' style='display: -webkit-box;'>
                        <label style='margin-right:10px'>Fecha:</label>
                          <div class='input-group'>
                          <div class='input-group-addon'>
                            <i class='fa fa-calendar'></i>
                          </div>
                            <input type='date' size='10' maxlength='10' name='post_date' style='color: #444;border: #d2d6de;border-style: solid;border-width: thin;height: 33px;width: 149px;padding-left: 10px;' required>
                            <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                            return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                          </div>
                      </div>\n";

  echo"               <div class='bootstrap-timepicker'>
                        <div class='form-group' style='display: flex;'>
                          <label style='margin-right:15px'>Time: </label>
      	                <div class='input-group'>
                                <div class='input-group-addon'>
                                  <i class='fa fa-clock-o'></i>
                                </div>
                              <input type='text' size='10' maxlength='10' class='form-control timepicker' name='post_time' style='width: 150px;' required>
                            </div>
                       </div>
                     </div>";
 echo "<input type='hidden' value='' name='latitude'/>";
 echo "<input type='hidden' value='' name='longitude'/>";
  echo "             <div class='form-group'>
                       <label>Notes:</label>
                         <input type='text' name='post_notes' maxlength='250' class='form-control' style=' width: 98%;' >
                     </div>";

 echo "						<div class='box-footer'>
                      <button type='button' id='formButtons' onclick='location=\"timeadmin.php\"' class='btn btn-default pull-right' style='margin: 0px 10px 0px 10px;'>
                        <i class='fa fa-ban'></i>Cancelar
                      </button>

											<button id='formButtons' type='submit' name='submit'  class='btn btn-success pull-right'>
                        Siguiente
                        <i class='fa fa-arrow-right'></i>
                        </button><a href='usercreate.php'></a>
										</div>";


  echo " </form>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
          <!-- /.col (right) -->
        </div>
        <!-- /.row -->";




  include '../theme/templates/endmaincontent.inc';
  include '../footer.php';
  include '../theme/templates/controlsidebar.inc';
  include '../theme/templates/endmain.inc';
  include '../theme/templates/reportsfooterscripts.inc';
  exit;


}elseif ($request == 'POST') {


  include 'header_post_reports.php';

  @$office_name = $_POST['office_name'];
  @$group_name = $_POST['group_name'];
  @$fullname = stripslashes($_POST['user_name']);
  @$post_date = $_POST['post_date'];
  @$post_time = $_POST['post_time'];
  @$post_statusname = $_POST['post_statusname'];
  @$post_notes = $_POST['post_notes'];


  $lat = $_POST['latitude'];
  $lon = $_POST['longitude'];

  echo "lat: $lat - lon: $lon\n";
  $fullname = addslashes($fullname);


  if(!isset($evil_post)){

    if(!isset($office_name,$group_name,$fullname,$post_date,$post_statusname)){
       $evil_post = '1';
    }


  }

  if(empty($post_statusname)){
    echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                   Se requiere seleccionar un estado.
                </div></div>';
    $evil_post = '1';
  }


  if(isset($evil_post)){




    echo "<div class='row'>
              <div id='float_window' class='col-md-10'>
                  <div class='box box-info'>
                      <div class='box-header with-border'>
                      <h3 class='box-title'><i class='fa fa-book'></i> Register multiple workday at a time</h3>
                  </div>
                  <div class='box-body'>";

    echo "            <form name='form' action='$self' method='post' onsubmit=\"return isFromOrToDate();\" style='margin-left: 5%;'>\n";

    echo "              <input type='hidden' name='date_format' value='$js_datefmt'>\n";
    if ($username_dropdown_only == "yes") {

        $query = "select * from ".$db_prefix."employees order by empfullname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    echo "                <div class='form-group'>
                            <label> Username: </label>
                              <select name='user_name' class='form-control select2 pull-right' style='width: 50%;'>\n";
    echo "                        <option value ='All'>All</option>\n";

        while ($row=mysqli_fetch_array($result)) {
          $tmp_empfullname = stripslashes("".$row['empfullname']."");
          echo "                  <option>$tmp_empfullname</option>\n";
        }

    echo "                    </select>
                          </div> &nbsp;*\n";
        ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
    } else {

    echo "                <div class='form-group'>
                            <label style='margin-right:38px'>Choose Office: </label>
                              <select name='office_name' class='form-control select2 pull-right' style='width: 50%;' onchange='group_names();'>
                              </select>
                          </div>";

    echo "                <div class='form-group'>
                            <label style='margin-right:35px'>Choose Group: </label>
                              <select name='group_name' class='form-control select2 pull-right' style='width: 50%;' onchange='user_names();'>

                              </select>
                          </div>\n";

    echo "                <div class='form-group'>
                            <label style='margin-right:10px'>Choose Username: </label>
                              <select name='user_name' class='form-control select2 pull-right' style='width: 50%;'>

                              </select>
                          </div>\n";

    }

    echo "               <div class='form-group' style='display: flex;'><label>Status:</label>";

    // query to populate dropdown with punchlist items //
    $query = "select punchitems from ".$db_prefix."punchlist";
    $punchlist_result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    echo "                  <select class='form-control' name='post_statusname' style='margin-left: 7px;width: 149px;'>
                                <option value =''>
                                  ...
                                </option>";

    while ($row = mysqli_fetch_array($punchlist_result)) {
    echo "                      <option> ".$row['punchitems']."
                                </option>";
    }

    echo "                 </select>
                        </div>";
    ((mysqli_free_result( $punchlist_result ) || (is_object( $punchlist_result ) && (get_class( $punchlist_result ) == "mysqli_result"))) ? true : false);

    echo "              <div class='form-group' style='display: -webkit-box;'>
                          <label style='margin-right:10px'>Fecha:</label>
                            <div class='input-group'>
                            <div class='input-group-addon'>
                              <i class='fa fa-calendar'></i>
                            </div>
                              <input type='date' size='10' maxlength='10' name='post_date' style='color: #444;border: #d2d6de;border-style: solid;border-width: thin;height: 33px;width: 149px;padding-left: 10px;' required>
                              <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                              return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                            </div>
                        </div>\n";

    echo"               <div class='bootstrap-timepicker'>
                          <div class='form-group' style='display: flex;'>
                            <label style='margin-right:15px'>Time: </label>";
    echo"    	                <div class='input-group'>
                                  <div class='input-group-addon'>
                                    <i class='fa fa-clock-o'></i>
                                  </div>
                                <input type='text' size='10' maxlength='10' class='form-control timepicker' name='post_time' style='width: 150px;' required>";
    echo"
                              </div>
                         </div>
                       </div>";

    echo "             <div class='form-group'>
                         <label>Notes:</label>
                           <input type='text' name='post_notes' maxlength='250' class='form-control' style=' width: 98%;' >
                       </div>";
   echo "						<div class='box-footer'>
                         <button type='button' id='formButtons' onclick='location=\"timeadmin.php\"' class='btn btn-default pull-right' style='margin: 0px 10px 0px 10px;'>
                           <i class='fa fa-ban'></i>Cancelar
                         </button>
                        <button id='formButtons' type='submit' name='submit'  class='btn btn-success pull-right'>Siguiente <i class='fa fa-arrow-right'></i></button><a href='usercreate.php'></a>
                      </div>";


    echo " </form>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col (right) -->
          </div>
          <!-- /.row -->";




    include '../theme/templates/endmaincontent.inc';
    include '../footer.php';
    include '../theme/templates/controlsidebar.inc';
    include '../theme/templates/endmain.inc';
    include '../theme/templates/reportsfooterscripts.inc';
    exit;

  }

  $employees_cnt = 0;
  $employees_empfullname = array();
  $employees_displayname = array();
  $fullname = addslashes($fullname);

  if (strtolower($user_or_display) == "display") {

      if (($office_name == "All") && ($group_name == "All") && ($fullname == "All")) {

          $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL order by displayname asc";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname == 'All')) {

          $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL order by displayname asc";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname != 'All')) {

          $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL and empfullname = '".$fullname."' order by
                    displayname asc";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      } elseif (($office_name != "All") && ($group_name == "All") && ($fullname == "All")) {

          $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and tstamp IS NOT NULL order by
                    displayname asc";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      } elseif (($office_name != "All") && ($group_name != "All") && ($fullname == "All")) {

          $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and groups = '".$group_name."'  and
                    tstamp IS NOT NULL order by displayname asc";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      } elseif (($office_name != "All") && ($group_name != "All") && ($fullname != "All")) {

          $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and groups = '".$group_name."' and
                    empfullname = '".$fullname."' and tstamp IS NOT NULL order by displayname asc";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
      }

  } else {

      if (($office_name == "All") && ($group_name == "All") && ($fullname == "All")) {

          $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL order by empfullname asc";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname == 'All')) {

          $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL order by empfullname asc";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname != 'All')) {

          $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL and empfullname = '".$fullname."' order by
                    empfullname asc";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      } elseif (($office_name != "All") && ($group_name == "All") && ($fullname == "All")) {

          $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and tstamp IS NOT NULL order by
                    empfullname asc";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      } elseif (($office_name != "All") && ($group_name != "All") && ($fullname == "All")) {

          $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and groups = '".$group_name."'  and
                    tstamp IS NOT NULL order by empfullname asc";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      } elseif (($office_name != "All") && ($group_name != "All") && ($fullname != "All")) {

          $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and groups = '".$group_name."' and
                    empfullname = '".$fullname."' and tstamp IS NOT NULL order by empfullname asc";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
      }
  }

  while ($row=mysqli_fetch_array($result)) {

    $employees_empfullname[] = stripslashes("".$row['empfullname']."");
    $employees_displayname[] = stripslashes("".$row['displayname']."");
    $employees_cnt++;
  }

  $timestamp = strtotime($post_date . " " . $post_time);


  for ($x=0;$x<$employees_cnt;$x++) {

    $fullname = stripslashes($fullname);
    $employees_empfullname[$x] = addslashes($employees_empfullname[$x]);
    $employees_displayname[$x] = addslashes($employees_displayname[$x]);

    $query = "insert into ".$db_prefix."info (fullname, `inout`, timestamp, notes, ipaddress, latitude, longitude) values ('".$employees_empfullname[$x]."', '".$post_statusname."', '".$timestamp."', '".$post_notes."', '".$connecting_ip."','" . $lat . "','" . $lon . "')";

    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    $update_query = "update ".$db_prefix."employees set tstamp = '".$timestamp."' where empfullname = '".$employees_empfullname[$x]."'";
    $other_result = mysqli_query($GLOBALS["___mysqli_ston"], $update_query);

  }


  echo "<div class='row'>
            <div id='float_window' class='col-md-10'>
                <div class='box box-info'>";
  echo "          <div class='box-header with-border'>
                    <h3 class='box-title'><i class='fa fa-clock-o'></i> Tiempo añadido satisfactoriamente</h3>
                  </div>
                  <div class='box-body'>";
 echo "             <form name='form' action='$self' method='post' onsubmit=\"return isFromOrToDate();\">\n";

echo "<br>";
 if (($office_name == "All") && ($group_name == "All") && ($fullname == "All")) {

echo "<h3><span>Se ha registrado el Estado: $post_statusname con Fecha: $post_date y Hora: $post_time para todos los empleados de $enterprise_name.</span></h3>";

 } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname == 'All')) {

echo "<h3><span>Se ha registrado el estado: $post_statusname con Fecha: $post_date y Hora: $post_time para $office_name --> $group_name --> $fullname.</span><h3>";

 } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname != 'All')) {

echo "<h3><span>Se ha registrado el estado: $post_statusname con Fecha: $post_date y Hora: $post_time para $office_name --> $group_name --> $fullname.</span><h3>";

 } elseif (($office_name != "All") && ($group_name == "All") && ($fullname == "All")) {

echo "<h3><span>Se ha registrado el estado: $post_statusname con Fecha: $post_date y Hora: $post_time para $office_name --> $group_name --> $fullname.</span><h3>";

 } elseif (($office_name != "All") && ($group_name != "All") && ($fullname == "All")) {


echo "<h3><span>Se ha registrado el estado: $post_statusname con Fecha: $post_date y Hora: $post_time para $office_name --> $group_name --> $fullname.</span><h3>";

 } elseif (($office_name != "All") && ($group_name != "All") && ($fullname != "All")) {

echo "<h3><span>Se ha registrado el estado: $post_statusname con Fecha: $post_date y Hora: $post_time para $office_name --> $group_name --> $fullname.</span><h3>";

 }

 echo "               <input type='hidden' name='date_format' value='$js_datefmt'>\n";
echo "<br>";
echo "						<div class='box-footer'>
                    <button type='button' id='formButtons' onclick='location=\"timeadmin.php\"' class='btn btn-default pull-right' style='margin: 0px 10px 0px 10px;'>
                      <i class='fa fa-sign-out'></i>Salir
                    </button>

                    <button type='button' id='formButtons' onclick='location=\"time_punch_employees.php\"' class='btn btn-success pull-right'>
                      <i class='fa fa-plus-square-o'></i>Continuar
                    </button>
                  </div>";

 echo "
                  </form>
                </div>
           </div>
       </div>";



 include '../theme/templates/endmaincontent.inc';
 include '../footer.php';
 include '../theme/templates/controlsidebar.inc';
 include '../theme/templates/endmain.inc';
 include '../theme/templates/reportsfooterscripts.inc';
 exit;

}

 ?>
