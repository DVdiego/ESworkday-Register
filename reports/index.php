<?php

session_start();




include '../config.inc.php';

include 'header_get_reports.php';
include 'topmain.php';

echo "<title>$title</title>\n";
$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
setlocale(LC_ALL,"es_ES.UTF-8");

$current_page = "last_employees_status.php";

if (!isset($_SESSION['valid_reports_user'])) {

echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Administration</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
echo "        <tr class=right_main_text><td align=center>1 You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=reports'><u>here</u></a> to login.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}

include 'leftmain.php';
    echo "<!-- Content Header (Page header) -->
    <section class='content-header' style='padding-bottom: 30px;'>
      <h1>
        Run Reports
        <small>it all starts here</small>
      </h1>
    </section>";


    $from_date = strtotime(date("m/d/y",time()));
    $to_date = strtotime(date("m/d/y",time()));

    // $from_date = 1560124860;
    //
    // $to_date = 1560211140;

    //$query ="SELECT ".$db_prefix."employees.* FROM employees WHERE empfullname != 'admin' ";
    $query_employees = "select ".$db_prefix."* FROM employees WHERE empfullname != 'admin'";
    // $query_employees = "select ".$db_prefix."employees.empfullname
    // from ".$db_prefix."employees
    // WHERE ".$db_prefix."employees.empfullname != 'admin' ";
    $emp_result = mysqli_query($GLOBALS["___mysqli_ston"],$query_employees);

    while ($row=mysqli_fetch_array($emp_result)) {
      $tmp_empfullname = "".$row['empfullname']."";
      $tmp_dni = "".$row['empDNI']."";
      hours_worked_report($tmp_empfullname,$tmp_dni,$from_date,$to_date,$db_prefix,$root);
    }



// }



function hours_worked_report($emp_fullname,$tmp_dni,$from_date,$to_date,$db_prefix,$root) {



  echo '<!-- Main content -->
        <section class="content_reports" >

        <!-- Default box -->
          <div class="box collapsed-box">';
  echo '<div class="box-header with-border">
          <h3 class="box-title">'.$emp_fullname.'</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-plus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
      </div>';
  echo '<div class="box-body">';
  // echo "<div class='container'>";




    $employees_cnt = 0;
    $employees_empfullname = array();
    $employees_displayname = array();
    $info_cnt = 0;
    $info_fullname = array();
    $info_inout = array();
    // $info_timestamp = array();
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



    $from_timestamp = $from_date + 60;
    $to_timestamp = $to_date + 61080;


    $query = "select ".$db_prefix."info.fullname, ".$db_prefix."info.`inout`, ".$db_prefix."info.timestamp, ".$db_prefix."info.notes,
    ".$db_prefix."info.ipaddress, ".$db_prefix."punchlist.in_or_out, ".$db_prefix."punchlist.punchitems, ".$db_prefix."punchlist.color, ". $db_prefix . "employees.empDNI
    , ". $db_prefix . "info.latitude, ". $db_prefix . "info.longitude
    from ".$db_prefix."info, ".$db_prefix."punchlist, ".$db_prefix."employees
    where ".$db_prefix."info.fullname = '".$emp_fullname."'
    and ".$db_prefix."info.timestamp >= '".$from_timestamp."'
    and ".$db_prefix."info.timestamp <= '".$to_timestamp."'
    and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems
    and ".$db_prefix."employees.empfullname = '".$emp_fullname."' and ".$db_prefix."employees.empfullname <> '".$root."'
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


          // echo "<div class='row'>
          //             <div class='col-sm-12 col-md-6 col-lg-6'>
          //                 <strong>Nombre: $info_fullname</strong>
          //             </div>
          //             <div class='col-sm-12 col-md-6 col-lg-6'>
          //                 <strong>DNI: $dni</strong>
          //             </div>
          //         </div>";
          echo "<div class='row'>
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


              echo "<div class='row'>
                        <div class='col-sm-12 col-md-10 col-lg-10'>
                          <div class='alert alert-warning'>
                            <strong>
                                ¡Aviso! No has registrado correctamente los estados in/out del día. ¡Contactar con el administrador!
                                <a class='admin_headings' href='http://isoftsolutions.es/'> contacto</a>
                            </strong>
                          </div>
                      </div>
                    </div>\n";

            }
          }

          $sum_in = 0;
          $sum_out = 0;


          echo "<hr class='separator-reports'>\n";
          echo "<div class='row'>";
          echo "  <div class='col-xs-3 col-sm-3 col-md-2 col-lg-2'>
                    <strong><span style='color:'> Estado</span></strong>
                  </div>";
          echo "  <div class='col-xs-3 col-sm-3 col-md-4 col-lg-4'>
                    <strong><span style='color:'> Fecha</span></strong>
                  </div>";
          // echo "  <div class='col-xs-2 col-sm-2 col-md-2 col-lg-2'>
          //           <strong><span style='color:'> Dirección IP</span></strong>
          //         </div>";
          echo "  <div class='col-xs-3 col-sm-3 col-md-2 col-lg-2'>
                    <strong><span style='color:'> Comentarios</span></strong>
                  </div>";
          echo "  <div class='col-xs-3 col-sm-3 col-md-2 col-lg-2'>
                    <strong><span style='color:'> Dirección</span></strong>
                  </div>";
          echo "</div>";

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
            <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
                <span style='color: <?php echo $punchlist_color?>'><?php echo "$info_inout"?></span>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-4 col-lg-4">
                <?php $datetime = strftime("%d/%m/%y, %H:%M", $info_timestamp);echo "$datetime";
                ?>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
                <?php echo "$info_notes";?>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
            <?php $url = 'https://www.google.com/maps/?q=' . $latitude . ',' . $longitude;?>
                <a href=<?php echo $url?> target='_blank'>Ver mapa</a>
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


        // echo "<div class='row'>
        //         <div class='col-sm-12 col-md-10 col-lg-10'>
        //             <strong>
        //                   Tiempo trabajado en el día = ".$hours."h:".$minutes."m
        //             </strong>
        //         </div>
        //       </div>\n";
        // //echo " Suma del tiempo de trabajo del día: $time_aux";
         echo "<hr class='separator-reports'>\n";


      } else {

        //condición para que salte el mensaje solo cuando se ha realizado la suma de horas previamente
        // if($count>0){
        //
        //
        //   echo "<div class='row'>
        //             <div class='col-sm-12 col-md-10 col-lg-10'>
        //               <div class='alert alert-warning'>
        //                 <strong>
        //                     ¡Aviso! No has registrado correctamente los estados in/out del día. ¡Contactar con el administrador!
        //                     <a class='admin_headings' href='http://isoftsolutions.es/'> contacto</a>
        //                 </strong>
        //               </div>
        //           </div>
        //         </div>\n";
          echo "<hr class='separator-reports'>\n";
        // }

      }

      //7680 son las horas de trabajo realizadas en 4 años, si se trabaja 8h diarias,
      //como la ley obliga a tener el historial de almenos 4 años
      // pongo el valor 8000
      // if($total_sum>0 && $thours<8000){
      //
      //   $from_date = strftime('%D', $from_timestamp);
      //   $to_date = strftime('%D', $to_timestamp);
      //   echo "<div class='row'>
      //           <div class='col-sm-12 col-md-10 col-lg-10'>
      //               <strong>
      //                   Tiempo trabajado, $from_date hasta $to_date &emsp;&emsp;&emsp;&emsp;&emsp;&emsp; TOTAL ".$thours."h:".$tminutes."m
      //               </strong>
      //
      //           </div>
      //         </div>\n";
      //   echo "<hr class='separator-reports'>\n";
      //
      // }else{
      //
      //   echo "<div class='row'>
      //             <div class='col-sm-12 col-md-10 col-lg-10'>
      //               <div class='alert alert-warning'>
      //                 <strong>
      //                     ¡Aviso! No se puede realizar la suma total sino se corrigen los errores. ¡Contactar con el administrador!
      //                     <a class='admin_headings' href=' '> contacto</a>
      //                 </strong>
      //               </div>
      //             </div>
      //         </div>\n";
      //   echo "<hr class='separator-reports'>\n";
      // }


    }

    //$last_date = date("d",$info_timestamp);
    $last_date = strtotime(date("d-m-y",$info_timestamp));
    //$last_in_or_out = $punchlist_in_or_out;
    $last_timestamp = $info_timestamp;
    $count++;
  }
    // echo "</div><!--Cierra el div container-->";


    echo '      </div>
          <!-- /.box-body -->
            </div>
          <!-- /.box -->
          </section>
          <!-- /.content -->';

}




function format_time($time_in_seconds) {

   $hours = floor($time_in_seconds / 3600);
   $minutes = floor(($time_in_seconds - ($hours * 3600)) / 60);
   $seconds = $time_in_seconds - ($hours * 3600) - ($minutes * 60);

   //return $hours . ':' . $minutes . ":" . $seconds;
   return array($hours,$minutes,$seconds);
}
include '../footer.php';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
?>
