<?php
/*                     H E A D E R . P H P
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
/** @file geometry_viewer/accounts/include/header.php
 *
 */

    require_once('../config.php');
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

		<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
                <link href="include/style.css" rel="stylesheet" type="text/css"/>
                <link href="../css/pace/themes/pace-theme-minimal.css" rel="stylesheet" />
                <script src="../js/pace.js"></script>                              
                <script src="../js/bootstrap.min.js"></script>
                <script src="../js/jquery-1.10.2.min.js"></script>

	</head>

	<body>
		<div id="top-bar" class="effect8">
                    <div><a href="../upload.php"><img id="top-bar-logo" src="../images/BRL-CAD_gear_logo_256.png"></a></div>
                    <div id="logo-text">BRL-CAD</div>
                    <div id="slogan">Geometry Viewer</div>
                </div>
		
