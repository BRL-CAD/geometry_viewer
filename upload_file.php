<?php
/*                 U P L O A D _ F I L E . P H P
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
/** @file geometry_viewer/upload_file.php
 *
 */
    include 'accounts/auth.php';
    include 'functions.php';
    include 'config.php';

    /** 
     * This variable hold 1, if upload completes, 2 if 
     * file already exists, otherwise 0. 
     */
    $uploadComplete = 0;

    if ($_FILES["file"]["error"] > 0) {
        echo "Error: " . $_FILES["file"]["error"] . "<br>";
        $uploadComplete = 0;
    } else if (file_exists("$uploadPath/" . $_FILES["file"]["name"])) {
        $uploadComplete = 2;
    } else {
        move_uploaded_file($_FILES["file"]["tmp_name"],
        "$uploadPath/" . $_FILES["file"]["name"]);
        $uploadComplete = 1;
    }

    /** 
     * Holds the names of entities that to be displayed. variable is 
     * passed to WebGL through header. 
     */
    $redirectionData = NULL;
    $objFileName = NULL;

    /** Holds the name of uploaded database file. */
    $dbFileName = $_FILES["file"]["name"];

    /** 
     * Database file name is splited into its components, i.e. file 
     * name and extension and stored in array. 
     */
    $dbNameComponents = explode(".", $dbFileName);
    
    /** Copy the name of file into variable. */
    $dbFilePreffix = $dbNameComponents[0];

    /** 
     * Command that lists the entities of a BRL-CAD database file is 
     * copied into a variable. 
     */
    $cmd = "env $mgedPath -c $uploadPath/$dbFileName ls -a 2>&1";

    /** Output of command is stored into variable as string. */
    $out = shell_exec($cmd);

    /** 
     * Split stirng and storing names of entities 
     * into array. 
     */
    $list = explode(" ", $out);
    
    /** Count total number of entities. */
    $totalEntities = count($list) - 2;
    
    /** Number of entities to be displayed directly after upload. */
    if ($totalEntities < 5) {
	$n = $totalEntities;
    } else {
        $n = 5;
    }
     
    /** 
     * If file already exits, upload fails and models of entities 
     * of pre existing file are displayed. 
     */
    if ($uploadComplete == '2') {
        for ($i = 0; $i < $n; $i++) {
            if ($list[$i] == "_GLOBAL") {
                $i = $i + 1;
                $n = $n + 1;

                /** This code has chances to be removed in future. */
	        $objFileName = $objPath.$dbFilePreffix."_".$list[$i].".obj";
	        $redirectionData = $redirectionData."|".$objFileName;
	    } else {
	        $objFileName = $objPath.$dbFilePreffix."_".$list[$i].".obj";    
	        $redirectionData = $redirectionData."|".$objFileName;
	    }
        }
        header('Location: model_display.php?entitiesString='.urlencode($out).'&dbFileName='.urlencode($dbFileName));
    } else if ($uploadComplete == '1') {
        for ($i = 0; $i < $n; $i++) {
            if ($list[$i] == "_GLOBAL") {
                $i = $i + 1;
                $n = $n + 1;
	        $objFileName = $objPath.$dbFilePreffix."_".$list[$i].".obj";
	        $redirectionData = $redirectionData."|".$objFileName;
	    } else {
	        $objFileName = $objPath.$dbFilePreffix."_".$list[$i].".obj";    
	        $redirectionData = $redirectionData."|".$objFileName;
	    }       
        }
        header('Location: model_display.php?entitiesString='.urlencode($out).'&dbFileName='.urlencode($dbFileName));
    }

/*                                                                    
 * Local Variables:                                                   
 * mode: PHP                                                            
 * tab-width: 8
 * End:                                                               
 * ex: shiftwidth=4 tabstop=8                                         
 */
?>
