<?php
/*                       R E S E T . P H P
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
/** @file geometry_viewer/accounts/reset.php
 *
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<?php
    require_once('include/db.php');
    include_once('include/header.php');

    session_start();
    $token = $_GET['token'];

    /** Matching user's token with one in the database. */
    if (!isset($_POST['password'])) {
        $confirmToken = "select email from tokens where token = '".$token."' and used = 0";
        $res = mysql_query($confirmToken);
        while ($row = mysql_fetch_array($res)) {
            $email = $row['email'];
        }
    
        if ($email != '') {
            $_SESSION['email'] = $email;
        } else { 
            die("<div id=\"alert-msge\" class=\"alert alert-danger\">
            Invalid link or Password already changed.
            </div>");
        }
    }

    $password = $_POST['password'];
    $email = $_SESSION['email'];
    
    if (!isset($password)) {
        echo "<br>";
        echo'<h3 style="text-align: center">Reset Your Password</h3>';
        echo'<form method="post" class="form-horizontal" role="form" 
             id="login-form">
                 <fieldset>
                     <div class="form-group">
                         <label class="col-lg-1 control-label" 
                         for="email">Email</label>
                         <div class="col-lg-10">
                             <input type="password" class="form-control" 
                             id =""  name="password" style="width: 250px;" required>
                         </div>
                    </div>
                    <div>
                        <input type="submit" value="Reset My Password"  
                        class="btn btn-primary" name="" style="width: 322px;"/>			
                    </div>
                 </fieldset>
             </form>';
    }

    if (isset($_POST['password'])&&isset($_SESSION['email'])) {
        $updatePasswd = "update users set password = '".md5($password)."' where email = '".$email."'";
        $res = mysql_query($updatePasswd);
        if ($res) {
            mysql_query("update tokens set used = 1 where token = '".$token."'");
            echo "<div id=\"alert-msge\" class=\"alert alert-success\">
                      Password changed successfully.";
            echo "</div>";
            echo "<button type=\"submit\" class=\"btn btn-success\" id=\"alert-msge\" 
            name=\"sign_in\" onClick=\"window.location='login.php'\">
            Sign In </button>";
        } else { 
            echo "<div id=\"alert-msge\" class=\"alert alert-danger\">
                      Password resetting failed due to some error.";
            echo "</div>";
        }
    }
/*                                                                    
 * Local Variables:                                                   
 * mode: PHP                                                            
 * tab-width: 8
 * End:                                                               
 * ex: shiftwidth=4 tabstop=8                                         
 */
