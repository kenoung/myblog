<?php // Utility Helper Functions

// This function checks if the user is an administrator.
// This function takes two optional values.
// This function returns a Boolean value.
function is_administrator($name = 'School', $value='Computing') {

	// Check for the cookie and check it's value
	if (isset($_COOKIE[$name]) && ($_COOKIE[$name] == $value)) {
		return true;
	} else {
		return false;
	}
} // End of is_administrator() function.

?>