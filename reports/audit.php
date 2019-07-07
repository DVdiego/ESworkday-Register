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

/**
 * This module will generate a report of the audit log.
 */

session_start();

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
$current_page = "audit.php";
setlocale(LC_ALL,'es_ES.UTF-8');
include '../config.inc.php';
include '../admin/header.php';
include 'topmain.php';
include 'leftmain.php';

if ($use_reports_password == "yes") {
    if (!isset($_SESSION['valid_reports_user'])) {
        echo "<title>$title</title>\n";
        include '../admin/header.php';
        include '../admin/topmain.php';

        echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
        echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Reports</td></tr>\n";
        echo "  <tr class=right_main_text>\n";
        echo "    <td align=center valign=top scope=row>\n";
        echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
        echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
        echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=reports'><u>here</u></a> to login.</td></tr>\n";
        echo "      </table><br /></td></tr></table>\n"; exit;
    }
}

echo "<title>$title -  Reportes para Auditoría</title>\n";

if ($request == 'GET') {

    // include '../admin/header_date.php';
    // include 'leftmain.php';
    //
    // if ($use_reports_password == "yes") {
    //     include '../admin/topmain.php';
    // } else {
    //     include 'topmain.php';
    // }


    echo '<div class="row">
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-suitcase"></i> Reportes para Auditoría</h3>
                </div>
                <div class="box-body">';
                echo "            <form name='form' action='$self' method='post' onsubmit=\"return isFromOrToDate();\">\n";

                echo "              <input type='hidden' name='date_format' value='$js_datefmt'>\n";



                echo "              <div class='form-group' style='display: -webkit-box;'>
                                      <label style='padding-right: 10px;'>Fecha Inicio:</label>
                                        <div class='input-group'>

                                        <div class='input-group-addon'>
                                          <i class='fa fa-calendar'></i>
                                        </div>
                                          <input type='date' size='10' maxlength='10' name='from_date' style='color: #444;border: #d2d6de;border-style: solid;border-width: thin;height: 33px;width: 149px;padding-left: 10px;' required>
                                          <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                                          return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                                        </div>
                                    </div>\n";
                echo "              <div class='form-group' style='display: -webkit-box;'>
                                      <label style='padding-right: 27px;'>Fecha Fin:</label>
                                        <div class='input-group'>
                                        <div class='input-group-addon'>
                                          <i class='fa fa-calendar'></i>
                                        </div>
                                          <input type='date' size='10' maxlength='10' name='to_date' style='color: #444;border: #d2d6de;border-style: solid;border-width: thin;height: 33px;width: 149px;padding-left: 10px;' required>
                                          <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                                          return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                                        </div>
                                    </div>\n";


                echo "              <div class='form-group'><div class='radio'>
                                        <label>¿Desea exportarlo como CSV (el enlace al archivo .CSV estará en la parte superior derecha de la página siguiente)</label></div> \n";
                     if (strtolower($export_csv) == "yes") {
                     echo "    <div class='radio'><label><input type='radio' name='csv' value='1' checked>&nbsp;Si</label></div>\n";
                     echo "    <div class='radio'><label><input type='radio' name='csv' value='0'> &nbsp;No </label></div></div>\n";
                     } else {
                     echo "    <div class='radio'><label><input type='radio' name='csv' value='1'> Si</label></div>   <div class='radio'><label><input type='radio' name='csv' value='0' checked>No</label></div></div>\n";
                     }


                echo '    <div class="box-footer">
                          <button type="button" id="formButtons" onclick="location=\'index.php\'" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                            <i class="fa fa-ban"></i>
                            Cancelar
                          </button>
                          <button id="formButtons" type="submit" class="btn btn-success" >Siguiente <i class="fa fa-arrow-right"></i></button></div>
                        </div>
                      </form>
                            <!-- /.box-body -->
                          </div>
                          <!-- /.box -->

                        </div>
                        <!-- /.col (right) -->
                      </div>
                      <!-- /.row -->';




                include '../theme/templates/endmaincontent.inc';
                include '../footer.php';
                include '../theme/templates/controlsidebar.inc';
                include '../theme/templates/endmain.inc';
                include '../theme/templates/reportsfooterscripts.inc';
                exit;
} else {
    //include '../admin/header_date.php';

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    @$tmp_csv = $_POST['csv'];

    // begin post error checking //

    if (isset($tmp_csv)) {
        if (($tmp_csv != '1') && (!empty($tmp_csv))) {
            $evil_post = '1';
            if ($use_reports_password == "yes") {
                include '../admin/topmain.php';
            } else {
                include 'topmain.php';
            }
            echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
            echo "  <tr valign=top>\n";
            echo "    <td>\n";
            echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
            echo "        <tr class=right_main_text>\n";
            echo "          <td valign=top>\n";
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> Choose \"yes\" or \"no\" to the \"<b>Export to CSV?</b>\" question.</td></tr>\n";
            echo "            </table>\n";
        }
    }

    if (!isset($evil_post)) {
        if (empty($from_date)) {
            $evil_post = '1';
            if ($use_reports_password == "yes") {
                include '../admin/topmain.php';
            } else {
                include 'topmain.php';
            }
            echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
            echo "  <tr valign=top>\n";
            echo "    <td>\n";
            echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
            echo "        <tr class=right_main_text>\n";
            echo "          <td valign=top>\n";
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid From Date is required.</td></tr>\n";
            echo "            </table>\n";
 //       } elseif (!eregi ("^([0-9]?[0-9])+[-|/|.]+([0-9]?[0-9])+[-|/|.]+(([0-9]{2})|([0-9]{4}))$", $from_date, $date_regs)) {
        } elseif (!preg_match('/' . "^([0-9]?[0-9])+[-|\/|.]+([0-9]?[0-9])+[-|\/|.]+(([0-9]{2})|([0-9]{4}))$" . '/i', $from_date, $date_regs)) {
            $evil_post = '1';
            if ($use_reports_password == "yes") {
                include '../admin/topmain.php';
            } else {
                include 'topmain.php';
            }
            echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
            echo "  <tr valign=top>\n";
            echo "    <td>\n";
            echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
            echo "        <tr class=right_main_text>\n";
            echo "          <td valign=top>\n";
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid From Date is required.</td></tr>\n";
            echo "            </table>\n";
        } else {
            if ($calendar_style == "amer") {
                if (isset($date_regs)) {
                    $from_month = $date_regs[1];
                    $from_day = $date_regs[2];
                    $from_year = $date_regs[3];
                }
                if ($from_month > 12 || $from_day > 31) {
                    $evil_post = '1';
                    if ($use_reports_password == "yes") {
                        include '../admin/topmain.php';
                    } else {
                        include 'topmain.php';
                    }
                    echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
                    echo "  <tr valign=top>\n";
                    echo "    <td>\n";
                    echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
                    echo "        <tr class=right_main_text>\n";
                    echo "          <td valign=top>\n";
                    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                    echo "              <tr>\n";
                    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid From Date is required.</td></tr>\n";
                    echo "            </table>\n";
                }
            } elseif ($calendar_style == "euro") {
                if (isset($date_regs)) {
                    $from_month = $date_regs[2];
                    $from_day = $date_regs[1];
                    $from_year = $date_regs[3];
                }
                if ($from_month > 12 || $from_day > 31) {
                    $evil_post = '1';
                    if ($use_reports_password == "yes") {
                        include '../admin/topmain.php';
                    } else {
                        include 'topmain.php';
                    }
                    echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
                    echo "  <tr valign=top>\n";
                    echo "    <td>\n";
                    echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
                    echo "        <tr class=right_main_text>\n";
                    echo "          <td valign=top>\n";
                    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                    echo "              <tr>\n";
                    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid From Date is required.</td></tr>\n";
                    echo "            </table>\n";
                }
            }
        }
    }

    if (!isset($evil_post)) {
        if (empty($to_date)) {
            $evil_post = '1';
            if ($use_reports_password == "yes") {
                include '../admin/topmain.php';
            } else {
                include 'topmain.php';
            }
            echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
            echo "  <tr valign=top>\n";
            echo "    <td>\n";
            echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
            echo "        <tr class=right_main_text>\n";
            echo "          <td valign=top>\n";
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid To Date is required.</td></tr>\n";
            echo "            </table>\n";
//        } elseif (!eregi ("^([0-9]?[0-9])+[-|/|.]+([0-9]?[0-9])+[-|/|.]+(([0-9]{2})|([0-9]{4}))$", $to_date, $date_regs)) {
        } elseif (!preg_match('/' . "^([0-9]?[0-9])+[-|\/|.]+([0-9]?[0-9])+[-|\/|.]+(([0-9]{2})|([0-9]{4}))$" . '/i', $to_date, $date_regs)) {
            $evil_post = '1';
            if ($use_reports_password == "yes") {
                include '../admin/topmain.php';
            } else {
                include 'topmain.php';
            }
            echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
            echo "  <tr valign=top>\n";
            echo "    <td>\n";
            echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
            echo "        <tr class=right_main_text>\n";
            echo "          <td valign=top>\n";
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid To Date is required.</td></tr>\n";
            echo "            </table>\n";
        } else {
            if ($calendar_style == "amer") {
                if (isset($date_regs)) {
                    $to_month = $date_regs[1];
                    $to_day = $date_regs[2];
                    $to_year = $date_regs[3];
                }
                if ($to_month > 12 || $to_day > 31) {
                    $evil_post = '1';
                    if ($use_reports_password == "yes") {
                        include '../admin/topmain.php';
                    } else {
                        include 'topmain.php';
                    }
                    echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
                    echo "  <tr valign=top>\n";
                    echo "    <td>\n";
                    echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
                    echo "        <tr class=right_main_text>\n";
                    echo "          <td valign=top>\n";
                    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                    echo "              <tr>\n";
                    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid To Date is required.</td></tr>\n";
                    echo "            </table>\n";
                }
            } elseif ($calendar_style == "euro") {
                if (isset($date_regs)) {
                    $to_month = $date_regs[2];
                    $to_day = $date_regs[1];
                    $to_year = $date_regs[3];
                }
                if ($to_month > 12 || $to_day > 31) {
                    $evil_post = '1';
                    if ($use_reports_password == "yes") {
                        include '../admin/topmain.php';
                    } else {
                        include 'topmain.php';
                    }
                    echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
                    echo "  <tr valign=top>\n";
                    echo "    <td>\n";
                    echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
                    echo "        <tr class=right_main_text>\n";
                    echo "          <td valign=top>\n";
                    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                    echo "              <tr>\n";
                    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid To Date is required.</td></tr>\n";
                    echo "            </table>\n";
                }
            }
        }
    }

    if (isset($evil_post)) {
        echo "            <br />\n";
        echo "            <form name='form' action='$self' method='post' onsubmit=\"return isFromOrToDate();\">\n";
        echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
        echo "              <tr>\n";
        echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/report.png' />&nbsp;&nbsp;&nbsp; Reportes para Auditoría</th> </tr>\n";
        echo "              <tr><td height=15></td></tr>\n";
        echo "              <input type='hidden' name='date_format' value='$js_datefmt'>\n";
        echo "              <tr><td class=table_rows style='padding-left:32px;' width=20% nowrap>From Date: ($tmp_datefmt)</td><td style='padding-left:20px;' width=80% > <input type='text' size='10' maxlength='10' name='from_date' value='$from_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp; <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt'); return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'>Pick Date</a></td><tr>\n";
        echo "              <tr><td class=table_rows style='padding-left:32px;' width=20% nowrap>To Date: ($tmp_datefmt)</td><td style='padding-left:20px;' width=80% > <input type='text' size='10' maxlength='10' name='to_date' value='$to_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp; <a href=\"#\" onclick=\"form.to_date.value='';cal.select(document.forms['form'].to_date,'to_date_anchor','$js_datefmt'); return false;\" name=\"to_date_anchor\" id=\"to_date_anchor\" style='font-size:11px;color:#27408b;'>Pick Date</a></td><tr>\n";
        echo "              <tr><td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>*&nbsp;required&nbsp;</td></tr>\n";
        echo "            </table>\n";
        echo "            <div style=\"position:absolute;visibility:hidden;background-color:#ffffff;layer-background-color:#ffffff;\" id=\"mydiv\" height=200>&nbsp;</div>\n";
        echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr><td class=table_rows height=25 valign=bottom>1.&nbsp;&nbsp;&nbsp;¿Desea exportarlo como CSV (el enlace al archivo .CSV estará en la parte superior derecha de la página siguiente)</td></tr>\n";
        if ($tmp_csv == "1") {
            echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='csv' value='1' checked>&nbsp;Yes<input type='radio' name='csv' value='0'>&nbsp;No</td></tr>\n";
        } else {
            echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='csv' value='1' >&nbsp;Yes <input type='radio' name='csv' value='0' checked>&nbsp;No</td></tr>\n";
        }
        echo "              <tr><td height=10></td></tr>\n";
        echo "            </table>\n";
        echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr><td width=30><input type='image' name='submit' value='Edit Time' align='middle' src='../images/buttons/next_button.png'></td><td><a href='index.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form></td></tr>\n";
        include '../footer.php';
        exit;
    }

    // end post error checking //

    if (!empty($from_date)) {
        // $from_date = "$from_month/$from_day/$from_year";
        $from_date = str_replace("/", "-", $from_date);
        $from_timestamp = strtotime($from_date) - @$tzo;
        $from_date = $_POST['from_date'];
    }



    if (!empty($to_date)) {
        // $to_date = "$to_month/$to_day/$to_year";
        $to_date = str_replace("/", "-", $to_date);
        $to_timestamp = strtotime($to_date) + 86400 - @$tzo;
        $to_date = $_POST['to_date'];
    }

    // $time = time();
    // $rpt_hour = gmdate('H',$time);
    // $rpt_min = gmdate('i',$time);
    // $rpt_sec = gmdate('s',$time);
    // $rpt_month = gmdate('m',$time);
    // $rpt_day = gmdate('d',$time);
    // $rpt_year = gmdate('Y',$time);
    // $rpt_stamp = mktime ($rpt_hour, $rpt_min, $rpt_sec, $rpt_month, $rpt_day, $rpt_year);


    $rpt_stamp = time();

    $rpt_stamp = $rpt_stamp + @$tzo;
    $rpt_time = date($timefmt, $rpt_stamp);
    $rpt_date = date($datefmt, $rpt_stamp);
    $from_date_eur = strftime('%d/%m/%Y',strtotime($from_date));
    $to_date_eur = strftime('%d/%m/%Y',strtotime($to_date));

    /*
      Tabla de datos arribas de los informes
    */
    echo '  <div class="row" style="margin-top: 20px;">
              <div id="float_window" class="col-md-10">
                <div class="box box-info">
                  <div class="box-header">
                    <h3 class="box-title"><i class=fa fa-list></i> Datos</h3>
                  </div>

                  <div class="box-body">
                    <table class="table table-hover">
                      <tr>
                        <td>
                          Fecha del informe: '. $rpt_time .', '. $rpt_date .'
                        </td>
                        <td>
                          <strong>Reportes para Auditoría</strong>
                        </td>
                        <td>
                          Rango de fechas: '. $from_date_eur .' hasta '. $to_date_eur .'
                        <td>
                      </tr>';

if(!empty($tmp_csv)){
echo '                <tr>
                        <td>
                          Descargar el fichero .CSV:
                          <a style="color:#27408b;font-size:16px; text-decoration:underline;"
                          href=\'get_csv.php?rpt=auditlog&csv='.  $tmp_csv .'&from='. $from_timestamp .'&to='. $to_timestamp .'&tzo='. $tzo .'\'>
                            &nbsp;Descargar
                          </a>
                        </td>
                      </tr>';
}
echo '              </table>
                  </div>
                </div>
              </div>
            </div>';

    $row_count = 0;
    $page_count = 0;
    $cnt = 0;
    $modified_when = array();
    $modified_from = array();
    $modified_to = array();
    $modified_by_ip = array();
    $modified_by_user = array();
    $modified_why = array();

    $row_color = $color2; // Initial row color

    $query = "select * from ".$db_prefix."audit where modified_when >= '".$from_timestamp."' and modified_when <= '".$to_timestamp."' order by modified_when asc";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    while ($row=mysqli_fetch_array($result)) {
        $modified_when[] = "".$row["modified_when"]."";
        $modified_from[] = "".$row["modified_from"]."";
        $modified_to[] = "".$row["modified_to"]."";
        $modified_by_ip[] = "".$row["modified_by_ip"]."";
        $modified_by_user[] = stripslashes("".$row["modified_by_user"]."");
        $modified_why[] = "".$row["modified_why"]."";
        $user_modified[] = "".$row["user_modified"]."";
        $cnt++;
    }
      echo '<div class="row">
              <div id="float_window" class="col-md-10">
                <div class="box box-info">';
      echo '      <div class="box-header">';
      echo '        <h3 class="box-title"><i class="fa fa-suitcase"></i> Informes para auditoría</h3>
                  </div>

                  <div class="box-body table-responsive no-padding">';

    for ($x=0;$x<$cnt;$x++) {
        if (!empty($modified_when[$x])) {
            $modified_when[$x] = $modified_when[$x] + @$tzo;
            $modified_when_time = date($timefmt, $modified_when[$x]);
            $modified_when_date = date($datefmt, $modified_when[$x]);
        } else {
            exit;
        }
        if (!empty($modified_from[$x])) {
            $modified_from[$x] = $modified_from[$x] + @$tzo;
            $modified_from_time = date($timefmt, $modified_from[$x]);
            $modified_from_date = date($datefmt, $modified_from[$x]);
        } else {
            $modified_from_time = "".$row["modified_from"]."";
            $modified_from_date = "".$row["modified_from"]."";
        }
        if (!empty($modified_to[$x])) {
            $modified_to[$x] = $modified_to[$x] + @$tzo;
            $modified_to_time = date($timefmt, $modified_to[$x]);
            $modified_to_date = date($datefmt, $modified_to[$x]);
        } else {
            $modified_to_time = "".$row["modified_to"]."";
            $modified_to_date = "".$row["modified_to"]."";
        }
        if ((!empty($modified_from[$x])) && (empty($modified_to[$x]))) {
            $modified_status = "Borrado";
            $modified_color = "#FF0000";
        } elseif ((!empty($modified_from[$x])) && (!empty($modified_to[$x]))) {
            $modified_status = "Editado";
            $modified_color = "#FF9900";
        } elseif ((empty($modified_from[$x])) && (!empty($modified_to[$x]))) {
            $modified_status = "Añadido";
            $modified_color = "#009900";
        }

        if ($row_count == 0) {
            if ($page_count == 0) {
                echo "<table class='misc_items table table-center' width=100% >\n";
                echo "  <tr class=notprint>\n";
                echo "    <th>Usuario</th>\n";
                echo "    <th>Acción</th>\n";
                echo "    <th>Fecha</th>\n";
                echo "    <th>Autor</th>\n";
                echo "    <th>IP</th>\n";
                echo "    <th>Desde</th>\n";
                echo "    <th>Hasta</th>\n";
                echo "    <th>Razón</th></tr>\n";
            } else {
                // display report name and page number of printed report above the column headings of each printed page //
                $temp_page_count = $page_count + 1;
                echo "  <tr><td colspan=2 class=notdisplay style='font-size:9px;color:#000000;padding-left:10px;'>Fecha del Informe: $rpt_time, $rpt_date (page $temp_page_count)</td><td class=notdisplay nowrap style='font-size:9px;color:#000000;' align=right colspan=4>$rpt_name</td></tr>\n";
                echo "  <tr><td class=notdisplay align=right colspan=6 nowrap style='font-size:9px;color:#000000;'> Rango de fechas: $from_date_eur - $to_date_eur</td></tr>\n";
            }
        }

        // begin alternating row colors //
        $row_color = ($row_count % 2) ? $color1 : $color2;

        // display the query results //
        echo "  <tr><td nowrap align=left width=15% style='background-color:$row_color;color:".$row["color"]."; padding-left:10px;'>$user_modified[$x]</td>\n";
        echo "    <td nowrap align=left width=10% style='background-color:$row_color;color:$modified_color; padding-left:10px;'>$modified_status</td>\n";
        echo "  <td nowrap align=left width=15% style='background-color:$row_color;color:".$row["color"]."; padding-left:10px;'>$modified_when_date,&nbsp;$modified_when_time</td>\n";
        echo "    <td nowrap align=left width=15% style='background-color:$row_color;color:".$row["color"]."; padding-left:10px;'>$modified_by_user[$x]</td>\n";
        echo "    <td nowrap align=left width=15% style='background-color:$row_color;color:".$row["color"]."; padding-left:10px;'>$modified_by_ip[$x]</td>\n";
        if (!empty($modified_from[$x])) {
            echo "    <td nowrap align=left width=15% style='background-color:$row_color;color:".$row["color"]."; padding-left:10px;'>$modified_from_date,&nbsp;$modified_from_time</td>\n";
        } else {
            echo "    <td nowrap align=left width=15% style='background-color:$row_color;color:".$row["color"]."; padding-left:10px;'>N/A</td>\n";
        }
        if (!empty($modified_to[$x])) {
            echo "    <td nowrap align=left width=15% style='background-color:$row_color;color:".$row["color"]."; padding-left:10px;'>$modified_to_date,&nbsp;$modified_to_time</td>\n";
        } else {
            echo "    <td nowrap align=left width=15% style='background-color:$row_color;color:".$row["color"]."; padding-left:10px;'>N/A</td>\n";
        }
        if (!empty($modified_why[$x])) {
           echo "    <td nowrap align=left width=15% style='background-color:$row_color;color:".$row["color"]."; padding-left:10px;'>$modified_why[$x]</td>\n";
        } else {
            echo "    <td nowrap align=left width=15% style='background-color:$row_color;color:".$row["color"]."; padding-left:10px;'>Not Entered</td></tr>\n";
        }
        $row_count++;

        // output 40 rows per printed page //
        if ($row_count == 40) {
            echo "  <tr style=\"page-break-before:always;\"></tr>\n";
            $row_count = 0;
            $page_count++;
        }
    }
    echo "</table>\n";
    echo '</div>'; //box-body
    echo '</div>'; //box-info
    echo '</div>'; //window
    echo '</div>'; //row
    //echo "</div>"; //div responsive
}
exit;
?>
