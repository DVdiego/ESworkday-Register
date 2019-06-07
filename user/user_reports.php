<?php

session_start();




include '../config.inc.php';
include 'header.php';
include 'topmain.php';
include 'leftmain.php';


echo "<title>$title</title>\n";
$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
setlocale(LC_ALL,'es_ES.UTF-8');

$current_page = "worker_reports.php";

if (!isset($_SESSION['valid_profile'])) {

echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Administration</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
echo "        <tr class=right_main_text><td align=center>1 You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=admin'><u>here</u></a> to login.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}


if($request == 'GET') {

$_SERVER['username'] = $_GET['username'];


  $get_user = $_SERVER['username'];
  if (get_magic_quotes_gpc()) {$get_user = stripslashes($get_user);}
  $get_user = addslashes($get_user);



  if($login_with_fullname == "yes"){
    $query = "select empfullname from ".$db_prefix."employees where empfullname = '".$get_user."'";
  }elseif ($login_with_displayname == "yes"){
    $query = "select empfullname from ".$db_prefix."employees where displayname = '".$get_user."'";
  }elseif ($login_with_dni == "yes"){
    $query = "select empfullname from ".$db_prefix."employees where empDNI = '".$get_user."' ";
  }

  $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
  while ($row=mysqli_fetch_array($result)) {
  $username = stripslashes("".$row['empfullname']."");
  }
  ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
  if (!isset($username)) {echo "username is not defined for this user.\n"; exit;}


    echo '<div class="row">
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                   <h3 class="box-title"><i class="fa fa-file-o"></i> Generar Informe</h3>
                 </div>
                <div class="box-body">';
    echo "<form id='form_reports' class='form-horizontal' role='form' name='timeclock' action='$self' method='post'>";


    echo "    <div class='form-group'>
                <label style='padding-right: 10px;'>Nombre:</label>

                <input type='hidden' name='post_username' maxlength='25' class='form-control' value=\"$username\">$username

              </div>";

    echo "    <div class='form-group'>
                <label style='padding-right: 10px;'>Fecha inicio:</label>
                <input type='date' size='10' maxlength='10' name='from_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
                <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
              </div>";

   echo "    <div class='form-group'>
               <label style='padding-right: 27px;'>Fecha fin:</label>
               <input type='date' size='10' maxlength='10' name='to_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
               <a href=\"#\" onclick=\"form.to_date.value='';cal.select(document.forms['form'].to_date,'to_date_anchor','$js_datefmt');
               return false;\" name=\"to_date_anchor\" id=\"to_date_anchor\" style='font-size:11px;color:#27408b;'></a>
            </div>";

    echo "    <div class='form-group'>
                <button type='submit' class='btn btn-lg btn-primary'>Consultar</button>
              </div>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
echo "</div>";
echo "</div>";
//echo " <tr><td height=90%></td></tr>\n";
include '../theme/templates/endmaincontent.inc';
include '../footer.php';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';

//echo "</div>"; # se supone que cierra "<div class='col-sm-12 col-md-10 col-lg-10'>";
}

if($request == 'POST') {
    //Begin post validation
    $emp_fullname = $_POST['post_username'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    if(!isset($emp_fullname) || $emp_fullname == '') {

        echo "      <table width=100% height=100% border=0 cellpadding=0 cellspacing=0>\n";
        echo "        <div class='alert alert-danger'>
                      <strong>¡Error!</strong> Se ha producido un error. Por favor, vuelva a intentarlo.
                      </div>\n";
        echo "      </table>\n";
    }





                $query = "SELECT ".$db_prefix."info.*, ".$db_prefix."employees.*, ".$db_prefix."punchlist.*
                FROM ".$db_prefix."info, ".$db_prefix."employees, ".$db_prefix."punchlist
                where ".$db_prefix."info.timestamp = ".$db_prefix."employees.tstamp AND ".$db_prefix."info.fullname = ".$db_prefix."employees.empfullname
                AND ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems
                AND ".$db_prefix."employees.disabled <> '1'
                AND ".$db_prefix."employees.empfullname = '" . $emp_fullname . "'
                ";

                $result = mysqli_query($GLOBALS["___mysqli_ston"],$query);

                echo "<div class='container'>";
                echo "<br>";
                if($display_logo_report == "yes" && "premium" == strtolower($version_worktime)){

                  echo "<div id='logo-report'>
                          <img src=$logo_report alt='' />
                        </div>";
                }

                echo "<div class='row'>
                            <div class='col-sm-12 col-md-4 col-lg-4'>
                                Empresa: $enterprise_name
                            </div>
                            <div class='col-sm-12 col-md-4 col-lg-4'>
                                N.I.F.: $enterprise_nif
                            </div>
                            <div class='col-sm-12 col-md-4 col-lg-4'>
                                Fecha del informe: ".strftime("%D %r", time())."
                            </div>
                        </div>";
                hours_worked_report($emp_fullname,$from_date,$to_date,$db_prefix);

}



function hours_worked_report($emp_fullname,$from_date,$to_date,$db_prefix) {
    $employees_cnt = 0;
    $employees_empfullname = array();
    $employees_displayname = array();
    $info_cnt = 0;
    $info_fullname = array();
    $info_inout = array();
    //$info_timestamp = array();
    $info_timestamp = 0;
    $info_notes = array();
    $info_date = array();
    $x_info_date = array();
    $info_start_time = array();
    $info_end_time = array();
    $punchlist_in_or_out = array();
    $punchlist_punchitems = array();
    $secs = 0;
    $total_hours = 0;
    $row_count = 0;
    $page_count = 0;
    $punch_cnt = 0;
    $tmp_z = 0;

    /*EN CASO DE QUERER ESTABLECER FECHAS DE COMIENZO Y FIN EDITAR ESTAS VARIABLES*/
    if(empty($from_date) || empty($to_date) ){
      $from_timestamp = 0; //'0' desde todos los tiempos
      $to_timestamp = time();

    }else {
      // le sumas 1 min
      $from_timestamp = strtotime ($from_date) + 60;
      // le sumas 23h:59m -> 86340mills
      $to_timestamp = strtotime ($to_date) + 86340;
    }



    $query = "select ".$db_prefix."info.fullname, ".$db_prefix."info.`inout`, ".$db_prefix."info.timestamp, ".$db_prefix."info.notes,
    ".$db_prefix."info.ipaddress, ".$db_prefix."punchlist.in_or_out, ".$db_prefix."punchlist.punchitems, ".$db_prefix."punchlist.color, ". $db_prefix . "employees.empDNI
    , ". $db_prefix . "info.latitude, ". $db_prefix . "info.longitude
    from ".$db_prefix."info, ".$db_prefix."punchlist, ".$db_prefix."employees
    where ".$db_prefix."info.fullname = '".$emp_fullname."' and ".$db_prefix."info.timestamp >= '".$from_timestamp."'
    and ".$db_prefix."info.timestamp < '".$to_timestamp."' and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems
    and ".$db_prefix."employees.empfullname = '".$emp_fullname."' and ".$db_prefix."employees.empfullname <> 'admin'
    order by ".$db_prefix."info.timestamp asc";
    $result = mysqli_query($GLOBALS["___mysqli_ston"],$query);

    $count = 0;
    $last_date = "";
    $last_in_or_out = -1;
    $last_timestamp = 0;
    $suma_total = 0;

    $sum_in = 0;
    $sum_out = 0;

    $total_sum = 0;
    $aux_sum = 0;
    $num_files = mysqli_num_rows($result);
    //echo "número de filas: $num_files";
    while ($row=mysqli_fetch_array($result)) {

        $info_fullname = stripslashes("".$row['fullname']."");
        $dni = "".$row['empDNI']."";
        $info_inout = "".$row['inout']."";
        $info_timestamp = "".$row['timestamp'].""; //+ $tzo;
        $info_notes = "".$row['notes']."";
        $info_ipaddress = "".$row['ipaddress']."";
        $punchlist_in_or_out = "".$row['in_or_out']."";
        $punchlist_punchitems[] = "".$row['punchitems']."";
        $punchlist_color = "".$row['color']."";
        $latitude = "".$row['latitude']."";
        $longitude = "".$row['longitude']."";
        $info_cnt++;




        //echo "contador: $count\n";
        //Se ejecuta únicamente con la primera fila de la consulta
        if($count == 0) {

          echo "<br>";
          echo "<div class='row'>
                      <div class='col-sm-12 col-md-6 col-lg-6'>
                          <strong>Nombre: $info_fullname</strong>
                      </div>
                      <div class='col-sm-12 col-md-6 col-lg-6'>
                          <strong>DNI: $dni</strong>
                      </div>
                  </div>";
        }



        //Se ejecuta cuando detecta que hemos cambiado de día
        //$date_info = date("r",$info_timestamp);
        $date_info = strtotime(date("d-m-y",$info_timestamp));


        $date_format = strftime("%A %d de %B del %Y", $info_timestamp);



        //$total_sum = $total_sum + $aux_sum;
        /*resetea las variables a 0 para cada día*/
        if($last_date != $date_info) {


          list($hours,$minutes,$seconds) = format_time($aux_sum);
          $total_sum = $total_sum + $aux_sum;
          //condición para que imprima solo cuando timestamp es positivo, es decir cada in --> out y no out--> in
          //if($aux_sum>0 && $hours <= 12){
            if($aux_sum>0){

            echo "<br>";
            echo "<div class='row'>
                    <div class='col-sm-12 col-md-12 col-lg-12'>
                        <strong>
                            Tiempo trabajado en el día = ".$hours."h:".$minutes."m
                        </strong>
                    </div>
                  </div>\n";
            //echo " Suma del tiempo de trabajo del día: $time_aux";
          }else {

            //condición para que salte el mensaje solo cuando se ha realizado la suma de horas previamente
            if($count>0){

              echo "<br>";
              echo "<div class='row'>
                        <div class='col-sm-12 col-md-10 col-lg-10'>
                          <div class='alert alert-warning'>
                            <strong>
                                ¡Aviso! No has registrado correctamente los estados in/out del día. ¡Contactar con el administrador!
                                <a class='admin_headings' href=' '> contacto</a>
                            </strong>
                          </div>
                      </div>
                    </div>\n";

            }
          }

          $sum_in = 0;
          $sum_out = 0;


          echo "<hr class='separate-days'>\n";
          echo "<div class='row'>";
          echo "  <div class='col-sm-12 col-md-2 col-lg-2'>
                    <strong><span style='color:'> Estado</span></strong>
                  </div>";
          echo "  <div class='col-sm-12 col-md-4 col-lg-4'>
                    <strong><span style='color:'> Fecha</span></strong>
                  </div>";
          echo "  <div class='col-sm-12 col-md-2 col-lg-2'>
                    <strong><span style='color:'> Dirección IP</span></strong>
                  </div>";
          echo "  <div class='col-sm-12 col-md-2 col-lg-2'>
                    <strong><span style='color:'> Comentarios</span></strong>
                  </div>";
          echo "  <div class='col-sm-12 col-md-2 col-lg-2'>
                    <strong><span style='color:'> Dirección</span></strong>
                  </div>";
          echo "</div>";
          echo "<br>";

        }



        /*suma todos los in o out para despues sacar la diferencia */
        if($punchlist_in_or_out == 1){
          $sum_in = $sum_in+$info_timestamp;
          //echo "IN suma: $sum_in"; echo "  IN info: $info_timestamp";
        }else {
          $sum_out = $sum_out+$info_timestamp;
          //echo "OUT suma: $sum_out"; echo "  OUT info: $info_timestamp";
        }

        /*variable que almacena las horas de trabajo de cada día*/
        $aux_sum = $sum_out-$sum_in;

        ?>



        <div class="row">
            <div class="col-sm-12 col-md-2 col-lg-2">
                <span style='color: <?php echo $punchlist_color?>'><?php echo "$info_inout"?></span>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
                <?php $datetime = strftime("%d de %B de %Y, %H:%M", $info_timestamp);echo "$datetime";
                ?>
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2">
              <span style='color: <?php echo $punchlist_color?>'><?php echo "$info_ipaddress"?></span>
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2">
                <?php echo "$info_notes";?>
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2">
            <?php $url = 'https://www.google.com/maps/?q=' . $latitude . ',' . $longitude;?>
                <a href=<?php echo $url?> target='_blank'>Ver en mapa</a>
            </div>
        </div>



<?php

    /*condición para operar cuando la fecha de inicio es igual a la fecha fin*/
    if($count+1 == $num_files){


      $total_sum = $total_sum + $aux_sum;
      list($hours,$minutes,$seconds) = format_time($aux_sum);
      list($thours,$tminutes,$tseconds) = format_time($total_sum);

      //condición para que imprima solo cuando timestamp es positivo, es decir cada in --> out y no out--> in
      if($aux_sum>0 && $hours <= 12){

        echo "<br>";
        echo "<div class='row'>
                <div class='col-sm-12 col-md-10 col-lg-10'>
                    <strong>
                          Tiempo trabajado en el día = ".$hours."h:".$minutes."m
                    </strong>
                </div>
              </div>\n";
        //echo " Suma del tiempo de trabajo del día: $time_aux";
        echo "<hr class='separate-days'>\n";


      } else {

        //condición para que salte el mensaje solo cuando se ha realizado la suma de horas previamente
        if($count>0){

          echo "<br>";
          echo "<div class='row'>
                    <div class='col-sm-12 col-md-10 col-lg-10'>
                      <div class='alert alert-warning'>
                        <strong>
                            ¡Aviso! No has registrado correctamente los estados in/out del día. ¡Contactar con el administrador!
                            <a class='admin_headings' href=' '> contacto</a>
                        </strong>
                      </div>
                  </div>
                </div>\n";
          echo "<hr class='separate-days'>\n";
        }

      }

      //7680 son las horas de trabajo realizadas en 4 años, si se trabaja 8h diarias,
      //como la ley obliga a tener el historial de almenos 4 años
      // pongo el valor 8000
      if($total_sum>0 && $thours<8000){

        $from_date = strftime('%D', $from_timestamp);
        $to_date = strftime('%D', $to_timestamp);
        echo "<div class='row'>
                <div class='col-sm-12 col-md-10 col-lg-10'>
                    <strong>
                        Tiempo trabajado, $from_date hasta $to_date &emsp;&emsp;&emsp;&emsp;&emsp;&emsp; TOTAL ".$thours."h:".$tminutes."m
                    </strong>

                </div>
              </div>\n";
        echo "<hr class='separate-days'>\n";

      }else{

        echo "<div class='row'>
                  <div class='col-sm-12 col-md-10 col-lg-10'>
                    <div class='alert alert-warning'>
                      <strong>
                          ¡Aviso! No se puede realizar la suma total sino se corrigen los errores. ¡Contactar con el administrador!
                          <a class='admin_headings' href=' '> contacto</a>
                      </strong>
                    </div>
                  </div>
              </div>\n";
        echo "<hr class='separate-days'>\n";
      }


    }

    //$last_date = date("d",$info_timestamp);
    $last_date = strtotime(date("d-m-y",$info_timestamp));
    //$last_in_or_out = $punchlist_in_or_out;
    $last_timestamp = $info_timestamp;
    $count++;
  }
    echo "</div><!--Cierra el div container-->";
}




function format_time($time_in_seconds) {

   $hours = floor($time_in_seconds / 3600);
   $minutes = floor(($time_in_seconds - ($hours * 3600)) / 60);
   $seconds = $time_in_seconds - ($hours * 3600) - ($minutes * 60);

   //return $hours . ':' . $minutes . ":" . $seconds;
   return array($hours,$minutes,$seconds);
}

?>
