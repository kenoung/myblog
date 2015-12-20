<?php // Add a blog post

/* This script adds an entry to the database. */

define('TITLE', 'Add Blog Post');
include('common/header.html');

// Must sign in for access:
if (!is_administrator()) {
	print '<h2>Access Denied!</h2>
	<p class="lead">Please <a href="login.php">log in</a> if you want to add a post.</p>';
	include('common/footer.html');
	exit();
}

// Check for a form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form

	if (strlen($_POST['title']) > 100) { // Check title length
		print '<div class="alert alert-danger">
		<p>Please use a short and succint title that is less than 100 characters long. You used ' . strlen($_POST['title']) . ' characters!</p>
		</div>';

	} elseif (!empty($_POST['title']) && !empty($_POST['post'])) {

		// Need the database connection
		// include(MYSQL); - use this only if you changed the path in config.php
		include("../config/mysql_connect.php");


		// Prepare the values for storing
		$title = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['title'])));
		$post = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['post'])));

		$query = "INSERT INTO blog_post (title, post) VALUES ('$title', '$post')";
		$r = mysqli_query($dbc, $query);

		if (mysqli_affected_rows($dbc) == 1) { // print a message
			print '<div class="alert alert-success"><p>Your blog post was submitted. View your post <a href="home.php">here</a></p></div>';
		} else {
			print '<div class="alert alert-danger">
			<p>Could not submit your post because:<br />' . mysqli_error($dbc) .'.</p> 
			<p>The query being run was ' . $query . '</p></div>';
		}

		// Close the connection
		mysqli_close($dbc);

	} else { // Failed to fill in the required blanks

		print '<div class="alert alert-danger">
		<p>Please make sure you have entered both a title and your blog post.</p>
		</div>';

	}
} // End of submitted IF.

// Leave PHP and display the form

?>

		<form role="form" action="add_post.php" method="post">
			<div class="well form-group blog-post">
				<p><h3>Title</h3> <input type="text" name="title" class="form-control" required autofocus></p>
				<p><h3>Post</h3> <textarea name="post" class="form-control" rows="10" required></textarea></p>
				<hr>
				<p class="text-center"><button type="submit" class="btn btn-success" name="submit">Post!</button></p>

		</form>

<?php include('common/footer.html');

?>