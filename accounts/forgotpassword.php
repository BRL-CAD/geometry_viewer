<?php
/*              F O R G O T _ P A S S W O R D . P H P
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
/** @file geometry_viewer/accounts/forgot_password.php
 *
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<?php 
    include_once('include/header.php');
    require_once('include/db.php');
    require_once('../config.php');

    /** Swift Mailer Library. */
    require_once('include/swift/swift_required.php');

    if(!isset($_GET['email'])) {
        echo "<br>";
        echo'<h3 style="text-align: center">Reset Your Password</h3>';

        echo'<form action="forgotpassword.php" class="form-horizontal" 
            role="form" id="login-form">
                <fieldset>
                    <div class="form-group">
                        <label class="col-lg-1 control-label" 
                        for="email">Email</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" 
                            id ="email"  name="email" style="width: 250px;" required>
                        </div>
                    </div>
                    <div>
                        <input type="submit" value="Reset My Password"  
                        class="btn btn-primary" name="sign_in" 
                        style="width: 322px;"/>			
                    </div>
                </fieldset>
            </form>'; 
        exit();
    }

    $email = $_GET['email'];
    $searchEmailID = "select email from users where email = '".$email."'";
    $res = mysql_query($searchEmailID);
    $rows = mysql_num_rows($res);
    if($rows == 0) {
        echo "<div id=\"alert-msge\" class=\"alert alert-danger\">
                Email id is not registered.
            </div>"; 
        die();    
    }

    /** Create random key. */
    $date = date_create();
    $key = $email . date_format($date, 'Y-m-d H:i:s') . "\n";
    $token = md5($key);
    $searchEmailID = "insert into tokens (token,email) values ('".$token."','".$email."')";
    mysql_query($searchEmailID);

    /** 
     * Mail Transport 
     * TODO: This code should be in separate file of function.
     */
    $transport = Swift_SmtpTransport::newInstance('ssl://smtp.gmail.com', 465)
    ->setUsername($senderEmail)
    ->setPassword($senderPassword);

    /** Mailer */
    $mailer = Swift_Mailer::newInstance($transport);

    /** Create a message */
    $message = Swift_Message::newInstance($passwordResetSubject)
    ->setFrom(array($senderEmail => $senderName))
    ->setTo(array($email => $email))
    ->setBody('Hi '.$email.'!
    <br><br>Please click the following link to reset your password:<br><br>
    <a href="'.$siteUrl.'geometry_viewer/accounts/reset.php?token='.$token.'">'.$siteUrl.'geometry_viewer/accounts/reset.php?token='.$token.' </a><br><br>Have a nice day!', 'text/html');

    /** Send email */
    if ($mailer->send($message)) {			
	echo "<div id=\"alert-msge\" class=\"alert alert-success\"> 
                 Password reset link has been sent your email address. 
            </div>";	
    } else {
        echo "<div id=\"alert-msge\" class=\"alert alert-danger\"> 
                 Error occurred. Unable to send password reset link.
            </div>";
    }

/*                                                                    
 * Local Variables:                                                   
 * mode: PHP                                                            
 * tab-width: 8
 * End:                                                               
 * ex: shiftwidth=4 tabstop=8                                         
 */
