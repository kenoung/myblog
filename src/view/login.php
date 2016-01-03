<?php // Login script

// Set two variables with default values:
$loggedin = false;
$error = false;

// Set the page title and include the header file:
define('TITLE', 'Login');
include('common/header.html');

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Handle the form:
	if (!empty($_POST['email']) && !empty($_POST['password'])) {

		// Include database connection
		include("../config/mysql_connect.php");
		$email = mysqli_real_escape_string($dbc, $_POST['email']);
		$password = mysqli_real_escape_string($dbc, $_POST['password']);

		// Query the database:
		$query = "SELECT author_id, first_name, password FROM authors WHERE email='$email'";
		if ($r = mysqli_query($dbc, $query)) {
			$values = mysqli_fetch_array($r, MYSQLI_ASSOC);
			if (@mysqli_num_rows($r) == 1 && password_verify($password, $values['password'])) {
				// Record the values
				$_SESSION['author_id'] = $values['author_id'];
				$_SESSION['first_name'] = $values['first_name'];
				mysqli_free_result($r);
				mysqli_close($dbc);

				ob_end_clean(); // Delete the buffer
				header("refresh: 0"); // Refresh the page
				exit(); // Quit the script.
			} else { // Incorrect
				$error = 'The submitted email address and password do not match those on file.';
			}

		} else {
			print '<div class="alert alert-danger">
			<p>Could not submit your post because:<br />' . mysqli_error($dbc) .'.</p> 
			<p>The query being run was ' . $query . '</p></div>';
		}

	} else { // Forgot a field

		$error = 'Please make sure you enter both an email address and a password.';

	}
}



// Print an error if one exists
if ($error) {
	print "<div class=\"alert alert-danger\">\n
	\t\t<p>" . $error. '</p></div>';
}


if (is_administrator()) {

	$loggedin = true;

}

// Indicate the user is logged in, or show the form:
if ($loggedin) {
	print '<div class="well text-center">
	<h2>You are now logged in!</h2>
	<br>
	<p class="lead">You can view, edit or delete posts from the <a href="home.php">home page</a> or add a new post <a href="add_post.php">here</a>.</p>
	</div>';
} else {

// Exit php to display login form
?>

<div class="row">
	<div class="col-sm-4"></div>
	<div class="col-sm-4">
		<form class="form-horizontal well" role="form" action="login.php" method="post">
			<h2><strong>Login</strong></h2><br>

			<!-- Email -->
			<div class="form-group">	
				<div class="col-sm-12">
				    <label class="control-label sr-only" for="email">Email:</label>
		    		<input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
		    	</div>
	    	</div>

	    	<!-- Password -->
		  	<div class="form-group">
		  		<div class="col-sm-12">
					<label class="control-label sr-only" for="password">Password:</label>
					<input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>
				</div>
			</div>

			<!-- Submit Button -->
			<div class="form-group"> 
			    <div class="text-center">
			      	<button type="submit" class="btn btn-default">Submit</button>
			  	</div>
			</div>

		</form>
	</div>
	<div class="col-sm-4"></div>
</div>

<?php

}

include('common/footer.html');
// Need the footer
?>