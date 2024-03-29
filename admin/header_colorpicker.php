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
 * This module will add the standard headers and checks.
 */

include '../functions.php';

// grab the connecting ip address for the audit log. if more than 1 ip address is returned, accept the first ip and discard the rest. //
$connecting_ip = get_ipaddress();
if (empty($connecting_ip)) {
    return FALSE;
}

// determine if connecting ip address is allowed to connect to WorkTime Control, we need to find a way to do this for hostnames//
if ($restrict_ips == "yes") {
    for ($x=0; $x < count($allowed_networks); $x++) {
        $is_allowed = ip_range($allowed_networks[$x], $connecting_ip);
        if (! empty($is_allowed)) {
            $allowed = TRUE;
        }
    }
    if (!isset($allowed)) {
        echo "<html> You are not authorized to view this page.";
        exit;
    }
}

// check for correct db version //
if ($use_persistent_connection == "yes") {
    @ $db = ($GLOBALS["___mysqli_ston"] = mysqli_connect($db_hostname,  $db_username,  $db_password));
} else {
    @ $db = ($GLOBALS["___mysqli_ston"] = mysqli_connect($db_hostname,  $db_username,  $db_password));
}
if (! $db) {
    echo "<html> Error: Could not connect to the database. Please try again later.";
    exit;
}
((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE " . $db_name));

$table = "dbversion";
$result = mysqli_query($GLOBALS["___mysqli_ston"], "SHOW TABLES LIKE '".$db_prefix.$table."'");
@$rows = mysqli_num_rows($result);
if ($rows == "1") {
    $dbexists = "1";
} else {
    $dbexists = "0";
}

$db_version_result = mysqli_query($GLOBALS["___mysqli_ston"], "select * from ".$db_prefix."dbversion");
while (@$row = mysqli_fetch_array($db_version_result)) {
    @$my_dbversion = "".$row["dbversion"]."";
}

// include css and timezone offset//
if (($use_client_tz == "yes") && ($use_server_tz == "yes")) {
    $use_client_tz = '$use_client_tz';
    $use_server_tz = '$use_server_tz';
    echo "<html>
   Please reconfigure your config.inc.php file, you cannot have both $use_client_tz AND $use_server_tz set to 'yes'";
    exit;
}

if (empty($creating_backup_file)) { // This allows the database backup code to create a dynamic backup file.
    echo "<html>
   <head>";
    if ($use_client_tz == "yes") {
        if (! isset($_COOKIE['tzoffset'])) {
            include '../tzoffset.php';
            echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
        }
    }
    echo "
    	<title>$title</title>";
          include '../theme/templates/adminheader.inc';
          echo "<link rel='stylesheet' type='text/css' href='../css/styles.css' />\n";
    echo "<link rel='stylesheet' type='text/css' media='print' href='../css/print.css'/>\n";
    echo "<script language=\"javascript\" src=\"../scripts/ColorPicker2.js\"></script>\n";
    echo "<script language=\"javascript\">var cp = new ColorPicker();</script>\n";
    echo "<script language=\"javascript\" src=\"../scripts/pnguin.js\"></script>\n";

    echo "</head>\n";
    if ($use_client_tz == "yes") {
        if (isset($_COOKIE['tzoffset'])) {
            $tzo = $_COOKIE['tzoffset'];
            settype($tzo, "integer");
            $tzo = $tzo * 60;
        }
    } elseif ($use_server_tz == "yes") {
        $tzo = date('Z');
    } else {
        $tzo = "1";
    }
include '../theme/templates/mainstart.inc';
    echo "

";
}
?>
