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
if (!$dbc) {
	print 'Unable to connect to ' . HOSTNAME . ', please set MySQL credentials in mysql_connect.php.';
} else {
	mysqli_set_charset($dbc, 'utf8');
}

?>
