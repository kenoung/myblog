<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Database Set Up</title>
</head>
<body>
	

	<?php // Set-up your database
	/* Please update mysql_connect.php after running this script */

	// Check if set-up has been completed before

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		// Validate input:

		if (!empty($_POST['hostname']) && (!empty($_POST['user']))) {

			$hostname = $_POST['hostname'];
			$user = $_POST['user'];
			$password = $_POST['password'];

			// Connect to database

			// Attempt to connect to MySQL and print out messages.
			if ($dbc = @mysql_connect($hostname, $user, $password)) {
				print '<p>You have successfully connected to MySQL!</p>';

				// Try to select the database:
				if (@mysql_select_db('myblog', $dbc)) {

					print '<p>Your database has already been created!</p>';

					// Define the query:
					$query = 'SELECT * FROM quotes';

					if ($r = mysql_query($query, $dbc)) { // Run the query.

						// Set up has been completed.

						print '<p>You have already completed your setup.</p>';

						}  else { // Query didn't run.
				
							require_once("create_table.php") ;

				
						}

				} else {
					print '<p>Creating database...</p>';

					// Create database
					if ($dbc) { 

						if (@mysql_query('CREATE DATABASE myblog', $dbc)) {
							print '<p>The database myblog has been created!</p>';
						} else { // Could not create database
							print '<p style="color: red; ">Could not create the database because:<br />' . mysql_error($dbc) . '.</p>';
						}

						// Try to select the database:
						if (@mysql_select_db('myquotes', $dbc)) {
							print '<p>The database has been selected!</p>';
						} else {
							print '<p style="color:red; ">Could not select the database because:<br />' . mysql_error($dbc) . '.</p>';
						}

						require_once("create_table.php");

					}
				}


			} else {
				print "<p>Please check your credentials. Unable to log in to your SQL database.</p>"; 
			}

			
	
		} else { // Error in inputs
			print "Please fill in all the fields.";
		}

	} else { // Display form

		?>
		<form action="db_setup.php" method="post">
			<p>Hostname: <input type="text" name="hostname" /></p>
			<p>Username: <input type="text" name="user" /></p>
			<p>Password: <input type="password" name="password" /></p>
			<button type="submit" name="submit">Log in to database</button>

		<?php




	}

		// if ($dbc = @mysql_connect('localhost', 'root', '')) {

		// 	// Handle the error if the database couldn't be selected
		// 	if (!@mysql_select_db('myquotes', $dbc)) {
		// 		print '<p style="color: red;">Could not select database because:<br />' . mysql_error($dbc) . '.</p>';
		// 		mysql_close($dbc);
		// 		$dbc = FALSE;
		// 	} 
		// } else { // Connection Failure
		// 	print '<p style="color: red; ">Could not connect to MySQL:<br />' . mysql_error() . '.</p>';
		// }

		?>
</body>
</html>
