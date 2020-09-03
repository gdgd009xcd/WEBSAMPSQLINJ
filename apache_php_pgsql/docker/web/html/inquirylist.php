<?php

include 'env.php';
include 'clearSessionTokens.php';

session_start();
$step = 0;
$stepname = getenv('SCRIPT_FILENAME') . '.step';

if(isset($_SESSION['sqlprint'])) {
    $sqlprint = 1;
}

$subject = $_POST['subject'];
$contents = $_POST['contents'];
$token1 = $_POST['token1'];
$prevtoken1 = $_SESSION['token1'];

$search = $_POST['search'];

$errormess = "";
if ( $search === "1" ){
	if ( empty($token1) || $token1 !== $prevtoken1 ) {
		$errormess .= "invalid token1";
	}
	$searchsql = "";
	if (!empty($subject) ){
		$searchsql .= " AND subject LIKE '%" . $subject . "%'";
	}
	if (!empty($contents) ){
		$searchsql .= " AND contents LIKE '%" . $contents . "%'";
	}
}

if (isset($_POST['cancel']) || !empty($errormess) ) {
    unset($_SESSION[$stepname]);
    clearSessionTokens();
    header('location: mypage.php');
    exit(-1);
}

if (!isset($_SESSION[$stepname])){
    clearSessionTokens();
    $_SESSION[$stepname] = $step;
} else {
    $step = $_SESSION[$stepname] ;
}

$randomval = sha1(uniqid(rand(), true));
$_SESSION['token1'] = $randomval;

$link = pg_connect($dbconnectinfo);

if (!$link) {
    $ERRORMESS = pg_last_error($link);
    header('location: index.php?errormess=' . urlencode($ERRORMESS));
    exit(-1);
}



if( isset($_SESSION['user'])){
    $user = $_SESSION['user'];
}else {
    $ERRORMESS = "ERROR SESSION user no exist.";
    header('location: index.php?errormess=' . urlencode($ERRORMESS));
    exit(-1);
}


$sql = "SELECT *  FROM uploadlist where username ='" . $user . "'" . $searchsql;
$result = execsql($link, $sql);
?>

<html>
<head>
<title>
your registered entry list
</title>
</head>
<body>
<H2>Show your registered entries</H2>
<P> user:<?php echo $user; ?><P>
<?php
if ($sqlprint == 1) {
?>
    <P> SQL[<?php echo $sql; ?>]<P>
<?php
}
?>
<P> total entry list: <?php echo pg_numrows($result); ?> <P>
<form action="inquirylist.php" method = "POST">
<INPUT type="HIDDEN" name="search" value="1">
<input type="hidden" name="token1" value="<?php echo $randomval; ?>">
Subject:<INPUT type="text" name="subject" value="<?php echo $subject; ?>">
Contents:<INPUT type="text" name="contents" value="<?php echo $contents; ?>">
<input type="submit"  value="Search">
</form>


<table border="1">
<?php
$titleprint = 0;
for ($i = 0 ; $i < pg_num_rows($result) ; $i++){
    $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
    $keys = array_keys($rows);
    if ($titleprint == 0) {
        echo '<TR>';
        foreach($keys as $k) {
?>
            <th><?php echo $k;?></th>
<?php
        }
        echo "</TR>\n";
        $titleprint++;
    }
    echo '<TR>';
    foreach($rows as $k => $v) {
?>
        <td><?php echo $v?></td>
<?php
    }
    echo "</TR>\n";
?>
<?php
}
?>
</table>

<P>
<form action="inquirylist.php" method = "POST">
<INPUT type="HIDDEN" name="cancel" value="1">
<input type="submit"  value="Return to MYPAGE">
<input type="hidden" name="dummy" value="<?php echo $randomval; ?>">
</form>

<P>
</body>
</html>

<?php
exit(0);
?>
