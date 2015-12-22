<?php 
/* Please set your hostname, username, password, database name here */

// Set database access information
DEFINE ('HOSTNAME', 'localhost'); 
DEFINE('USERNAME', 'root');
DEFINE('PASSWORD', '');
DEFINE('DBNAME', 'myblog');

// Connect:
$dbc = @mysqli_connect (HOSTNAME, USERNAME, PASSWORD, DBNAME);

// If cannot connect:
if ($dbc) {
	print '<div class="alert alert-danger">Unable to connect to ' . HOSTNAME . ', please create your database <a href="../config/db_setup.php">here</a> AND set MySQL credentials in mysql_connect.php.</div>';
} else {
	mysqli_set_charset($dbc, 'utf8');
}

?>
