<?php

// initialize session
// DO NOT forget it if you use function session_name("something")
session_start();

// delete all SESSION array.
$_SESSION = array();

// to destroy sessin, delete cookies also.
if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time()-42000, '/');
}

// after all destroy session.
session_destroy();

header("Location:indexAPI.php");
exit(0);

?>
