<?php

include '../theme/templates/leftnavstart.inc';

//user moved here from topmain
if (isset($_SESSION['valid_user'])) {
$logged_in_user = $_SESSION['valid_user'];
echo '
      <div class="user-panel">
        <div class="pull-left image">
          <h3><i class="fa fa-user-secret text-orange"></i></h3>
        </div>
        <div class="pull-left info">
          <p>'.$logged_in_user.'</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Logged in</a>
        </div>
      </div>';
}

else if (isset($_SESSION['time_admin_valid_user'])) {
    $logged_in_user = $_SESSION['time_admin_valid_user'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user-secret text-red"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Logged in</a>
            </div>
          </div>';

} else if (isset($_SESSION['valid_reports_user'])) {
    $logged_in_user = $_SESSION['valid_reports_user'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user-plus"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Logged in</a>
            </div>
          </div>';
} else if (isset($_SESSION['valid_report_employee'])) {
    $logged_in_user = $_SESSION['valid_report_employee'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Logged in</a>
            </div>
          </div>';
}

// end user moved here from topmain


echo '<ul class="sidebar-menu"><li class="header">Run reports</li>';

echo"<li class='treeview'><a href='index.php'><i class='fa fa-sticky-note-o'></i> <span>Today's report</span></a></li>\n";

echo"<li class='treeview'><a href='timerpt.php'><i class='fa fa-file-text-o'></i> <span>Daily Time Report</span></a></li>\n";

echo "<li class='treeview'><a class=admin_headings href='total_hours.php'><i class='fa fa-file-text-o'></i><span>Hours Worked Report</span></a></li>\n";

echo "<li class='treeview'><a class=admin_headings href='audit.php'><i class='fa fa-history'></i><span>Audit Log<span></a></li>\n";
echo '</ul>';

include '../theme/templates/leftnavend.inc';
include '../theme/templates/beginmaincontent.inc';

?>
