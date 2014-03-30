<?php
/*                         L O G I N . P H P
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
/** @file geometry_viewer/accounts/login.php
 *
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<?php 

    include 'include/header.php'; 
    include 'include/db.php';

    /** Start session. */
    session_start();
    
   
    /** Check if the form has been submitted. */
    if(isset($_POST['sign_in'])){

        /** Prevent mysql injection. */

        $password = md5(mysql_real_escape_string($_POST['password']));
        $email = mysql_real_escape_string($_POST['email']);

        /** 
         * Validation.
         * TODO: Need more secure way to implement 
         * sign-in / sign-up feature.
         */
        if (empty($password)) { 
            $action['result'] = 'error'; 
        }

        if (empty($email)) { 
            $action['result'] = 'error'; 
        }

        if ($action['result'] != 'error') {		
            $result = mysql_query("SELECT * FROM `users` WHERE 
            (email='$email' AND password='$password')")  
            or die(mysql_error());

            if(!isset($result)) {
                echo "<div class=\"alert alert-danger \"> query failed </div>";
            }

            if (mysql_num_rows($result) > 0) {
                session_regenerate_id();
                $member = mysql_fetch_assoc($result);
                $_SESSION['id'] = $member['id'];
                $_SESSION['username'] = $member['username'];
                $_SESSION['email'] = $member['email'];
                session_write_close();
                header("Location: ../upload.php");
                exit();
            } else {
                /** Login failed. */
                echo "<div id=\"alert-msge\" class=\"alert alert-danger\">
                    email ID and / or password not found.
                    </div>";


            }
        }
    }
?>
        
    <h3 style="text-align: center">Sign In</h3>
    
    <?php /** Login form. */  ?>
    <form method="post" action="" class="form-horizontal" role="form" 
    id="login-form">
        <fieldset>
            <div class="form-group">
                <label class="col-lg-1 control-label" for="email">Email:</label>
                <div class="col-lg-10">
                    <input type="email" class="form-control" id ="email" 
                    name="email" style="width: 250px;" required>
                </div>
            </div>
	    
            <div class="form-group">
                <label class="col-lg-1 control-label" for="password">Password:</label>
                <div class="col-lg-10">
                    <input type="password" class="form-control" 
                    id ="password" name="password" style="width: 250px;" required>
                </div>
            </div>
    		        
            <div>
    	        <input type="submit" value="Sign in" class="btn btn-primary" 
                name="sign_in" style="width: 322px;"/>			
            </div>

            <div><br> 
                <a href="forgotpassword.php">Forgotten your password?</a><br><br>
                <a href="signup.php">New user? Create an account.</a>
            </div>

            
        </fieldset>    
    </form></body>
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
