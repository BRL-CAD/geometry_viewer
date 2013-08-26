<?php
    include 'functions.php';

    /** 
     * Following variable will hold 1, if upload completes, 2  if 
     * file already exists, otherwise 0. 
     */
    $uploadComplete = 0;

    if ($_FILES["file"]["error"] > 0) {
        echo "Error: " . $_FILES["file"]["error"] . "<br>";
        $uploadComplete = 0;
    } else if (file_exists("upload/" . $_FILES["file"]["name"])) {
    $uploadComplete = 2;
    } else {
        move_uploaded_file($_FILES["file"]["tmp_name"],
        "upload/" . $_FILES["file"]["name"]);
        $uploadComplete = 1;
    }

    /** 
     * Holds the names of entities that to be displayed. variable is 
     * passed to WebGL through header 
     */
    $redirectionData = NULL;
    $objFileName = NULL;

    /** Holds the name of uploaded database file */
    $dbFileName = $_FILES["file"]["name"];

    /** 
     * Database file name is splited into its components, i.e. file 
     * name and extension and stored in array 
     */
    $dbNameComponents = explode(".", $dbFileName);
    
    /** Copying the name of file into variable */
    $dbFilePreffix = $dbNameComponents[0];
     
    /** 
     * Command that lists the entities of a BRL-CAD database file is 
     * copied into a variable 
     */
    $cmd = "env /usr/brlcad/dev-7.24.1/bin/mged -c $uploadPath$dbFileName ls -a 2>&1";

    /** Output of command is stored into variable as string */
    $out = shell_exec($cmd);
    
    /** 
     * Spliting the stirng and storing names of entities 
     * into array 
     */
    $list = explode(" ", $out);
    
    $serializedList = urlencode(serialize($list));
    
    /** Counting total number of entities */
    $totalEntities = count($list) - 2;
    
    /** Number of entities to be displayed directly after upload */
    if ($totalEntities < 5) {
	$n = $totalEntities;
    } else {
        $n = 5;
    }
     
    /** 
     * If file already exits, upload fails and models of entities 
     * of pre existing file are displayed 
     */
    if ($uploadComplete == '2') {
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
        header('Location: model_display.php?obj='.$redirectionData);
    } else if ($uploadComplete == '1') {
        for ($i = 0; $i < $n; $i++) {
            if ($list[$i] == "_GLOBAL") {
                $i = $i + 1;
                $n = $n + 1;
                create_obj($dbFileName, $dbFilePreffix, $list[$i], $uploadPath, $objPath);
	        $objFileName = $objPath.$dbFilePreffix."_".$list[$i].".obj";
	        $redirectionData = $redirectionData."|".$objFileName;
	    } else {
                create_obj($dbFileName, $dbFilePreffix, $list[$i], $uploadPath, $objPath);
	        $objFileName = $objPath.$dbFilePreffix."_".$list[$i].".obj";    
	        $redirectionData = $redirectionData."|".$objFileName;
	    }       
        }
        header('Location: model_display.php?obj='.$redirectionData);
    }
?>
