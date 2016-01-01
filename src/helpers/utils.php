<?php // Utility Helper Functions

#########################
## Check Access Rights ##
#########################

function is_administrator($name = 'School', $value = 'Computing') {
	// This function checks if the user is an administrator.
	// This function takes two optional values.
	// This function returns a Boolean value.

	// Check for the cookie and check it's value
	if (isset($_COOKIE[$name]) && ($_COOKIE[$name] == $value)) {
		return true;
	} else {
		return false;
	}
} // End of is_administrator() function.

function check_access($name = NULL, $value = NULL) {
	// This script takes no inputs. Checks if user has access rights to the page
	if (!is_administrator($name, $value)) {
		print '<div class="well"><h2>Access Denied!</h2>
		<p class="lead">Please <a href="login.php">log in</a> if you want to add a post.</p>';
		include('common/footer.html');
		exit();
	}
}

####################
## Blog Post Form ##
####################

function validate_exists_error($var_array) {
	/*
	 * Returns an error if any variable in $var_array does not exist.
	 *
	 * All variables are assumed to be found in the $_POST array.
	 */

	$error = ""; // Initialize error message

	foreach($var_array as $var) {
		if (isset($_POST[$var]) AND empty($_POST[$var])) {
			$error .= "<p>$var has not been set.</p>";
		}
	}

	return $error;

}

function handle_bp ($action) {
	/* This script handles blog post form based on whether $action is:
	- 'add'
	- 'update'

	'update' will require placeholders
	*/

	global $dbc;

	if ($action != 'add' && $action != 'update') { // check input
		print 'code error in display_bp. action can only be add or update';
		exit();
	}

	// Prepare the values for storing
	$title = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['title'])));
	$post = mysqli_real_escape_string($dbc, base64_encode($_POST['post']));


	// modify database here:

	if ($action == 'add') { // 'INSERT' command

		$query = "INSERT INTO blog_post (title, post) VALUES ('$title', '$post')";
		
	} else { // 'UPDATE' command

		$query = "UPDATE blog_post SET title='$title', post='$post' WHERE post_id={$_POST['post_id']}";
		
	}

	if ($r = mysqli_query($dbc, $query)) { // perform query

		print '<div class="alert alert-success"><p>Your blog post was submitted. View your post <a href="home.php">here</a></p></div>';

	} else { // Print error message

		print '<div class="alert alert-danger">
		<p>Could not submit your post because:<br />' . mysqli_error($dbc) .'.</p> 
		<p>The query being run was ' . $query . '</p></div>';

	} // end of add/update post

} // end of handle_bp function

function display_bp($action, $title='', $post='') { 
	// Displays blog post submission/update form 
	// Takes one $action arguments: 'update' or 'add'
	// Takes two additional arguments to display as placeholders if 'update' selected

	// Select button colour and text
	if ($action == 'add') {
		$btn_type = 'btn-success';
		$btn_text = 'Post!';
	} elseif ($action == 'update') {
		$btn_type = 'btn-warning';
		$btn_text = 'Update!';
	} else {
		print 'error in display_bp. only add or update actions allowed';
	}

	print '
		<form role="form" action="'. htmlentities($_SERVER['PHP_SELF']) .'" method="post">
			<div class="well form-group blog-post">
				<p><h3>Title</h3> <input type="text" name="title" class="form-control" value="' . htmlentities($title) . '" required autofocus></p>
				<p><h3>Post</h3> <textarea name="post" class="form-control ckeditor" rows="10" required>' . base64_decode($post) . '</textarea></p>
				<hr>
				<p class="text-center"><button type="submit" class="btn ' . $btn_type . '" name="submit">' . $btn_text . '</button></p>';

	if (isset($_GET['post_id'])) { // post number needed to modify
		print '<input type="hidden" name="post_id" value="' . $_GET['post_id'] . '" />';
	} elseif (isset($_POST['post_id'])) {
		print '<input type="hidden" name="post_id" value="' . $_POST['post_id'] . '" />';
	}

	print '</form>';

}


?>