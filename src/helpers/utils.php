<?php // Utility Helper Functions

#########################
## Check Access Rights ##
#########################

function is_administrator($name = 'School', $value = 'Computing') {
	// This function checks if the user is an author.
	// Check for user_id in $_SESSION
	// Takes in two optional inputs (relic of previous cookie implementation)

	if (isset($_SESSION['author_id'])) {
		return TRUE;
	} else {
		return FALSE;
	}

} // End of is_administrator() function.

function check_access($name = NULL, $value = NULL) {
	// This script takes no inputs. Checks if user has access rights to the page
	if (!is_administrator($name, $value)) {
		switch (basename($_SERVER['PHP_SELF'])) { 
			// Check current page:
			case 'add_post.php':
				$action = 'add a post';
				break;
			case 'delete_post.php':
				$action = 'delete a post';
				break;
			case 'edit_post.php':
				$action = 'edit a post';
				break;
			case 'categories.php':
				$action = 'manage categories';
				break;
			default:
				$action = 'have admin rights';
		}

		print '<div class="well"><h2>Access Denied!</h2>
		<p class="lead">Please <a href="login.php">log in</a> if you want to '. $action .'.</p>';
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

function available_categories() {
	/* This script displays available categories.*/

	global $dbc;

	// Define the query:
	$query = "SELECT cat_id, cat_name FROM categories";
	if ($r = mysqli_query($dbc, $query)) {
		while ($row = mysqli_fetch_array($r, MYSQL_ASSOC)) {
			$cat_list[$row['cat_id']] = $row['cat_name'];
		}
		if (isset($cat_list)) {
			return $cat_list;
		} else {
			return FALSE;
		}
		
	} else {
		// Query did not run
		echo '<p class="alert alert-warning">Could not retrieve categories because:<br />' . mysqli_error($dbc) . '.</p>
		<p>The query being run was: ' . $query . '</p>';
		return FALSE;
	}
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

	if ($_POST['cat_id']) {
		$cat_id = mysqli_real_escape_string($dbc,$_POST['cat_id']);
	} else {
		$cat_id = NULL;
	}

	// modify database here:

	if ($cat_id) {
		if ($action == 'add') { // 'INSERT' command

			$query = "INSERT INTO blog_post (title, post, author_id, cat_id) VALUES ('$title', '$post', '{$_SESSION['author_id']}', '$cat_id')";
			
		} else { // 'UPDATE' command

			$query = "UPDATE blog_post SET title='$title', post='$post', cat_id='$cat_id' WHERE post_id={$_POST['post_id']}";
			
		}
	} else {
		if ($action == 'add') { // 'INSERT' command

			$query = "INSERT INTO blog_post (title, post, author_id) VALUES ('$title', '$post', '{$_SESSION['author_id']}')";
			
		} else { // 'UPDATE' command

			$query = "UPDATE blog_post SET title='$title', post='$post', cat_id = NULL WHERE post_id={$_POST['post_id']}";
			
		}
	}

	if ($r = mysqli_query($dbc, $query)) { // perform query

		print '<div class="alert alert-success"><p>Your blog post was submitted. View your post <a href="home.php">here</a></p></div>';

	} else { // Print error message

		print '<div class="alert alert-danger">
		<p>Could not submit your post because:<br />' . mysqli_error($dbc) .'.</p> 
		<p>The query being run was ' . $query . '</p></div>';

	} // end of add/update post

} // end of handle_bp function

function display_bp($action, $title='', $post='', $cat_id=NULL) { 
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
		echo 'error in display_bp. only add or update actions allowed';
	}

	// Find available categories
	$cat_list = available_categories();

	echo '
		<form role="form" action="'. htmlentities($_SERVER['PHP_SELF']) .'" method="post">
			<div class="well form-group blog-post">
				<p><h3>Title</h3> <input type="text" name="title" class="form-control" value="' . htmlentities($title) . '" required autofocus></p>
				<p><h3>Post</h3> <textarea name="post" class="form-control ckeditor" rows="10" required>' . base64_decode($post) . '</textarea></p>
				<p><h3>Select Category:</h3>
					<select class="form-control" name="cat_id">
						<option value="0">Uncategorized</option>';
					foreach ($cat_list as $id => $name) {
						echo "\n\t\t\t\t\t\t<option value=\"$id\" ";
						if ($id == $cat_id) {
							echo 'selected="selected"';
						}
						echo ">$name</option>";
					}
					echo '
					</select>
				</p>
				<hr>
				<p class="text-center"><button type="submit" class="btn ' . $btn_type . '" name="submit">' . $btn_text . '</button></p>';

	if (isset($_GET['post_id'])) { // post number needed to modify
		print '<input type="hidden" name="post_id" value="' . $_GET['post_id'] . '" />';
	} elseif (isset($_POST['post_id'])) {
		print '<input type="hidden" name="post_id" value="' . $_POST['post_id'] . '" />';
	}

	print '</div></form>';

}


?>