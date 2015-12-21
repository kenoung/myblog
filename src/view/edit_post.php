<?php // Edit blog post

/* This script edits a blog post:

1. Receives an ID value in the URL ($_GET)
2. Record retrieved to populate form.
3. Validate form data.
4. Record update in database.

*/

// Define a page title and include header:
define('TITLE', 'Edit a Post');
include('common/header.html');

// print '<h2>Edit a Post</h2>';

// Restrict access to administrators only:
if (!is_administrator()) {

	print '<h2>Access Denied!</h2>
	<div class="alert alert-danger">
	<p>You do not have permission to access this page.</p>
	</div>';
	include('common/footer.html');
	exit();

}

// Connect to database
include("../config/mysql_connect.php");

if (isset($_GET['post_id']) && is_numeric($_GET['post_id']) && ($_GET['post_id'] > 0)) { // Display entry in a form

	// Define the query.
	$query = "SELECT title, post FROM blog_post WHERE post_id={$_GET['post_id']}";
	if ($r = mysqli_query($dbc,$query)) { // Run the query
		$row = mysqli_fetch_array($r,MYSQLI_ASSOC); // Retrieve the information

		// Make the form:
		print '<form role="form" action="edit_post.php" method="post">
			<div class="well form-group blog-post">
				<p><h3>Title</h3> <input type="text" name="title" class="form-control" value="' . htmlentities($row['title']) . '" autofocus required></p>
				<p><h3>Post</h3> <textarea name="post" class="form-control" rows="10" required>' . base64_decode($row['post']) . '</textarea></p>
				<hr><p class="text-center"><button type="submit" class="btn btn-warning" name="submit">Update this Quote!</button></p>
				<input type="hidden" name="post_id" value="' . $_GET['post_id'] . '" />
		</form>';

	} else { // Couldn't get the information

	print '<div class="alert alert-danger"><p>Could not retrieve the blog post because:<br>' . mysqli_error($dbc) . '.</p>
	<p>The query being run was ' . $query . '</p></div>';

	}

} elseif (isset($_POST['post_id']) && is_numeric($_POST['post_id']) && ($_POST['post_id'] > 0)) { // Handle the form
	
	// Validate and secure the form data:
	$problem = false;
	if ( !empty($_POST['title']) && !empty($_POST['post'] )) {

		// prepare the values for storing:
		$title = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['title'])));
		$post = mysqli_real_escape_string($dbc, base64_encode($_POST['post']));

	} else {
		print '<div class="alert alert-danger"><p>Please make sure you have filled out all the fields.</p></div>';
		$problem = TRUE;
	}

	if (!$problem) {

		// Define the query
		$query = "UPDATE blog_post SET title='$title', post='$post' WHERE post_id={$_POST['post_id']}";
		if ($r = mysqli_query($dbc, $query)) {
			print '<div class="alert alert-success"><p>Your blog post has been updated. Return <a href="home.php'."#{$_POST['post_id']}".'">home</a> to see your changes.</p></div>';

			// Make the form:
			print '<form role="form" action="edit_post.php" method="post">
			<div class="well form-group blog-post">
				<p><h3>Title</h3> <input type="text" name="title" class="form-control" value="' . htmlentities($title) . '" autofocus required></p>
				<p><h3>Post</h3> <textarea name="post" class="form-control" rows="10" required>' . base64_decode($post) . '</textarea></p>
				<hr><p class="text-center"><button type="submit" class="btn btn-warning" name="submit">Update this Quote!</button></p>
				<input type="hidden" name="post_id" value="' . $_POST['post_id'] . '" />
		</form>';

		} else {
			print '<div class="alert alert-danger"><p>Could not update the quotation because:<br />' . mysql_error($dbc) . '.</p>
			<p>The query could not run because:<br />' . mysql_error($dbc) . '.</p>
			<p>The query being run was ' . $query . '</p></div>';

		}

	} // No problem

} else { // No ID set
	print '<div class="alert alert-danger"><p>This page has been accessed in error.</p></div>';
} // End of main IF.

	mysqli_close($dbc); // Close the connection
	include('common/footer.html'); // Include the footer

	?>