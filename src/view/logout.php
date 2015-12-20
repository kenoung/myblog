<?php // Logout Script

// Destroy the cookie if it exists
if (isset($_COOKIE['School'])) {
	setcookie('School', FALSE, time()-300);
}

// Define a page title and include the header:
define('TITLE', 'Logout');
include('common/header.html');

// Print a message:
print '<div class="well">
<p class="lead">You are now logged out.</p>
</div>';

// Include the footer:
include('common/footer.html');

?>