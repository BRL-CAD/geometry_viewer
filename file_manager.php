<?php
/*               F I L E _ M A N A G E R . P H P
 *
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
/** @file geometry_viewer/file_manager.php
 *
 */
 require_once 'accounts/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php
 include_once 'header.php';

    /** Open the directory that contains files */
    if ($handle = opendir('./user_accounts/'.$username.'/')) {
        
/** Read all the files that have extension .g */
	while (false !== ($file = readdir($handle))){
	    if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'g'){
		$thelist .= '<form method="post" action="upload_file.php" class="fm-form"> 
			<input type="hidden" name="dbfilename" id="dbfilename" value="'.$file.'"/>
			<input type="hidden" name="fromFileManager" id="fromFileManager" value="true" />
			<input type="submit" class="btn btn-info" id="file-name" value="'.$file.'"/>
		     </form>';
	    }
	}

	closedir($handle);
    }
?>

<style>html{overflow:auto;}</style>

    <body>
	<div id="files-wrapper">
	    <?php echo $thelist; ?>
	</div>
    </body>

</html>

<?php
/* 
 * Local Variables
 * mode: PHP 
 * tab-width: 8
 * End: 
 * ex: shiftwidth=4 tabstop=8
 */
?>
