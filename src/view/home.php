<?php // Home page. View my blog posts

/* This script lists every blog post. */

// Include the header:
define('TITLE', 'Home Page');
include('common/header.html');

// print '<h2>Latest Posts</h2>';

// Allow authors to add post
if (is_administrator()) {
	print '
	<div class="well blog-post">
	<a href="add_post.php" class="btn btn-success">Add A Post Here!</a> </p>
	</div>
	';
}


// Connect to database
include("../config/mysql_connect.php");

// Categories
$cat_list = available_categories();

// Define the query:
$query = 'SELECT post_id, title, post, date_entered, cat_id FROM blog_post ORDER BY date_entered DESC';

// Run the query:
if ($r = mysqli_query($dbc,$query)) {

	// Retrieve the returned records:
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

		// Find category name
		$cat_name = $cat_list[$row['cat_id']];

		// print the record:
		print "<div class=\"well\"   id=\"{$row['post_id']}\">

		<div class=\"text-center post-title\">
		<h2><strong>".htmlentities($row['title'])."</strong></h2>
		posted {$row['date_entered']} in <strong>$cat_name</strong>
		</div>

		<div class=\"blog-post\">".base64_decode($row['post'])."</div>
		\n";


		// allow authors to edit/delete blog posts
		if (is_administrator()) {
			print "
			<div class=\"btn-group\">
			<a href=\"edit_post.php?post_id={$row['post_id']}\" class=\"btn btn-default\">Edit</a> 
			<a href=\"delete_post.php?post_id={$row['post_id']}\" class=\"btn btn-default\">Delete</a>
			</div>
			</div>";
		} else {
			print "</div>";
		}


	} // End of while loop.
} else { // Query didn't run
	print '<p class="alert alert-warning">Could not retrieve the data because:<br />' . mysqli_error($dbc) . '.</p>
	<p>The query being run was: ' . $query . '</p>';

} // End of query IF.

mysqli_close($dbc); // Close the connection.

include('common/footer.html'); // Include the footer.
?>
