<?php // Add a blog post

/* This script adds an entry to the database. */

define('TITLE', 'Add Blog Post');
include('common/header.html');

// Must sign in for access:
check_access('School', 'Computing');

// Need the database connection
// include(MYSQL); - use this only if you changed the path in config.php
include("../config/mysql_connect.php");

// Check for a form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form

	if (strlen($_POST['title']) > 100) { // Check title length
		print '<div class="alert alert-danger">
		<p>Please use a short and succint title that is less than 100 characters long. You used ' . strlen($_POST['title']) . ' characters!</p>
		</div>';

	} elseif (!empty($_POST['title']) && !empty($_POST['post'])) {



		handle_bp('add');

	} else { // Failed to fill in the required blanks

		print '<div class="alert alert-danger">
		<p>Please make sure you have entered both a title and your blog post.</p>
		</div>';

	}
} // End of submitted IF.

display_bp('add');

?>


<?php include('common/footer.html');

?>