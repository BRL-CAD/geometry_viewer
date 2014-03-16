<?php
/*                    C O N F I G - E D I T . P H P
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
	require_once('config.php');
	include_once('header.php');
?>
	
	<!-- TODO: Make this style find a place in stylesheets -->
	<style> body { overflow:auto; } </style>

	<div class="form-wrapper">
        <form role="form" action="config-process.php" method="post">
			<h2> Configuration Settings </h2>

			<!-- The title of the app, appears in header and at many other places --> 	
			<div class="form-group">
				<label for="app-title">App Title</label>
				<input type="text" class="form-control" name="app-title" value="<?php echo $title; ?>">	
			</div>		
			
			<!-- The url of the app -->
			<div class="form-group">
				<label for="app-url">App URL</label>
				<input type="text" class="form-control" name="app-url" value="<?php echo $siteUrl; ?>">
			</div>
			
			<!-- Path to mged -->
			<div class="form-group">
				<label for="mged-path">mged Path</label>
				<input type="text" class="form-control" name="mged-path" value="<?php echo $mgedPath; ?>">
				<p class="help-block"> 
					To know your mged path, go to terminal and check the output of command <code>which mged</code>
				</p>               
			</div>

			<!-- Path to g-obj -->
			<div class="form-group">
				<label for="gobj-path">gobj Path</label>
				<input type="text" class="form-control" name="gobj-path" value="<?php echo $gobjPath; ?>">
				<p class="help-block"> 
					To know your g-obj path, go to terminal and check the output of command <code>which g-obj</code>
				</p>
			</div>
			
			<!-- MySQL Credentials -->
			<div class="form-group">
				<label for="mysql-username">MySQL Username</label>
				<input type="text" class="form-control" name="mysql-username" value="<?php echo $mysqlUsername; ?>">
			</div>		
			
			<div class="form-group">
				<label for="mysql-password">MySQL Password</label>
				<input type="password" class="form-control" name="mysql-password" value="<?php echo $mysqlPassword; ?>">
			</div>

			<div class="form-group">
				<label for="mysql-database">MySQL Database</label>
				<input type="text" class="form-control" name="mysql-database" value="<?php echo $mysqlDatabase; ?>">
			</div>
			
			<!-- Subject of the sign-up and password reset confirmation mail -->
			<div class="form-group">
				<label for="new-account-subject">New Account Mail Subject</label>
				<input type="text" class="form-control" name="new-account-subject" value="<?php echo $newAccountSubject; ?>">
				<p class="help-block"> 
					After sign up user will recieve a confirmation mail with subject that you specify here.
				</p>
			</div>
			
			<div class="form-group">
				<label for="password-reset-subject">Password Reset Subject</label>
				<input type="text" class="form-control" name="password-reset-subject" value="<?php echo $passwordResetSubject; ?>">
				<p class="help-block"> 
					For resetting the password user will recieve a mail with subject that you specify here.
				</p>
			</div>
			
			<!-- Email address of the sender -->
			<div class="form-group">
				<label for="sender-email">Sender e-mail </label>
				<input type="email" class="form-control" name="sender-email" value="<?php echo $senderEmail; ?>">
				<p class="help-block"> 
					Currently, it should be a gmail address.  
				</p>             
			</div>

			<!-- Name of the sender -->
			<div class="form-group">
				<label for="sender-name">Sender Name </label>
				<input type="text" class="form-control" name="sender-name" value="<?php echo $senderName; ?>">
			</div>
			
			<!-- Password for email account -->
			<div class="form-group">
				<label for="sender-password">Sender e-mail's password </label>
				<input type="password" class="form-control" name="sender-password" value="<?php echo $senderPassword; ?>">
			</div>
		
			<button type="submit" class="btn btn-primary" name="submit">Submit</button>
		</form>  
	</div>
      
</body>
</html>

