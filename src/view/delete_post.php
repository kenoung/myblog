<?php // Deletes a blog post

/* This script deletes a blog post. */


// Include the header:
define('TITLE', 'Delete a Post');
include('common/header.html');



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

	print '<div class="alert alert-warning"><p>Are you sure you want to <strong>delete</strong> this blog post? Click <a href="home.php">here</a> to return home instead.</p> </div>';

	// Define the query.
	$query = "SELECT title, post FROM blog_post WHERE post_id={$_GET['post_id']}";
	if ($r = mysqli_query($dbc,$query)) { // Run the query
		$row = mysqli_fetch_array($r,MYSQLI_ASSOC); // Retrieve the information


		// Make the form:
		print '<form role="form" action="delete_post.php" method="post">

			<div class="well form-group blog-post">
				<p><h3>Title</h3> <div class="well well-sm">' . htmlentities($row['title']) . '</div></p>
				<p><h3>Post</h3> <div class="well">' . base64_decode($row['post']) . '</div></p>
				<hr><p class="text-center"><button type="submit" class="btn btn-danger" name="submit">DELETE!</button></p>
				<input type="hidden" name="post_id" value="' . $_GET['post_id'] . '" />
		</form>';

	} else { // Couldn't get the information

	print '<div class="alert alert-danger"><p>Could not retrieve the blog post because:<br>' . mysqli_error($dbc) . '.</p>
	<p>The query being run was ' . $query . '</p></div>';

	}

} elseif (isset($_POST['post_id']) && is_numeric($_POST['post_id']) && ($_POST['post_id'] > 0)) { // Handle the form

	// Define the query:
	$query = "DELETE FROM blog_post WHERE post_id={$_POST['post_id']} LIMIT 1";
	$r = mysqli_query($dbc, $query); // Execute the query

	// Report on the result:
	if (mysqli_affected_rows($dbc) == 1) {

		print '<div class="alert alert-success"><p>Your blog post has been deleted. Return <a href="home.php">home</a> to see your changes.</p></div>';
	
	} else {

		print '<div class="alert alert-danger">
		<p>Could not delete the blog post because:<br>' . mysqli_error($dbc) . '.</p>
		<p>The query being run was ' . $query . '</p></div>';

	}

} else { // No ID received

		print '<div class="alert alert-danger">
		<p>This page has been accessed in error.</p></div>';

}

mysqli_close($dbc); // Close the connection

include('common/footer.html');
?>