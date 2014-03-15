<?php
/*                   S E N D M A I L . P H P
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
/** @file geometry_viewer/accounts/sendmail.php
 *
 */
?>

<head>
    <style>
        body {
            background-color: #F2F2F2;
        }
    </style>
</head>

<?php
    require_once('include/db.php');
    include_once('include/header.php');
    require_once('../config.php');

    /** check if the form has been submitted */
    if (isset($_POST['signup'])) {

        /** prevent mysql injection */
	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	$email = mysql_real_escape_string($_POST['email']);
	
        /** 
         * Validation 
         *
         * TODO: Need more secure way to implement 
         * sign-in / sign-up feature. 
         */
        if (empty($username)) { 
            $status = 'error';
        }

        if (empty($password)) { 
            $status = 'error'; 
        }

        if (empty($email)) { 
            $status = 'error'; 
        }
	
        if ($status != 'error') {
            $query_result = mysql_query("SELECT * FROM `users` WHERE 
            (email='$email' OR username='$username')");

            if (!$query_result) {         
                echo 'Unable to search database to check whether user already exists';                                     
            } else if (mysql_num_rows($query_result) != 0) {
                header('Location: signup.php?userExists=yes');
            } else {
                $password = md5($password);	
			
                /* add to the database */
                $add = mysql_query("INSERT INTO `users` 
                VALUES(NULL,'$username','$password','$email',0)");
		
	        if ($add) {
	            /** Get the new user ID. */
	            $userid = mysql_insert_id();
	
                    /** Create random key. */
	            $key = $username . $email . date('mY');
	            $key = md5($key);
			
                    /** 
                     * Add entry to confirm table and marking it as 
                     * pending / waiting for confirmation. 
                     */
	            $confirm = mysql_query("INSERT INTO `confirm` 
                    VALUES(NULL,'$userid','$key','$email')");	
			
	            if ($confirm) {
                        /** Swift Mailer Library. */
                        require_once 'include/swift/swift_required.php';

                        /** Mail Transport */
                        $transport = Swift_SmtpTransport::newInstance('ssl://smtp.gmail.com', 465)
                        ->setUsername($senderEmail)
                        ->setPassword($senderPassword);

                        /** Mailer */
                        $mailer = Swift_Mailer::newInstance($transport);

                        /** Create a message. */
                        $message = Swift_Message::newInstance($newAccountSubject)
                        ->setFrom(array($senderEmail => $senderName))
                        ->setTo(array($email => $username))
                        ->setBody('
                        Hi '.$username.'!<br><br>
                        Please click the following link to activate your account:<br><br> 
                        <a href="'.$siteUrl.'geometry_viewer/accounts/confirm.php?email='.$email.'&key='.$key.'&username='.$username.'"> '.$siteUrl.'geometry_viewer/accounts/confirm.php?email='.$email.'&key='.$key.'&username='.$username.'</a> <br><br>Have a nice day!', 'text/html');

                        /** Send email. */
        	        if ($mailer->send($message)) {			
                            echo"<div id=\"alert-msge\" class=\"alert alert-success\">
                                Thanks for signing up. Please check your email for confirmation!
                                <div>";	
        	        } else {
        		    echo "Could not send confirm email";
        	        }
                    } else {
	                echo "Confirm row was not added to the database. Reason: " . mysql_error();
	            }
			
                } else {
	            echo "User could not be added to the database. Reason: " . mysql_error();	
                }
	    }
	}
    }

/**                                                                    
 * Local Variables:                                                   
 * mode: PHP                                                            
 * tab-width: 8
 * End:                                                               
 * ex: shiftwidth=4 tabstop=8                                         
 */
?>
