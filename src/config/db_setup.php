<!DOCTYPE html>
<html>
<head>
<title>Database Setup</title>
</head>
<body>
	<h1>Set Up Your Database</h1>
	<p>This script can only be used if you have full access. Otherwise, you need to create your database manually and run the SQL queries in create_table.php.</p>
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
				$query = 'SELECT * FROM blog_post';

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
					if (@mysql_select_db('myblog', $dbc)) {
						print '<p>The database has been selected!</p>';
					} else {
						print '<p>Could not select the database because:<br />' . mysql_error($dbc) . '.</p>';
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
	<form role="form" action="db_setup.php" method="post">
		<div class="form-group">
			<p>Hostname: <input type="text" name="hostname" /></p>
			<p>Username: <input type="text" name="user" /></p>
			<p>Password: <input type="password" name="password" /></p>
			<button type="submit" class="btn btn-default" name="submit">Log in to database</button>
		</div>
	<?php } 

	print "<p>Return to <a href=\"../view/home.php\">home</a>.</p>"

	?>
</body>
</html>
