<?php
/*                M O D E L _ D I S P L A Y . P H P
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
/** @file geometry_viewer/model_display.php
 *
 */

include 'accounts/auth.php';
?>

<!doctype html>
<html lang="en">
    <head>

    <title><?php echo $title; ?></title>
	<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, 
        user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
	<link rel=stylesheet href="css/base.css"/>
        <link rel=stylesheet href="css/bootstrap.css"/>
        <link href="css/pace/themes/pace-theme-minimal.css" rel="stylesheet" />
       
    </head>
    <body>
        <script src="js/pace.js"></script>                              
	<script src="js/three.min.js"></script>
	<script src="js/Detector.js"></script>
	<script src="js/Stats.js"></script>
	<script src="js/loaders/OBJLoader.js"></script>
	<script src="js/OrbitControls.js"></script>
	<script src="js/THREEx.FullScreen.js"></script>
        <script src="js/THREEx.WindowResize.js"></script>
        <script src="js/KeyboardState.js"></script>
        <script src="js/jquery-1.10.2.min.js"></script>
        
        <div id="top-bar" class="effect8">

            <div>
               <a href="upload.php"><img id="top-bar-logo" src="images/BRL-CAD_gear_logo_256.png"></a>
            </div>
            <div id="logo-text">BRL-CAD</div>
            <div id="slogan">Geometry Viewer</div>
            <div class="vertical-line"> </div>
            <div id="login-message" style="text-align: right; padding-right: 10px;">
                <h5>You are logged in as: <?php echo $username; ?> | <a href="accounts/logout.php">Logout</a></h5>
            </div>
            <div id="db-file-name">
                <?php 
                    $dbFileName=$_GET['dbFileName']; 
                    echo "File uploaded: $dbFileName" ?>
            </div>
        </div>
        <?php 
        include 'variables.php';
        include 'config.php';
        ?>
        <div id="ThreeJS"></div>

	<script>
	    /** Standard global variables. */
	    var container, scene, camera, renderer, controls, stats;
            var clock = new THREE.Clock();
            
            /** 
             * Creating object of KeyboardState() to implement
             * keyboard shorcut keys to see different views of model. 
             */
            var keyboard = new KeyboardState();

            /** 
             * Global obj model array, used to apply wireframe/shade 
             * to models.   
             */
            var objGlobalObject = [];

	    /** custom global variables */
	    var entitiesInScene = new Array();
	    var objEntitiesIndex, dbFileName;
            var objPath = <?php echo json_encode($objPath); ?>; 


            /**
             * A N I M A T E
             */
            function animate() 
            {
                requestAnimationFrame(animate);
                render();		
                update();
            }


            /**
             * U P D A T E
             */
            function update()
            {	
                keyboard.update();

                /** Keyboard options to see different views of model. */
                if (keyboard.down("T")) {
                    camera.position.set(0, 100, 0);
                }

                if (keyboard.down("R")) {
                    camera.position.set(0, 0, 100);
                }

                if (keyboard.down("B")) {
                    camera.position.set(0, -100, 0);
                }

                if (keyboard.down("L")) {
                    camera.position.set(100, 0, 0);
                }

                /** 
                 * If "W" is pressed, wireframe applied to all models 
                 * in the scene. 
                 */
                if (keyboard.down("W")) {
                    var OBJMaterial = new THREE.MeshBasicMaterial({color: 0x000000, wireframe: true});
                    for (var i = 0; i < objGlobalObject.length; i++) {
                        objGlobalObject[i].traverse (function (child) {
                            if (child instanceof THREE.Mesh) {
                                child.material = OBJMaterial;
                            }
                        });
                    }
                }

                /** 
                 * If "S" is pressed, shade applied to all models 
                 * in the scene. 
                 */
                if (keyboard.down("S")) {
                    var OBJMaterial = new THREE.MeshPhongMaterial({color: Math.random() * 0xffffff});
                    for (var i = 0; i < objGlobalObject.length; i++) {
                        objGlobalObject[i].traverse (function (child){
                            if (child instanceof THREE.Mesh) {
                                child.material = OBJMaterial;
                            }
                        });
                    }
                }

                controls.update();
                stats.update();
            }


            /**
             * R E N D E R
             */
            function render() 
            {	
                renderer.render(scene, camera);
            }


            /**
             * M U L T I P L E _ O B J _ L O A D E R
             *
             * Loads multiple OBJ files. Takes array as argument.
             */
            function multiple_obj_loader(objFile)
            {	    
                /** Material of OBJ model. */
                var OBJMaterial = new THREE.MeshPhongMaterial({color: 0x8888ff});
                var loader = new THREE.OBJLoader();
                loader.load(objFile[k], function (object){
                    object.traverse (function (child){
                        if (child instanceof THREE.Mesh) {
                            child.material = OBJMaterial;
            	    }
        	        });

        	        object.position.y = 0.1;
        	        scene.add(object);
                });	
            }


            /**
             * L O A D _ O B J _ F I L E S
             * 
             * Calls multiple_obj_loader in a loop to load multiple 
             * obj files.
             */
            function load_obj_files(objF)
            {
                for (k = 0; k < objF.length; k++) {
        	        multiple_obj_loader(objF);
        	    }
            }


            /**
             * S I N G L E _ O B J _ L O A D E R
             *
             * Load an obj file by taking its name as argument.
             */ 
            function single_obj_loader(objFile)
            {
                var entityName = objFile;
              
                /** 
                 * Applying material to OBJ model. By default, 
                 * wireframe will be applied.
                 */
                var OBJMaterial = new THREE.MeshPhongMaterial({color: 0x000000, wireframe: true});
                var loader = new THREE.OBJLoader();
                loader.load(objPath+"/"+objFile, function (object){
                    objGlobalObject.push(object);
        	        object.traverse (function (child){
                        if (child instanceof THREE.Mesh) {
                            child.material = OBJMaterial;
            	    }
        	        });
                    object.position.y = 0.1;
                    /** 
                     * By default, models appear tilted(one side 
                     * raised). So rotating model to make them 
                     * appear horizontally.
                     */
                    object.rotation.z = 90 * Math.PI/180;
                    object.rotation.x = -90 * Math.PI/180;
                    scene.add(object);
                    objEntitiesIndex = scene.children.length;	
                    entitiesInScene[objEntitiesIndex] = entityName;
                });
            }


            /**
             * A D D _ E N T I T Y
             *
             * Adds an entity to ThreeJS scene.
             */
            function add_entity(entity) 
            {
                /** 
                 * Sending AJAX request to create_obj.php to create 
                 * obj file for a model(to fulfil user's "view" button 
                 * click request). If sent request served successfully, 
                 * receiving name of obj file in 'data' variable in 
                 * response from create_obj.php and passing it 
                 * to single_obj)loader() to load it to ThreeJS scene.
                 */
                $.post('create_obj.php', {db: dbFileName, en: entity}, function(data){ 
                    if (data == entity+".obj"){               
                        single_obj_loader(data);
                    }                                     
                });     
            }


            /**
             * D E L E T E _ E N T I T Y
             *
             * Delete an entity from ThreeJS scene.
             */
            function delete_entity(entity) 
            {
                var rmElementIndex = entitiesInScene.indexOf(entity+".obj");
                if (rmElementIndex != '-1') { 
                    entitiesInScene.splice(rmElementIndex, 1);			
                    rmElement = scene.children[rmElementIndex - 1];
                    scene.remove(rmElement);
                } else {
                    alert("Invalid operation.");
                }
            }


            /**
             * I N I T
             *
             * Initializes ThreeJS scene.
             */
	    function init() 
	    {
		/** Scene */
		scene = new THREE.Scene();
		
		/** 
                 * Set the view size in pixels (custom or according 
                 * to window size).
                 */ 
                var screenWidth = window.innerWidth; 
                var screenHeight = window.innerHeight;	

		/** Camera attributes. */
                var viewAngle = 45; 
                var aspect = screenWidth / screenHeight; 
                var near = 0.1, far = 20000000;

		/** Set up camera. */
		camera = new THREE.PerspectiveCamera(viewAngle, aspect, near, far);

		/** Add camera to the scene. */
		scene.add(camera);

                /** 
                 * Set camera position (default position is 
                 * (0, 0, 0)) and set the angle towards the scene 
                 * origin. 
                 */
		camera.position.set(1000, 1000, 1000);
		camera.lookAt(scene.position);	
			
                /** 
                 * Create and start the renderer; 
                 * choose antialias setting. 
                 */
		if (Detector.webgl) {
		    renderer = new THREE.WebGLRenderer({antialias:true});
		} else {
			renderer = new THREE.CanvasRenderer(); 
		}
		renderer.setSize(screenWidth, screenHeight);
			
                /** 
                 * Attach div element to variable to contain 
                 * the renderer.
                 */
		container = document.getElementById('ThreeJS');
	    			
		/** Attach renderer to the container div. */
		container.appendChild(renderer.domElement);
			
		/** Automatically resize renderer. */
		THREEx.WindowResize(renderer, camera);
			
		/** Toggle full-screen on given key press. */
		THREEx.FullScreen.bindKey({ charCode: 'm'.charCodeAt(0) });
			
		/** Controls */
	
		/** 
		 * Move mouse and: left click to rotate, 
		 * middle click to zoom, 
		 * right  click to pan. 
		 */
		controls = new THREE.OrbitControls(camera, renderer.domElement);
			
		/** Stats */
			
                /** 
                 * Displays current and past frames per second 
                 * attained by scene. 
                 */
		stats = new Stats();
		stats.domElement.style.position = 'absolute';
		stats.domElement.style.bottom = '0px';
		stats.domElement.style.zIndex = 100;
		container.appendChild(stats.domElement);
			
		/** Light */
			
		/** Adding lights to scene. */
		var light = new THREE.PointLight(0xffffff);
		light.position = camera.position;
		scene.add(light);

		var ambientLight = new THREE.AmbientLight(0x111111);
		scene.add(ambientLight);


		/** Geometry */
			
		/** 
		 * Create a set of coordinate axes to help orient user 
		 * specify length in pixels in each direction. 
		 */
		var axes = new THREE.AxisHelper(10000);
		scene.add(axes);

                /** Grid */
                var grid = new THREE.GridHelper(300, 10);
                scene.add(grid); 

                /** 
                 * Receive data from URL using GET method.
                 * TODO: Should use POST method. 
                 */
                <?php
                $entitiesString = $_GET['entitiesString'];
                $dbFileName = $_GET['dbFileName'];
                ?>

                var entitiesString  = <?php echo json_encode($entitiesString); ?>; 
                dbFileName = <?php echo json_encode($dbFileName); ?>;  

                var entitiesList = new Array();
                entitiesList = entitiesString.split(" ");

                var totalEntities = entitiesList.length;

                /** 
                 * Creates left side bar to display list of 
                 * entities, each entity having corresponding pair of
                 * "view" and "delete" button. 
                 *
                 * TODO: Clean it.
                 */ 
          <?php  if (!$varErrorExists){ ?>
		document.write("<div id=\"leftSideBar\" class=\"effect6\"><table><th>Entities:</th>");
                    for (var h = 0; h < totalEntities-1; h++) {
                        if (entitiesList[h] == "_GLOBAL") {
                        h = h + 1;
	    	    document.write("<tr><td>"+entitiesList[h]+"</td><td><a id=\"view\" href=# onclick=\"add_entity(this.name)\" name=\""+entitiesList[h]+"\" value=\""+entitiesList[h]+"\">View</td></a>"+"    <td><a id=\"hide\" href=# onclick=\"delete_entity(this.name)\" name=\""+entitiesList[h]+"\" value=\""+entitiesList[h]+"\">Hide</a></td></tr>");
                        } else {
                    document.write("<tr><td>"+entitiesList[h]+"</td><td><a id=\"view\" href=# onclick=\"add_entity(this.name)\" name=\""+entitiesList[h]+"\" value=\""+entitiesList[h]+"\">View</td></a>"+"   <td><a id=\"hide\" href=# onclick=\"delete_entity(this.name)\" name=\""+entitiesList[h]+"\" value=\""+entitiesList[h]+"\">Hide</a></td></tr>");
                     
                        }
                    }
		document.write("</table></div>");
            }
        <?php } ?>
            /** Initialization */
	    init();

	    /** Animation loop */
	    animate();

        </script>
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
