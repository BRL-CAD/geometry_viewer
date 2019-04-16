
<?php
/*                C O N F I G - P R O C E S S . P H P
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
/** @file geometry_viewer/config-edit.php
 *
 */
	require_once('accounts/auth.php');
	include_once('header.php');
?>
	
	<!-- TODO: Make this style find a place in stylesheets -->
	<style> body { overflow:auto; } </style>

<?php	
	if(isset ($_POST['submit'])) {	
	
		$configString = <<<EOT
		
/*                     C O N F I G . P H P
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
/** @file geometry_viewer/config.php
 *
 */

    /** Title of project. */
    \$title = "{$_POST['app-title']}";

	
    /** Domain name of site. e.g. http://brlcad.org/ */
    \$siteUrl = "{$_POST['app-url']}";
	
    /** 
     * Path of mged executable file. To check this path on your 
     * system, run this command on terminal: "which mged" 
     * (without quotes).
     */
    \$mgedPath = "{$_POST['mged-path']}";

	
    /**
     * Path of g-obj executable file. To check this path on your 
     * system, run this command on terminal: "which g-obj" 
     * (without quotes).
     */
    \$gobjPath = "{$_POST['gobj-path']}";


    /** MySQL credentials. */
    \$mysqlUsername = "{$_POST['mysql-username']}";
    \$mysqlPassword = "{$_POST['mysql-password']}";
    \$mysqlDatabase = "{$_POST['mysql-database']}";
			
    /** Subject of confirmation e-mail sent on new account creation. */
    \$newAccountSubject = "{$_POST['new-account-subject']}";

    /** Subject of password reset e-mail. */
    \$passwordResetSubject = "{$_POST['password-reset-subject']}";
		
    /** E-mail address of sender. Currently, it should be gmail address. */
    \$senderEmail = "{$_POST['sender-email']}";
	
    /** Name of sender written in emails. */
    \$senderName = "{$_POST['sender-name']}";
		
    /** Password of sender's account. */
    \$senderPassword = "{$_POST['sender-password']}";
	
    \$normTol = '10';	
	
    require_once('config-validate.php');

/*                                                                    
 * Local Variables:                                                   
 * mode: PHP                                                            
 * tab-width: 8
 * End:                                                               
 * ex: shiftwidth=4 tabstop=8                                         
 */

EOT;

        /** Open config.php file and write the configurations to it */
        $configFile = fopen("config.php","w") or die 
            ('<div class="alert alert-danger"> Sorry, we were not able to open this file. 
	    You will have to copy paste the following content yourself in config.php. 
	    Don\'t forget to delete the prior content if any. </div> <pre> &lt;?PHP'.$configString.'?&gt; </pre>');
        fwrite($configFile, '<?php'.$configString.'?>');
        fclose($configFile);
    }

    /** Include config.php file again to check for validations */
    require_once('config.php');

    /** If configuration errors still presist */
    if(!empty($errorArray)){
        header('Location:config-edit.php');
    }
?>

	<div class="alert alert-success"> 
		Your new config file has been created! 
		<a href="upload.php" class="btn btn-success"> Upload a Model </a>
	</div>
<?php
 /*                                                                    
 * Local Variables:                                                   
 * mode: PHP                                                            
 * tab-width: 8
 * End:                                                               
 * ex: shiftwidth=4 tabstop=8                                         
 */
?>
