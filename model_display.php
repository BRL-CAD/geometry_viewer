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

include 'variables.php';
?>

<!doctype html>
<html lang = "en">
    <head>
        <title>BRL-CAD Online Geometry Viewer</title>
	<meta charset = "utf-8">
        <meta name = "viewport" content = "width = device-width, 
        user-scalable = no, minimum-scale = 1.0, maximum-scale = 1.0">
	<link rel = stylesheet href = "css/base.css"/>
	<link rel = stylesheet href = "css/bootstrap.min.css"/>
    </head>
    <body>
	<script src = "js/three.min.js"></script>
	<script src = "js/Detector.js"></script>
	<script src = "js/Stats.js"></script>
	<script src = "js/loaders/OBJLoader.js"></script>
	<script src = "js/OrbitControls.js"></script>
	<script src = "js/THREEx.FullScreen.js"></script>
	<script src = "js/THREEx.WindowResize.js"></script>
        <script src = "js/jquery-1.10.2.min.js"></script>
        
        <div id="top-bar">
            <img id="top-bar-logo" src="images/BRL-CAD_gear_logo_256.png">
            <legend>You are logged in as: <?php echo 
            $username; ?> | <a href="accounts/logout.php">Logout</a>
            </legend>
        </div>
        
        <div id = "ThreeJS"></div>

	<script>

	    /** standard global variables */
	    var container, scene, camera, renderer, controls, stats;
	    var clock = new THREE.Clock();

	    /** custom global variables */
	    var entitiesInScene = new Array();
	    var objEntitiesIndex, dbFileName;
            var objPath  = <?php echo json_encode($objPath); ?>; 


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
                /** material of OBJ model */
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
                /** material of OBj model */
                var OBJMaterial = new THREE.MeshPhongMaterial({color: Math.random() * 0xffffff});
                var loader = new THREE.OBJLoader();
                loader.load(objPath+"/"+objFile, function (objFile){
        	        objFile.traverse (function (child){
                        if (child instanceof THREE.Mesh) {
                            child.material = OBJMaterial;
            	    }
        	        });
                    objFile.position.y = 0.1;
                    objFile.rotation.z = 90 * Math.PI/180;
                    objFile.rotation.x = -90 * Math.PI/180;
                    scene.add(objFile);
                    objEntitiesIndex = scene.children.length;	
                    entitiesInScene[objEntitiesIndex] = entityName;
                });
            }


            /**
             * A D D _ E N T I T Y
             *
             * Adds an entity to ThreeJS scene
             */
            function add_entity(entity) 
            {
                $.post('create_obj.php', {db: dbFileName, en: entity}, function(data) {
                    if (data == entity+".obj"){               
                        single_obj_loader(data);
                    }                                     
                });     
            }


            /**
             * D E L E T E _ E N T I T Y
             *
             * Delete an entity from ThreeJS scene
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
             * Initializes ThreeJS scene
             */
	    function init() 
	    {
		/** scene */
		scene = new THREE.Scene();
		
		/** 
                 * set the view size in pixels (custom or according 
                 * to window size)
                 */ 
                var screenWidth = window.innerWidth; 
                var screenHeight = window.innerHeight;	

		/** camera attributes */
                var viewAngle = 45; 
                var aspect = screenWidth / screenHeight; 
                var near = 0.1, far = 20000000;

		/** set up camera */
		camera = new THREE.PerspectiveCamera(viewAngle, aspect, near, far);

		/** add the camera to the scene */
		scene.add(camera);

                /** 
                 * Set camera position (default position is 
                 * (0, 0, 0)) and set the angle towards the scene 
                 * origin 
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
                 * the renderer
                 */
		container = document.getElementById('ThreeJS');
	    			
		/** attach renderer to the container div */
		container.appendChild(renderer.domElement);
			
		/** automatically resize renderer */
		THREEx.WindowResize(renderer, camera);
			
		/** toggle full-screen on given key press */
		THREEx.FullScreen.bindKey({ charCode: 'm'.charCodeAt(0) });
			
		/** Controls */
	
		/** 
		 * move mouse and: left click to rotate, 
		 * middle click to zoom, 
		 * right  click to pan 
		 */
		controls = new THREE.OrbitControls(camera, renderer.domElement);
			
		/** Stats */
			
                /** displays current and past frames per second 
                 * attained by scene 
                 * */
		stats = new Stats();
		stats.domElement.style.position = 'absolute';
		stats.domElement.style.bottom = '0px';
		stats.domElement.style.zIndex = 100;
		container.appendChild(stats.domElement);
			
		/** Light */
			
		/** adding lights to scene */
		var light = new THREE.PointLight(0xffffff);
		light.position.set(0, 250, 0);
		scene.add(light);

		var ambientLight = new THREE.AmbientLight(0x111111);
		scene.add(ambientLight);

	        var directionalLight = new THREE.DirectionalLight(0xffeedd);
		directionalLight.position.set(1000, 1000, 1000);
                scene.add(directionalLight);

		/** Geometry */
			
		/** 
		 * create a set of coordinate axes to help orient user 
		 * specify length in pixels in each direction 
		 */
		var axes = new THREE.AxisHelper(10000);
		scene.add(axes);

                /** Grid */
                var grid = new THREE.GridHelper(300, 10);
                scene.add(grid); 

                /** Receive data from URL. */                
                <?php
                $entitiesString = $_GET['entitiesString'];
                $dbFileName = $_GET['dbFileName'];
                ?>

                var entitiesString  = <?php echo json_encode($entitiesString); ?>; 
                dbFileName = <?php echo json_encode($dbFileName); ?>;  

                var entitiesList = new Array();
                entitiesList = entitiesString.split(" ");

                var totalEntities = entitiesList.length;

                /** Creates left side bar to display list of 
                 * entities, each entity having corresponding pair of
                 * "view" and "delete" button. */    
		document.write("<div id = \"leftSideBar\"><table><th>Entities:</th>");
                    for (var h = 0; h < totalEntities-1; h++) {
                        if (entitiesList[h] == "_GLOBAL") {
                        h = h + 1;
	    	    document.write("<tr><td>"+entitiesList[h]+"</td><td><a id = \"view\" href = # onclick = \"add_entity(this.name)\" name = \""+entitiesList[h]+"\" value = \""+entitiesList[h]+"\">View</td></a>"+"    <td><a id = \"hide\" href = # onclick = \"delete_entity(this.name)\" name = \""+entitiesList[h]+"\" value = \""+entitiesList[h]+"\">Hide</a></td></tr>");
                        } else {
                    document.write("<tr><td>"+entitiesList[h]+"</td><td><a id = \"view\" href = # onclick = \"add_entity(this.name)\" name = \""+entitiesList[h]+"\" value = \""+entitiesList[h]+"\">View</td></a>"+"   <td><a id = \"hide\" href = # onclick = \"delete_entity(this.name)\" name = \""+entitiesList[h]+"\" value = \""+entitiesList[h]+"\">Hide</a></td></tr>");
                     
                        }
                    }
		document.write("</table></div>");
            }

            /** initialization */
	    init();

	    /** animation loop / game lop */
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
