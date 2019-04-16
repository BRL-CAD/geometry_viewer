<?php
/*                    F U N C T I O N S . P H P
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
/** @file geometry_viewer/functions.php
 *
 */

    require_once('variables.php');
    require_once('config.php');

    /** 
     * D I S P L A Y _ L I S T
     *
     * Display list of entities on browser. 
     */
    function display_list($dbFileName, $uploadPath, $totalEntities, $list)
    {
        echo "total entities in $dbFileName: $totalEntities</br>";
        for ($i = 0; $i < $totalEntities + 1; $i++) {
            if ($list[$i] == "_GLOBAL") {
                $i = $i + 1;
                $totalEntties = $totalEntities + 1;
                echo "$i ". $list[$i]. "<br>";
            } else {
                echo "$i ". $list[$i]. "<br>";
            }
        }
    }  


    /**
     * C R E A T E _ O B J
     * 
     * Create OBJ files.  
     */
    function create_obj($dbFileName, $entity, $uploadPath, $objPath, $gobjPath, $normTol)
    {
        $success = "$entity.obj";
        $fail = "fail";
        $gobj = "$gobjPath -n $normTol -o $objPath/$entity.obj $uploadPath/$dbFileName $entity";
        if (!shell_exec($gobj)) {
            echo $fail;
        } else {
            echo $success;
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
