<?php

$dbconnectinfo = "host=db dbname=testdb port=5432 user=test password=password";

function execsql($link, $sql) {
	$result = pg_query($link, $sql);
	if (!$result) {
	  $ERRORMESS = pg_last_error($link);
	  header('location: index.php?errormess=' . urlencode($ERRORMESS));
	  exit(-1);
	}
	return $result;
}

?>
