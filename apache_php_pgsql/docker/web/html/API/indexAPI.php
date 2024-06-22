<?php

$ERRORMESS = $_GET['errormess'];

$DB = '1';
if (!empty($_GET['DB'])) {
    $DB = $_GET['DB'];
}

// initialize session.
// don't forget this if you use session_name("something") function.
session_start();

// clear all sessions.
$_SESSION = array();

// you must delete session cookie when disconnecting session 
// Note: must destroy session not only session informations.
if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time()-42000, '/');
}

// finally destroy session.
session_destroy();

$randomval = sha1(uniqid(rand(), true));

?>

<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<SCRIPT>
$(document).ready(function() {
	// JSON
	$('#login').click(function() {
      
		var user = $('#loginForm [name=user]').val();
		var pass = $('#loginForm [name=pass]').val();
		var data = {'request' : 1,  'user': user, 'pass': pass};
		var base64data = btoa(encodeURIComponent(JSON.stringify(data)));
		var getParams = {'base64' : base64data, 'DB':<?php echo $DB; ?>};

      
		$.ajax({
			type: "POST",
			url: "mypageAPI.php",
			data: getParams,
			// contentType default is 'application/x-www-form-urlencoded'
			//contentType: 'application/json',
			dataType: "json"
		}).done(function(data, dataType) {
			alert(JSON.stringify(data));
		}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
			alert('Error : ' + errorThrown);
		});
		return false;
	});
});
</SCRIPT>
<title>
login page
</title>
</head>
<body>

<H2>Image Entry System</H2>
<H3>
A sample php web application with SQL injection everywhere :).
<H4>
this page demonstrate to login with base64 and url encoded login parameter.<BR>
this page login button generates below base64 parameter from specified parameters.
<P>

<PRE>
example:

original JSON data:  {"request":1,"user":"demo","pass":"password"}
base64 parameter consists of base64 encoding and urlencoded JSON data.

base64=JTdCJTIycmVxdWVzdCUyMiUzQTElMkMlMjJ1c2VyJTIyJTNBJTIyZGVtbyUyMiUyQyUyMnBhc3MlMjIlM0ElMjJwYXNzd29yZCUyMiU3RA%3D%3D
</PRE>

</H3>
<?php
if (!empty($ERRORMESS)){
?>
<P><font color="red" ><?php echo $ERRORMESS; ?></font><P>
<?php
}
?>
<A HREF="../help.html" target="_blank">HELP USAGE</A><P>
<form id="loginForm" action="mypageAPI.php" method="POST">
user:<input type="text" name="user" value=""><BR>
pass:<input type="password" name="pass" value=""><BR>
<input type ="checkbox" name="sqlprint" value="Print SQL for debug">Check to display SQL statement on screen<BR>
<input type ="checkbox" name="printDummy" value="generate dummy for debug">Generate dummy data for debugging<BR>
<input type="hidden" name="dummy" value="<?php echo $randomval; ?>">
<?php

if($DB === '1'){
print "<input type=hidden name=\"DB\" value=\"" . $DB .  "\"><BR>";
}
?>
<P>
<P>
</form>
<P>
<input id="login" value="login" type="submit" /><p>
<A href="mypageAPI.php">after login succeeded, to proceed mypage, please click this.</A>

<P>
<?php
if( !empty($DB) ){
?>
<HR>
<form action="../newuser.php" method="GET">
<input type ="checkbox" name="sqlprint" value="Print SQL for debug">Check to display SQL statement on screen<BR>
<input type="submit" value="Regist New User">
<input type="hidden" name="dummy" value="<?php echo $randomval; ?>">
</form>
<HR>
<A HREF="../index.php">return to original login page</A>
<?php
}
?>
</body>
</html>
