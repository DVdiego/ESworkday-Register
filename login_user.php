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
 session_start();

 include 'config.inc.php';
 include 'header.php';
 include 'theme/templates/mainstart.inc';
 include 'topmain.php';
 //include './theme/templates/leftnavstart.inc';
 //include './theme/templates/leftnavend.inc';
 include './theme/templates/beginmaincontent.inc';


 if (($use_reports_password == "yes") && ($_REQUEST["login_action"] == "user")) { // Determine if the user is trying to log-in to reports
     echo "
       <!-- Reports Login Interface -->
       <title>
          $title - User Login
       </title>";

     $self = $_SERVER['PHP_SELF'];

     // Determine if the user has entered his authentication credentials
     if (isset($_POST['login_userid']) && (isset($_POST['login_password']))) {
         $login_userid = $_POST['login_userid'];
         $login_password = $_POST['login_password'];

         // Determine if the user has report access rights.
         $query = "select empfullname, employee_passwd, reports, `profile` from ".$db_prefix."employees where empfullname = '".$login_userid."'";
         $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

         while ($row = mysqli_fetch_array($result)) {
             $reports_username = "".$row['empfullname']."";
             $reports_password = "".$row['employee_passwd']."";
             $reports_auth = "".$row['reports']."";
             $profile_auth = "".$row['profile']."";
         }

         // Determine if the user is authorised to view reports
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
                  window.location.href = './profile/user_profile.php';
               </script>";
         exit;
     } else if (isset($_SESSION['valid_report_employee'])) {
         echo "
               <script type='text/javascript' language='javascript'>
                  window.location.href = 'timeclock.php';
               </script>";
         exit;
     } else if ((isset($_SESSION['valid_user'])) || (isset($_SESSION['time_admin_valid_user']))) {
         echo "
       <br>You do not have report access permission.";
         exit;
     } else { // The user is either not valid or has not entered in his credentials.

 	    echo '<div class="col-md-12"><div class="login-box">
               <div class="login-logo">
                 <a href="index.php"><b>PHP TIMECLOCK <i class="fa fa-clock-o"></i></b>USER Login</a>
               </div><!-- /.login-logo -->
               <div class="login-box-body">


                 <form name="auth" method="post" action=" '.$self.' ">
                   <div class="form-group has-feedback">
                     <input type="text" class="form-control" name="login_userid" placeholder="Username">
                     <span class="glyphicon glyphicon-user form-control-feedback"></span>
                   </div>
                   <div class="form-group has-feedback">
                     <input type="password" name="login_password" class="form-control" placeholder="Password">
                     <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                   </div>
                   <div class="row">
                     <div class="col-xs-4">
             	  <button type="submit" class="btn btn-primary btn-block btn-flat" onClick="admin.php">Log In</button>
             	   <input type="hidden" name="login_action" value="reports">
                     </div>
                     <!-- /.col -->
                   </div>
                 </form>

               </div><!-- /.login-box-body -->
             </div><!-- /.login-box -->

           </div>';
         // Determine if the user has supplied incorrect credentials.
         if (isset($login_userid)) {

             echo "Could not log you in. Either your username or password is incorrect.";
         }
         echo "<script language=\"javascript\">
                 document.forms['auth'].login_userid.focus();
               </script>";
     }

   }
 include 'footer.php';
 include 'theme/templates/endmain.inc';
 include 'theme/templates/footerscripts.inc';
?>
