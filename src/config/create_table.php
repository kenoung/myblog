<?php
/* This script is used by create_db.php to create a table.
You do not need to edit this script. */ 

	if ($dbc) {
		$query = 'CREATE TABLE blog_post (
			post_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			author_id INT UNSIGNED NOT NULL DEFAULT 1,
			title VARCHAR(100) NOT NULL,
			post TEXT NOT NULL,
			date_entered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (post_id))';

		// Execute the query
		if (@mysql_query($query, $dbc)) {
			print '<p>The table has been created!</p>';
		} else {
			print '<p style="color: red; ">Could not create the table because:<br />' . mysql_error($dbc) . '.</p><p>The query being run was ' . $query . '.</p>';
		}

	}
	?>
</body>
</html>

