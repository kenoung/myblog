<?php

// Include the header:
define('TITLE', 'Categories');
include('common/header.html');

// Must sign in for access:
check_access();

// Delete a category
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	// Handle the form
	if (isset($_GET['cat_id']) && is_numeric($_GET['cat_id']) && ($_GET['cat_id'] > 0)) {
		// Need database connection
		include_once("../config/mysql_connect.php");
		
		// Define the query:
		$query = "DELETE FROM categories WHERE cat_id={$_GET['cat_id']} LIMIT 1";
		$r = mysqli_query($dbc, $query); // Execute the query

		// Report on the result:
		if (mysqli_affected_rows($dbc) == 1) {

			print '<div class="alert alert-success"><p>The category has been deleted.</p></div>';
		
		} else {

			print '<div class="alert alert-danger">
			<p>Could not delete the category because:<br>' . mysqli_error($dbc) . '.</p>
			<p>The query being run was ' . $query . '</p></div>';

		}

	} 

}

// Add a new category
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Handle the form
	if (isset($_POST['cat_name']) && !empty($_POST['cat_name'])) {
		// Need database connection
		include_once("../config/mysql_connect.php");

		$cat_name = mysqli_real_escape_string($dbc, $_POST['cat_name']);

		// Check if it already exists:
		$query = "SELECT cat_name FROM categories WHERE cat_name='{$_POST['cat_name']}'";
		if ($r = mysqli_query($dbc,$query)) {
			$exists = mysqli_num_rows($r);
		} else {
			echo '<p class="alert alert-warning">Could not retrieve the data because:<br>' . mysqli_error($dbc) . '.</p>
			<p>The query being run was: ' . $query . '</p>';
		}

		if ($exists) {
			echo '<p class="alert alert-warning">This category is already created.</p>';
		} else {
			// Define the query
			$query = "INSERT INTO categories (cat_name) VALUES ('$cat_name')";

			if ($r = mysqli_query($dbc,$query)) {
				echo '<p class="alert alert-success">You have added a new category!</p>';
			} else {
				echo '<p class="alert alert-warning">Could not retrieve the data because:<br>' . mysqli_error($dbc) . '.</p>
				<p>The query being run was: ' . $query . '</p>';
			}
		}

	} else {
		echo '<p class="alert alert-warning">Please enter a valid category name.</p>';
	}

} 

// Display form for adding new categories
?>

<div class="row">
	<div class="col-sm-4"></div>
	<div class="col-sm-4 well">
		<form class="form-inline text-center" role="form" action="categories.php" method="post">
			<div class="form-group">
				<label class="control-label sr-only" for="cat_name">New Category Name:</label>
		    	<input type="text" class="form-control" name="cat_name" id="cat_name" placeholder="Suggest A New Category" required>
			</div>
			&nbsp;
			&nbsp;
			<button type="submit" class="btn btn-default">Submit</button>

		</form>
	</div>
	<div class="col-sm-4"></div>
</div>


<?php

// Display list of categories

// Need database connection
include_once("../config/mysql_connect.php");

// Define the query
$query = 'SELECT cat_id, cat_name FROM categories ORDER BY cat_id';
// Run the query:
if ($r = mysqli_query($dbc,$query)) {

	$no = 1; // Instantiate row count

	// Start the table
	echo '<div class="row">
	<div class="col-sm-3"></div>
	<div class="well col-sm-6">
		<table class="table text-center">
		    <thead>
		    	<tr>
			        <th class="text-center">No.</th>
			        <th class="text-center">Category Name</th>
			        <th class="text-center">No. of Posts</th>
			        <th class="text-center">Delete Category</th>
			        <th class="text-center">Related Posts</th>
		      	</tr>
		    </thead>
		    <tbody>';

	if (mysqli_num_rows($r) != 0) {

		// Retrieve the returned records:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

			// Find number of posts in this category
			$query2 = "SELECT post_id FROM blog_post WHERE cat_id={$row['cat_id']}";
			if ($r2 = mysqli_query($dbc,$query2)) {
				$post_count = mysqli_num_rows($r2);
			} else {
				echo '<p class="alert alert-warning">Could not retrieve the data because:<br>' . mysqli_error($dbc) . '.</p>
				<p>The query being run was: ' . $query2 . '</p>';
			}


			// print the record:
			echo '<tr>
			        <td>'.$no.'</td>
			        <td>'.$row['cat_name'].'</td>
			        <td>'.$post_count.'</td>
			        <td>'."<a href=\"categories.php?cat_id={$row['cat_id']}\" class=\"btn btn-default\">Delete</a>".'</td>
			        <td>'."<a href=\"home.php?cat_id={$row['cat_id']}\" class=\"btn btn-default\">View</a>".'</td>
			    </tr>
			 ';

			$no++; // increment counter


		} // End of while loop.


	} else {
		echo '<div class="alert alert-warning lead text-center">No categories have been created.</div>';
	}

	
	// Find number of uncategorized posts
	$query2 = "SELECT post_id FROM blog_post WHERE cat_id IS NULL";
	if ($r2 = mysqli_query($dbc,$query2)) {
		$post_count = mysqli_num_rows($r2);
	} else {
		echo '<p class="alert alert-warning">Could not retrieve the data because:<br>' . mysqli_error($dbc) . '.</p>
		<p>The query being run was: ' . $query2 . '</p>';
	}

	// End the table
	echo '
			<tr>
		        <td>'.$no.'</td>
		        <td>Uncategorized</td>
		        <td>'.$post_count.'</td>
		        <td>'."<button class=\"btn btn-default\" disabled>Delete</button>".'</td>
		        <td>'."<a href=\"home.php?cat_id=0\" class=\"btn btn-default\">View</button>".'</td>
		    </tr>
		</tbody>
		<p class="text-center text-muted">Deleting a category will send all current posts in that category to "Uncategorized".</p>
		</table>
	</div>
	<div class="col-sm-3"></div>
	</div>';

} else { // Query didn't run
	print '<p class="alert alert-warning">Could not retrieve the data because:<br />' . mysqli_error($dbc) . '.</p>
	<p>The query being run was: ' . $query . '</p>';

} // End of query IF.



include('common/footer.html');


?>
