<?php // Login script

// Set two variables with default values:
$loggedin = false;
$error = false;

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Handle the form:
	if (!empty($_POST['email']) && !empty($_POST['password'])) {

		if (strtolower($_POST['email']) == 'admin@myblog.com' && $_POST['password'] == 'password') { // Correct

			// Create the cookie
			setcookie('School', 'Computing', time()+3600);
			$loggedin = true;

		} else { // Incorrect

			$error = 'The submitted email address and password do not match those on file.';

		}
	} else { // Forgot a field

		$error = 'Please make sure you enter both an email address and a password.';

	}
}

// Set the page title and include the header file:
define('TITLE', 'Login');
include('common/header.html');

// Print an error if one exists
if ($error) {
	print "<div class=\"alert alert-danger\">\n
	\t\t<p>" . $error. '</p></div>';
}

// Indicate the user is logged in, or show the form:
if ($loggedin) {
	print '<p>You are now logged in!</p>';
} else {
	print '<div class="form-group">';
	print '
	<div class="row">
	<div class="col-xs-4"></div>

	<div class="col-xs-4 well">
	<h2><strong>Login</strong></h2>
	<form role="form" action="login.php" method="post">
	<p><input type="email" name="email" class="form-control" placeholder=" Enter Email"></p>
	<p><input type="password" name="password" class="form-control" placeholder=" Enter Password"></p>
	<p><button type="submit" name="submit" class="btn btn-default">Log In!</button></p></form>
	</div>
	<div class="col-xs-4"></div>
	</div>
	</div>
	';

}

include('common/footer.html');
// Need the footer
?>