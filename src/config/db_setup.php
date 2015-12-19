<?php // Set-up your database
/* Please update mysql_connect.php after running this script */

// Add header
define('TITLE', 'Database Setup');
include('../view/common/header.html');

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

	<?php } 

	// Add footer
	include('../view/common/footer.html');

	?>
</body>
</html>
