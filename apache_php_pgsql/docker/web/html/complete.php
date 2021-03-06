<?php

session_start();

include 'env.php';
include 'clearSessionTokens.php';

if (isset($_POST['cancel'])) {
    clearSessionTokens();
    unset($_SESSION['subject']);
    unset($_SESSION['contents']);
    unset($_SESSION['mailaddr']);
    unset($_SESSION['imgfile']);
    unset($_SESSION['tmp_path']);
    unset($_SESSION['showfile']);
    unset($_SESSION['filetype']);
    header('location: mypage.php');
    exit(-1);
}

if(isset($_SESSION['sqlprint'])) {
    $sqlprint = 1;
}

if( isset($_SESSION['user'])){
	//already logged in..
	$user = $_SESSION['user'];
}else{
	header('location: index.php');
	exit(-1);
}

$prevtoken = $_POST['token3'];
$chktoken = $_SESSION['token3'];

if($prevtoken !== $chktoken ){
    header('location: index.php');
    exit(-1);
}

$randomval = sha1(uniqid(rand(), true));

if( isset($_SESSION['DB'])){
    $DB = "1";
} else {
    $DB ="";
}

if(isset($_SESSION['subject'])){
    $subject = $_SESSION['subject'];
}else{
    $subject = "";
}

if(isset($_SESSION['contents'])){
    $contents = $_SESSION['contents'];
}else{
    $contents = "";
}

if(isset($_SESSION['mailaddr'])){
    $mailaddr = $_SESSION['mailaddr'];
}else{
    $mailaddr = "";
}
if(isset($_SESSION['imgfile'])){
    $imgfile = $_SESSION['imgfile'];
}else{
    $imgfile = "";
}

if(isset($_SESSION['showfile'])){
    $showfile = $_SESSION['showfile'];
}else{
    $showfile = "";
}

if ( empty($subject) ||
     empty($contents) ||
     empty($imgfile) ||
     empty($mailaddr) ){
     $_SESSION['token1'] = $randomval;
     header('Location: inquiry.php?token1=' . $randomval);
    exit(0);
}
$imglink = "<A HREF=\"" . $showfile . "\">" . $imgfile . "</A>";
$savepath = session_save_path();
$mailfile = $savepath . "/mail.txt";
$oldmail = file_put_contents($mailfile, $mailaddr);

if(!empty($DB) && $DB === "1" ){
        $link = pg_connect($dbconnectinfo);

        if (!$link) {
            $ERRORMESS = pg_last_error($link);
            header('location: index.php?errormess=' . urlencode($ERRORMESS));
            exit(-1);
        }

	$sql1 = "SELECT *  FROM uploadlist" ;
	$result = execsql($link, $sql1);
	if( pg_num_rows($result) <= 0 ){
		$sql2 = "SELECT setval('inquiryno', 1)" ;
		$result = execsql($link, $sql2);
	}

        $sql3 = "INSERT INTO uploadlist  VALUES('"
         . substr($user, 0, 50) . "','"
         . substr($mailaddr,0,250) . "','"
         . substr($subject,0,250) . "','"
         . substr($contents,0,1000) . "','"
         . substr($oldmail,0,250) . "','"
         . substr($imglink,0,500) . "','"
         . substr($imgfile,0,500) . "',nextval('inquiryno'))";
	$result = execsql($link, $sql3);
	pg_close($link);
}

?>
<html>
<head>
<title>
Entry completed
</title>
</head>
<body>
<H2>Entry registered.</H2>
<P> user:<?php echo $user; ?>

<?php
if ($sqlprint == 1) {
?>
    <P> SQL1[<?php echo $sql1; ?>]<P>
<?php
if ( !empty($sql2) ){
?>
    <P> SQL2[<?php echo $sql2; ?>]<P>
<?php
}
?>
    <P> SQL3[<?php echo $sql3; ?>]<P>
<?php
}
?>
<?php
echo "<P>fileput result:" . $oldmail . "<P><BR>";
?>
We accepted your entry with the contents below. <BR>
<table border="1">
<tr>
<th>Subject</th><td><?php echo $subject; ?></td>
</tr>
<tr>
<th>Contents</th><td><?php echo $contents; ?></td>
</tr>
<tr>
<th>mailaddr</th><td><?php echo $mailaddr; ?></td>
</tr>
<tr>
<th>file</th><td><A HREF="<?php echo $showfile; ?>" ><?php echo $imgfile; ?></A></td>
</tr>
</table><BR>

<form action="complete.php" method = "POST">
<INPUT type="HIDDEN" name="cancel" value="1">
<input type="submit"  value="Return to MYPAGE">
<input type="hidden" name="dummy" value="<?php echo $randomval; ?>">
</form><BR>

<P>
<A HREF="logout.php">logout</A>

</body>
</html>



