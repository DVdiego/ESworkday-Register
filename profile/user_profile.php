<?php
session_start();

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
$current_page = "user_profile.php";

include '../config.inc.php';

// Determine who is wishing to see the report and has authenticated himself
if (! isset($_SESSION['valid_profile_user'])) {
    include '../admin/header.php';
    //     include '../admin/topmain.php';
    include 'topmain.php';
    include 'leftmain.php';

    echo "
      <!-- Invalid Employee -->
      <title>
         $title
      </title>

      <table width=100% border=0 cellpadding=7 cellspacing=1>
         <tr class=right_main_text>
            <td height=10 align=center valign=top scope=row class=title_underline>
               PHP Timeclock Reports
            </td>
         </tr>
         <tr class=right_main_text>
            <td align=center valign=top scope=row>
               <table width=200 border=0 cellpadding=5 cellspacing=0>
                  <tr class=right_main_text>
                     <td align=center>
                        You are not presently logged in, or do not have permission to view this page.
                     </td>
                  </tr>
                  <tr class=right_main_text>
                     <td align=center>
                        Click
                        <a class=admin_headings href='../login.php?login_action=reports'>
                           <u>here</u>
                        </a> to login.
                     </td>
                  </tr>
               </table>
            </td>
         </tr>
      </table>
   </body>
</html>";
    exit;
}

  echo "<i>USER PROFILE\n";

?>
