<script language="JavaScript">
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

function office_names() {

var select = document.form.office_name;
select.options[0] = new Option("all");
select.options[0].value = 'all';

<?php

//@$office_name = $post_office_name;
@$office_name = $_POST['office_name'];;

$query = "select * from ".$db_prefix."offices order by officename asc";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

$cnt=1;
while ($row=mysqli_fetch_array($result)) {
  if ("".$row['officename']."" == stripslashes($office_name)) {
  echo "select.options[$cnt] = new Option(\"".$row['officename']."\",\"".$row['officename']."\", true, true);\n";
  } else {
  echo "select.options[$cnt] = new Option(\"".$row['officename']."\");\n";
  echo "select.options[$cnt].value = \"".$row['officename']."\";\n";
  }
  $cnt++;
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
?>
}

function group_names() {
var offices_select = document.form.office_name;
var groups_select = document.form.group_name;
groups_select.options[0] = new Option("all");
groups_select.options[0].value = 'all';

if (offices_select.options[offices_select.selectedIndex].value != 'all') {
  groups_select.length = 0;
}

<?php

$query = "select * from ".$db_prefix."offices order by officename asc";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {
$office_row = addslashes("".$row['officename']."");
?>

if (offices_select.options[offices_select.selectedIndex].text == "<?php echo $office_row; ?>") {
<?php
$query2 = "select * from ".$db_prefix."offices, ".$db_prefix."groups where ".$db_prefix."groups.officeid = ".$db_prefix."offices.officeid
           and ".$db_prefix."offices.officename = '".$office_row."'
           order by ".$db_prefix."groups.groupname asc";
$result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
echo "groups_select.options[0] = new Option(\"all\");\n";
echo "groups_select.options[0].value = 'all';\n";
$cnt = 1;

while ($row2=mysqli_fetch_array($result2)) {
  $groups = "".$row2['groupname']."";
  echo "groups_select.options[$cnt] = new Option(\"$groups\");\n";
  echo "groups_select.options[$cnt].value = \"$groups\";\n";
  $cnt++;
}

?>
}
<?php
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
((mysqli_free_result($result2) || (is_object($result2) && (get_class($result2) == "mysqli_result"))) ? true : false);
?>

if (groups_select.options[groups_select.selectedIndex].value != 'all') {
  groups_select.length = 0;
}
if (offices_select.options[offices_select.selectedIndex].value == 'all') {

<?php

echo "groups_select.options[0] = new Option(\"all\");\n";
echo "groups_select.options[0].value = 'all';\n";

$query3 = "select * from ".$db_prefix."groups order by groupname asc";
$result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);

$cnt=1;
while ($row3=mysqli_fetch_array($result3)) {
  if ("".$row3['groupname']."" == stripslashes($display_group)) {
  echo "groups_select.options[$cnt] = new Option(\"".$row3['groupname']."\",\"".$row3['groupname']."\", true, true);\n";
  } else {
  echo "groups_select.options[$cnt] = new Option(\"".$row3['groupname']."\");\n";
  echo "groups_select.options[$cnt].value = \"".$row3['groupname']."\";\n";
  }
  $cnt++;
}
((mysqli_free_result($result3) || (is_object($result3) && (get_class($result3) == "mysqli_result"))) ? true : false);
?>
}
}

</script>
