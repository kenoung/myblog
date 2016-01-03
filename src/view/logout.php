<?php // Logout Script

// Define a page title and include the header:
define('TITLE', 'Logout');
include('common/header.html');

// Destroy the session if it exists
if (isset($_SESSION['first_name'])) {
	$_SESSION = array(); // Destroy the variables
	session_destroy(); // Destroy the session itself.
	setcookie(session_name(), '', time()-3600); // Destroy the cookie
}

// Print a message:
print '<div class="well">
<p class="lead">You are now logged out.</p>
</div>';

// Include the footer:
include('common/footer.html');

?>