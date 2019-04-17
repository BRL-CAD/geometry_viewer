<?php
/*                      C O N F I R M. P H P
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
/** @file geometry_viewer/accounts/confirm.php
 *
 */

    include 'include/db.php';
    include 'include/header.php';
    $status = NULL;

    /** Check if the $_GET variables are present. */
    if (empty($_GET['email']) || empty($_GET['key'])) {
	$status = 'error';
        echo " <div class\"alert alert-danger\"> We are missing variables. Please double check your email.</div>";
    }
		
    if ($status != 'error') {

        /** Cleanup the variables. */
	$email = mysql_real_escape_string($_GET['email']);
	$key = mysql_real_escape_string($_GET['key']);
	$username = mysql_real_escape_string($_GET['username']);

        /** Check if the key is in the database. */
        $check_key = mysql_query("SELECT * FROM `confirm` WHERE 
        `email` = '$email' AND `key` = '$key' LIMIT 1") 
        or die(mysql_error());
	
	if (mysql_num_rows($check_key) != 0) {
				
            /** Get the results of query. */
	    $confirm_info = mysql_fetch_assoc($check_key);
		
            /** Confirm the email and update users database. */
            $update_users = mysql_query("UPDATE `users` SET 
            `active` = 1 WHERE `id` = '$confirm_info[userid]' 
            LIMIT 1") or die(mysql_error());

            /** Delete the confirm row. */
            $delete = mysql_query("DELETE FROM `confirm` WHERE 
            `id` = '$confirm_info[id]' LIMIT 1") or die(mysql_error());
		
	    if ($update_users) {				
                echo "<div id=\"alert-msge\" class=\"alert alert-success\">
                    Email address <b>$email</b> has been confirmed. Click following <b>Sign In</b> button to login. Thank-You!
                    </div>";

                /** Create user directory after confirmation. */
                $createUserDir = "mkdir ../user_accounts/$username";
                $createObjDir = "mkdir ../user_accounts/$username/obj";
                shell_exec($createUserDir);
                shell_exec($createObjDir);
                
            echo "<button type=\"submit\" class=\"btn btn-success\" id=\"alert-msge\" 
            name=\"sign_in\" onClick=\"window.location='login.php'\">
            Sign In </button>";

	    } else {
                echo "<div id=\"alert-msge\" class=\"alert alert-danger\">
                    The user could not be updated Reason: ".mysql_error();
                echo "</div>";
	    }
	
	} else {
            echo "<div id=\"alert-msge\" class=\"alert alert-danger\">
                <h5>The key and email is not in our database.</h5>
                </div>";
	}

    }    

/*                                                                    
 * Local Variables:                                                   
 * mode: PHP                                                            
 * tab-width: 8
 * End:                                                               
 * ex: shiftwidth=4 tabstop=8                                         
 */
?>
