<?php
/* This script is used by create_db.php to create a table.
You do not need to edit this script. */ 

	if ($dbc) {

		// Create authors table
		$query = 'CREATE TABLE authors (
			author_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			first_name VARCHAR(40) NOT NULL,
			last_name VARCHAR(40) NOT NULL,
			email VARCHAR(60) NOT NULL,
			password CHAR(255) NOT NULL, 
			registration_date DATETIME NOT NULL DEFAULT NOW(),
			PRIMARY KEY (author_id),
			UNIQUE KEY (email),
			INDEX login(email, password))';

		// Execute the query
		if (@mysql_query($query, $dbc)) {
			print '<p>The authors table has been created!</p>';
		} else {
			print '<p style="color: red; ">Could not create the table because:<br />' . mysql_error($dbc) . '.</p><p>The query being run was ' . $query . '.</p>';
		}


		// Create categories table
		$query = 'CREATE TABLE categories (
			cat_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			cat_name VARCHAR(40) NOT NULL,
			PRIMARY KEY (cat_id))';

		// Execute the query
		if (@mysql_query($query, $dbc)) {
			print '<p>The categories table has been created!</p>';
		} else {
			print '<p style="color: red; ">Could not create the table because:<br />' . mysql_error($dbc) . '.</p><p>The query being run was ' . $query . '.</p>';
		}


		// Create blog_post table
		$query = 'CREATE TABLE blog_post (
			post_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			author_id INT UNSIGNED NOT NULL,
			title VARCHAR(100) NOT NULL,
			post TEXT NOT NULL,
			date_entered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			cat_id INT UNSIGNED NOT NULL,
			PRIMARY KEY (post_id),
			FOREIGN KEY (author_id) REFERENCES authors(author_id) 
			ON DELETE CASCADE,
			FOREIGN KEY (cat_id) REFERENCES categories(cat_id) 
			ON DELETE CASCADE)';

		// Execute the query
		if (@mysql_query($query, $dbc)) {
			print '<p>The blog_post table has been created!</p>';
		} else {
			print '<p style="color: red; ">Could not create the table because:<br />' . mysql_error($dbc) . '.</p><p>The query being run was ' . $query . '.</p>';
		}

	}
	?>
</body>
</html>

