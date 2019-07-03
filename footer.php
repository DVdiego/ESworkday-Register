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
 * This module will display the technology information about WorkTime Control.
 * This module will also add the ending HTML tags to make it valid HTML.
 */

echo '
        </div>
        <!-- /.content-wrapper -->

	<!-- Main Footer -->
  <footer class="main-footer" style="margin-left: 0;">
    <!-- To the right -->
    <div class="pull-right hidden-xs" style="padding-left:20px;">';


// Determine if we should add the contact E-mail to the footer
if (! empty($email) && ($email != "none")) {
    echo "
               <a class=footer_links href='mailto:$email'> Contact Management </a> &nbsp; &#8226;";
}

// Determine if the application information is set
if (empty($company_name) || empty($app_version)) {
    echo "
               Powered by <a class=footer_links href='http://isoftsolutions.es' target='_blank'> iSoftSolutions</a>";
} else {
    echo "
               $date &copy; $company_name is powered by <a class=footer_links href='http://isoftsolutions.es' target='_blank'>  iSoftSolutions v$app_version</a>";
}

echo '
	</div>

  <div class="pull-right">
<!-- Default to the left -->
<i class="fa fa-envelope"></i> Soporte <a class="footer_links" href="mailto:contacto@isoftsolutions.es" target="_blank" >contacto@isoftsolutions.es</a>
  </div>
 </footer>
';

// Finish up the HTML to make it valid

?>
