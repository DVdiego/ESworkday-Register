<?php

 session_start();

 include 'config.inc.php';
 include 'header.php';
 include 'theme/templates/mainstart.inc';
 include 'topmain.php';
 //include './theme/templates/leftnavstart.inc';
 //include './theme/templates/leftnavend.inc';
 include './theme/templates/beginmaincontent.inc';


 //if (($use_reports_password == "yes") && ($_REQUEST["login_action"] == "user")) { // Determine if the user is trying to log-in to reports


     echo " <!-- Reports Login Interface -->
             <title>
                $title - Login de Usuario
             </title>";

     $self = $_SERVER['PHP_SELF'];


     // Determine if the user has entered his authentication credentials
     if (isset($_POST['login_userid']) && (isset($_POST['login_password']))) {

         $login_userid = mysqli_real_escape_string($GLOBALS["___mysqli_ston"] , $_POST['login_userid']);
         $login_password = mysqli_real_escape_string($GLOBALS["___mysqli_ston"] , $_POST['login_password']);



        if($login_with_fullname == "yes"){

          $query = "select empfullname from ".$db_prefix."employees where empfullname = '".$login_userid."'";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

          while ($row = mysqli_fetch_array($result)) {
              $login_userid = "".$row['empfullname']."";

          }

        }elseif ($login_with_displayname == "yes"){

          $query = "select empfullname from ".$db_prefix."employees where displayname = '".$login."'";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

          while ($row = mysqli_fetch_array($result)) {
              $login_userid = "".$row['empfullname']."";

          }
        }elseif ($login_with_dni == "yes"){

          $query = "select empfullname from ".$db_prefix."employees where empDNI = '".$login."'";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

          while ($row = mysqli_fetch_array($result)) {
              $login_userid = "".$row['empfullname']."";

          }
        }
         // Determine if the user has report access rights.

         $query = "select empfullname, employee_passwd, reports, `profile` from ".$db_prefix."employees where empfullname = '".$login_userid."'";
         $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

         while ($row = mysqli_fetch_array($result)) {
             $reports_username = "".$row['empfullname']."";
             $reports_password = "".$row['employee_passwd']."";
             $reports_auth = "".$row['reports']."";
             $profile_auth = "".$row['profile']."";
         }


         // Determine if the user is authorised to view profile
         if (($login_userid == @$reports_username) && (password_verify($login_password, @$reports_password)) && ($profile_auth == "1")) {
             $_SESSION['valid_profile'] = $login_userid;
         } else if (($login_userid == @$reports_username) && (password_verify($login_password, @$reports_password))) { // User can view his own hours
             $_SESSION['valid_report_employee'] = $login_userid;
         }

         // Determine if the user has time or sys access rights.
         $query = "select empfullname, employee_passwd, admin, time_admin from ".$db_prefix."employees where empfullname = '".$login_userid."'";
         $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

         while ($row = mysqli_fetch_array($result)) {
             $admin_username = "".$row['empfullname']."";
             $admin_password = "".$row['employee_passwd']."";
             $admin_auth = "".$row['admin']."";
             $time_admin_auth = "".$row['time_admin']."";
         }

         if (($login_userid == @$admin_username) && (password_verify($login_password, @$admin_password)) && ($admin_auth == "1")) {
             $_SESSION['valid_user'] = $login_userid;
         } elseif (($login_userid == @$admin_username) && (password_verify($login_password, @$admin_password)) && ($time_admin_auth == "1")) {
             $_SESSION['time_admin_valid_user'] = $login_userid;
         }
     }

     // If the user supplied the proper credentials, send them to the proper location
     if (isset($_SESSION['valid_profile'])) {


         echo "
               <script type='text/javascript' language='javascript'>
                  window.location.href = './user/index.php';
               </script>";
         exit;
     } else if (isset($_SESSION['valid_report_employee'])) {

         echo "
               <script type='text/javascript' language='javascript'>
                  window.location.href = 'worktime.php';
               </script>";
         exit;
     } else if ((isset($_SESSION['valid_profile'])) || (isset($_SESSION['time_admin_valid_user']))) {
         echo "
       <br>You do not have report access permission.";
         exit;
     } else { // The user is either not valid or has not entered in his credentials.

       // if($login_with_fullname =="yes"){
       //   $name = "Nombre de Usuario";
       //
       // }elseif ($login_with_displayname == "yes") {
       //   $name = "Nombre de Acceso";
       // }elseif ($login_with_dni == "yes") {
       //
       //   $name = "DNI";
       // }

 	    echo '<div class="col-md-12"><div class="login-box">';
      echo '   <div class="login-logo">
                 <a href="index.php"><b>WorkTime <i class="fa fa-clock-o"></i></b> Login de Usuario</a>
               </div><!-- /.login-logo -->
               <div class="login-box-body">


                 <form name="auth" method="POST" action=" '.$self.' ">
                   <div class="form-group has-feedback">
                     <input type="text" class="form-control" name="login_userid" placeholder="ID Acceso">
                     <span class="glyphicon glyphicon-user form-control-feedback"></span>
                   </div>
                   <div class="form-group has-feedback">
                     <input type="password" name="login_password" class="form-control" placeholder="Password">
                     <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                   </div>
                   <div class="row">
                     <div class="col-xs-4">
             	  <button type="submit" id=button-primary class="btn btn-primary btn-block btn-flat" onClick="admin.php">Log In</button>
             	   <input type="hidden" name="login_action" value="reports">
                     </div>
                     <!-- /.col -->
                   </div>
                 </form>

               </div><!-- /.login-box-body -->
             </div><!-- /.login-box -->';
             // Determine if the user has supplied incorrect credentials.
             if (isset($login_userid)) {
               echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-warning"></i>Error!</h4>
                              Error en el acceso, el Id de acceso o la contraseña no son correctos.
                          </div></div>';
             }

         echo '  </div>';

         echo "<script language=\"javascript\">
                 document.forms['auth'].login_userid.focus();
               </script>";
     }

   //}
 include 'footer.php';
 include 'theme/templates/endmain.inc';
 include 'theme/templates/footerscripts.inc';
?>
<style type="text/css">
#wrapper > div {


      margin-left: 0px !important;
      background-color: #ffffff !important;
}

</style>
