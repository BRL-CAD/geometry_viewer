<?php
/*                         D B . P H P
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
/** @file geometry_viewer/accounts/include/db.php
 *
 */

    require_once('../config.php');

    /** Connect to the database. */
    mysql_connect('localhost', $mysqlUsername, $mysqlPassword) or die("Unable to connect to the database, please make sure your MySQL username and / or password is correct!");
    mysql_select_db($mysqlDatabase) or die("<div id=\"alert-msge\" class=\"alert alert-danger\">Unable to connect to database.</div>");
?>
