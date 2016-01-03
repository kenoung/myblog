<?php // Registration page. Register for a new account

/* This script allows new users to create their own account. */

// Include the header:
define('TITLE', 'Registration Page');
include('common/header.html');


if (is_administrator()) {
	// If already logged in

	print '<div class="well text-center">
	<h2>You are already logged in!</h2>
	<br>
	<p class="lead">You can view, edit or delete posts from the <a href="home.php">home page</a> or add a new post <a href="add_post.php">here</a>.</p>
	</div>';

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Handle the form

	// Include database connection
	include("../config/mysql_connect.php");

	// Validate inputs: first_name, last_name, email, password, password2
	$v_array = array (
		'first_name',
		'last_name',
		'email',
		'password',
		'password2'
	);

	$exist_error = validate_exists_error($v_array);

	if ($exist_error) {
		echo '<div class="alert alert-warning lead">' . $exist_error . '<p>Please return to the previous page and make sure to fill out all the fields.</p></div>';
	} elseif ($_POST['password'] != $_POST['password2']) { // Validate passwords
		echo '<div class="alert alert-warning lead"><p>Your passwords do not match.</p>
		<p>Please return to the previous page and fill out your passwords again.</p></div>';
	} else {

		foreach ($_POST as $key => $value) {
			if ($key == 'password') {
				$password = mysqli_real_escape_string($dbc, password_hash($_POST['password'], PASSWORD_DEFAULT));
			} else {
				$$key = mysqli_real_escape_string($dbc, $value);
			}
		}

		// Check if email is already registered
		$query = "SELECT author_id FROM authors WHERE email='$email'";
		if ($r = mysqli_query($dbc, $query)) {
			if (mysqli_num_rows($r) == 0) {
				// Create new author
				$query = "INSERT INTO authors (first_name, last_name, email, password) VALUES ('$first_name',  '$last_name', '$email', '". $password ."')";
				if ($r = mysqli_query($dbc, $query)) {
					echo '<div class="alert alert-success">Your account has been successfully created. You can now login <a href="login.php">here</a>.';
				} else {
					print '<div class="alert alert-danger">
					<p>Could not submit your post because:<br />' . mysqli_error($dbc) .'.</p> 
					<p>The query being run was ' . $query . '</p></div>';
				}
			} else {
				// Print error message
				echo '<div class="alert alert-warning lead"><p>This email has already been registered.</p>
			<p>Please return to the previous page and try a new email.</p></div>';
			}
		} else {
			print '<div class="alert alert-danger">
			<p>Could not submit your post because:<br />' . mysqli_error($dbc) .'.</p> 
			<p>The query being run was ' . $query . '</p></div>';
		}
	}

} else {
	// Display the form

	// Exit PHP to display the form
?>
<div class="row">
	<div class="col-sm-4"></div>
	<div class="col-sm-4">
		<form class="form-horizontal well" role="form" action="register.php" method="post">
			<h2><strong>Register Here</strong></h2>
			<br>

			<!-- Name -->
			<div class="form-group">

				<div class="col-sm-6">
					<label class="control-label sr-only" for="first_name">First Name:</label>
					<input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" autofocus required>
				</div>
				<div class="col-sm-6">
					<label class="control-label sr-only" for="last_name">Last Name:</label>
					<input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" required>
				</div>

			</div>

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

			<!-- Repeat Password -->
		  	<div class="form-group">
		  		<div class="col-sm-12">
					<label class="control-label sr-only" for="password">Repeat Password:</label>
					<input type="password" class="form-control" name="password2" id="password2" placeholder="Confirm your password" required>
				</div>
			</div>

			<!-- Submit Button -->
			<br>
			<div class="form-group"> 
			    <div class="text-center">
			      <button type="submit" class="btn btn-default">Submit</button>
			</div>
		</form>
	</div>
	<div class="col-sm-4"></div>
</div>


<?php
} 
?>
