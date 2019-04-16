<?php
/*                 U P L O A D . P H P
 * BRL-CAD
 *
 * Copyright (c) 1995-2013 United States Government as represented by
 * the U.S. Army Research Laboratory.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public License
 * version 2.1 as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this file; see the file named COPYING for more
 * information.
 */
/** @file geometry_viewer/upload.php
 *
 */
    include 'accounts/auth.php';
	include_once('config.php');
//    include 'variables.php';
?>

<!DOCTYPE html>
<html lang="en">

<?php 
    include 'header.php'; 
?>

        <script src="js/bootstrap.min.js"></script>
        <br>
	<div class="text-center">
            <form class="navbar-form" action="upload_file.php" 
            method="post" enctype="multipart/form-data">
                <label for="file">Upload BRl-CAD database(.g) file:
                </label><br>
                <input class="form-control" style="width: 250px;"  
                type="file" name="file" id="file"><br><br>
                <input type="submit" name="submit" value="Submit" 
                class="btn btn-success btn-large">
	    </form>
	</div>
    </body>
</html>

<?php
/*                                                                    
 * Local Variables:                                                   
 * mode: PHP                                                            
 * tab-width: 8
 * End:                                                               
 * ex: shiftwidth=4 tabstop=8                                         
 */
?>
