<?php

$ERRORMESS = $_GET['errormess'];

$DB = '1';
if (!empty($_GET['DB'])) {
    $DB = $_GET['DB'];
}

// セッションの初期化
// session_name("something")を使用している場合は特にこれを忘れないように!
session_start();

// セッション変数を全て解除する
$_SESSION = array();

// セッションを切断するにはセッションクッキーも削除する。
// Note: セッション情報だけでなくセッションを破壊する。
if (isset($_COOKIE[session_name()])) {
setcookie(session_name(), '', time()-42000, '/');
}

// 最終的に、セッションを破壊する
session_destroy();

$randomval = sha1(uniqid(rand(), true));

?>

<html>
<head>
<title>
login page
</title>
</head>
<body>

<H2>Image Entry System</H2>
<H3>
A sample php web application with SQL injection everywhere :).
</H3>
<?php
if (!empty($ERRORMESS)){
?>
<P><font color="red" ><?php echo $ERRORMESS; ?></font><P>
<?php
}
?>
<A HREF="help.html" target="_blank">HELP USAGE</A><P>
<form action="mypage.php" method="POST">
user:<input type="text" name="user" value=""><BR>
pass:<input type="password" name="pass" value=""><BR>
<input type ="checkbox" name="sqlprint" value="Print SQL for debug">Check to display SQL statement on screen<BR>
<input type="hidden" name="dummy" value="<?php echo $randomval; ?>">
<?php

if($DB === '1'){
print "<input type=hidden name=\"DB\" value=\"" . $DB .  "\"><BR>";
}
?>
<input type="submit" value="Login">
</form>
<?php
if( !empty($DB) ){
?>
<HR>
<form action="newuser.php" method="GET">
<input type ="checkbox" name="sqlprint" value="Print SQL for debug">Check to display SQL statement on screen<BR>
<input type="submit" value="Regist New User">
<input type="hidden" name="dummy" value="<?php echo $randomval; ?>">
</form>
<HR>
<?php
}
?>
</body>
</html>
