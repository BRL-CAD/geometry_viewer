<?
/*             C O N F I G - V A L I D A T E . P H P
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
/** @file geometry_viewer/config-validate.php
 *
 */

    /** Array of errors */
    $errorArray = array();
	
    /** If mged path is empty or the file does not exist at path specified, 
    give an error **/	
    if (($mgedPath == '') || (!file_exists($mgedPath))) {
        $errorArray[] = 'Your path to mged is either empty or wrong';
    }

    /** If gobj path is empty or the file does not exist at path specified,
    give an error **/
    if (($gobjPath == '') || (!file_exists($gobjPath))) {
        $errorArray[] = 'Your path to g-obj is either empty or wrong';
    } 

    /** If there are any errors in configuration files, show them */
    if (!empty($errorArray) && isset($username)) { ?>
        <div class="alert alert-danger" id="config-error-alert">
            <p> Your configuration file has following errors: "
                 
	    <?php foreach ($errorArray as $error) {
                echo '<p>'.$error.'</p>';
            } ?>
                
    	    <a href=<?php echo $siteUrl.'/geometry_viewer/config-edit.php'; ?> class="btn btn-danger">
                Edit Configuration      
            </a>
        </div>
    <?php } 
/*                                                                    
 * Local Variables:                                                   
 * mode: PHP                                                            
 * tab-width: 8
 * End:                                                               
 * ex: shiftwidth=4 tabstop=8                                         
 */
?>
