<?php

include 'env.php';
include 'clearSessionTokens.php';

session_start();

$step = 0;
$stepname = getenv('SCRIPT_FILENAME') . '.step';

if (!isset($_SESSION[$stepname])){
    clearSessionTokens();
    $_SESSION[$stepname] = $step;
} else {
    $step = $_SESSION[$stepname] ;
}

if(isset($_SESSION['sqlprint'])) {
    $sqlprint = 1;
}

$link = pg_connect($dbconnectinfo);

if (!$link) {
    $ERRORMESS = pg_last_error($link);
    header('location: index.php?errormess=' . urlencode($ERRORMESS));
    exit();
}

if (isset($_POST['cancel'])) {
    unset($_SESSION[$stepname]);
    clearSessionTokens();
    header('location: mypage.php');
    exit(-1);
}

if( isset($_SESSION['user']) && !empty($_SESSION['user'])){
	//already logged in..
	$user = $_SESSION['user'];
}else{
	$ERRORMESS = 'login failed';
        header('location: index.php?errormess=' . urlencode($ERRORMESS));
	exit(-1);
}

$randomval = sha1(uniqid(rand(), true));

$token1 = $_POST['token1'];
$prevtoken1 = $_SESSION['token1'];

$revoke = $_POST['revoke'];

if (empty($revoke) && $step > 0 ){
	$step = 0;
    	$_SESSION[$stepname] = $step;
	
}

if ( $step == 0 ){
	$step = 1;
    	$_SESSION[$stepname] = $step;
    	$_SESSION['token1'] = $randomval;

?>
<html>
<head>
<tltle>
Revoke user
</tltle>
</head>
<body>

<P>
user: <?php echo $user; ?>
<P>
<form action="removeuser.php" method="POST">
<input type="hidden" name="token1" value="<?php echo $randomval; ?>">
<input type="checkbox" name="revoke" value="revoke">Check if Confirmed revoke.<BR>
<input type="submit"  value="Revoke">
</form>
<P>
<form action="removeuser.php" method = "POST">
<INPUT type="HIDDEN" name="cancel" value="1">
<input type="submit"  value="Cancel">
</form>
<P>
<P>
<A HREF="logout.php">logout</A>
</body>
</html>
<?php
	exit(0);
} else {
	if ( empty($token1) || $token1 !== $prevtoken1 ){
            $ERRORMESS = 'invalid token1 value';
            header('location: index.php?errormess=' . urlencode($ERRORMESS));
            exit(-1);
	}
        $sql1 = "DELETE FROM account  WHERE username = '" . $user . "'";
        $result = pg_query($link, $sql1);
        if (!$result) {
            $ERRORMESS = pg_last_error($link);
            header('location: index.php?errormess=' . urlencode($ERRORMESS));
            exit(-1);
        }
        $sql2 = "DELETE FROM uploadlist  WHERE username = '" . $user . "'";
        $result = pg_query($link, $sql2);
        if (!$result) {
            $ERRORMESS = pg_last_error($link);
            header('location: index.php?errormess=' . urlencode($ERRORMESS));
            exit(-1);
        }
        pg_close($link);
        // セッション変数を全て解除する
        $_SESSION = array();

        // セッションを切断するにはセッションクッキーも削除する。
        // Note: セッション情報だけでなくセッションを破壊する。
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-42000, '/');
        }

        // 最終的に、セッションを破壊する
        session_destroy();
?>
<html>
<head>
<tltle>
Revoke user completed
</tltle>
</head>
<body>
<P> Your user account has Revoked. Good-bye.
<P> user:<?php echo $user; ?>
<?php
if ($sqlprint == 1) {
?>
    <P> SQL[<?php echo $sql1; ?>]<P>
    <P> SQL[<?php echo $sql2; ?>]<P>
<?php
}
?>
<P>
<input type="hidden" name="dummy" value="<?php echo $randomval; ?>">
<A HREF="index.php?DB=1">Login</A>
</body>
</html>
<?php
}
?>


