<?php
/*                     L A N D I N G . P H P
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
/** @file geometry_viewer/accounts/landing.php
 *
 */

require_once ('../config.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <title><?php echo $title; ?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<meta name="copyright" content="" />

        <link href="../css/bootstrap.min.css" rel="stylesheet" 
        type="text/css" media="screen" />
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery-1.10.2.min.js"></script>
        
        <style>
            body {
                background: #F2F2F2 url(../css/images/Display04.png) no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover; 
          }

            #left {
                float:left;
                width: 250px; 
                height: 50px;
            }

            #right {
                float:left;
                margin-left: 10px;
                width: 250px; 
                height: 50px;
            }

            #main-buttons-div {
                position: fixed;
                top: 50%;
                left: 50%;
                margin-top: -50px;
                margin-left: -250px;
            }
        
            #heading {
                position: fixed;
                top: 50%;
                left: 47%;
                margin-top: -100px;
                margin-left: -250px;
            }
        </style>
    </head>

    <body>
        <h1 class="text-primary" id="heading"><?php echo $title; ?></h1>
        
        <div id="main-buttons-div"> 
        <button type="submit" class="btn btn-primary" id="left" 
        name="signup" onClick="window.location='signup.php'">
        Signup Now</button>			
        
        <button type="submit" class="btn btn-success" id="right" 
        name="sign_in" onClick="window.location='login.php'">
        Sign In </button>			
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
