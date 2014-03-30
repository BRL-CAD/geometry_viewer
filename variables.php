<?php
/*                     V A R I A B L E S . P H P
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
/** @file geometry_viewer/variables.php
 *
 */

    /** Path of directory where uploaded file get stored. */
    $uploadPath = "user_accounts/$username";

    /** Path of directory where OBJ files stored. */
    $objPath = "user_accounts/$username/obj";

    /** Giving errors if the paths do not exist **/
    if(!file_exists($uploadPath)){ ?>
        <div class="alert alert-danger">
            Uh Oh! Your account has a problem. There's no folder for your account at our servers. 
        </div><?php 
    }
    else if(!file_exists($objPath)){ ?>
        <div class="alert alert-danger">
            Uh Oh! Your account has a problem. There's no folder for obj files in your account. 
        </div><?php
    } 
/*                                                                    
 * Local Variables:                                                   
 * mode: PHP                                                            
 * tab-width: 8
 * End:                                                               
 * ex: shiftwidth=4 tabstop=8                                         
 */
?>
