<?php 

include 'env.php';

if(isset($_POST['user'])){
    $user = $_POST['user'];
}else{
    $user ="";
}

if(isset($_POST['pass'])){
    $pass = $_POST['pass'];
}else{
    $pass = "";
}


$DB = $_POST['DB'];


session_start();



if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST['sqlprint'])){
        $_SESSION['sqlprint'] = 1;
    }
    if(isset($_POST['printDummy'])){
        $_SESSION['printDummy'] = 1;
    }

    if(isset($_SESSION['sqlprint'])) {
        $sqlprint = 1;
    }

    unset($_SESSION['user']);
    if(!empty($DB) && $DB === "1" ){
        $link = pg_connect($dbconnectinfo);
        if (!$link) {
            $ERRORMESS = pg_last_error($link);
            header('location: index.php?errormess=' . urlencode($ERRORMESS));
            exit(-1);
        }

        // PostgreSQLに対する処理
        $username = $user;
        $password = $pass;
        $sql = "SELECT username,password  FROM account where username='" . $username . "' and  password='" . $password . "'" ;
        $result = pg_query($link, $sql);
        if (!$result) {
            $ERRORMESS = pg_last_error($link);
            header('location: index.php?errormess=' . urlencode($ERRORMESS));
            exit(-1);
        }
        if(pg_num_rows($result) >= 1 ){
            $_SESSION['user'] = $user;
        }
        pg_close($link);

        $_SESSION['DB'] = '1';

    }else if ( $user === "test" && $pass === "password"){
            $_SESSION['user'] = $user;
    }
}

if( isset($_SESSION['user'])){
	//already logged in..
	$user = $_SESSION['user'];
}else{
	$ERRORMESS = 'SESSION[\'user\'] no exist error.';
    header('location: index.php?errormess=' . urlencode($ERRORMESS));
	exit(-1);
}

if(isset($_GET['token1'])){
    if ( isset($_SESSION['token1'])){
        if($_GET['token1'] === $_SESSION['token1']){
            header('location: inquiry.php?token1=' . $_GET['token1']);
            exit(0);
        }
    }
}

$randomval = sha1(uniqid(rand(), true));
$htoken = sha1(uniqid(rand(), true));
$_SESSION['token1'] = $randomval;
$_SESSION['htoken'] = $htoken;




?>
<!DOCTYPE html>
<head>
<title>
MYPAGE
</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <script>
  $(document).ready(function() {
    /**
     * poppボタンクリック
     */
    $('#popup').click(function() {
      
      var data = {'request' : 1};

      
      $.ajax({
        type: "POST",
        url: "popup.php",
        headers: {
            'X-SPECIAL': '<?php echo $htoken; ?>',
        },

        data: data,
      }).done(function(data, dataType) {
        var w = window.open();
        $(w.document.body).html(data);
      }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        

        
        alert('Error : ' + errorThrown);
      });

      
      return false;
    });

    // JSON
    $('#json').click(function() {
      
      var data = {'request' : 1, 'X-SPECIAL': '<?php echo $htoken; ?>'};

      
      $.ajax({
        type: "POST",
        url: "json.php",
        data:JSON.stringify(data),
        contentType: 'application/json',
        dataType: "json"
      }).done(function(data, dataType) {
        alert(JSON.stringify(data));
      }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        

        
        alert('Error : ' + errorThrown);
      });

      
      return false;
    });
  });
  </script>
</head>
<body>
<H2>MYPAGE</H2>
<A HREF="help.html" target="_blank">HELP USAGE</A>
<P> welcome user:<?php echo $user; ?><P>
<?php
if ($sqlprint == 1) {
?>
    <P> SQL[<?php echo $sql; ?>]<P>
<?php
}
?>
<HR>

<form action="moduser.php" method="GET">
Modify your account info :
<input type="submit" value="Modify your account info">
<input type="HIDDEN" name="foo" value="x" />
</form><BR>
<form action="moduser.php" method="GET">
Modify your age :
<input type="hidden" name="nopassword" value="1">
<input type="submit" value="Modify your age">
</form>
<HR>

<form action="inquiry.php" method="GET">
<input type="hidden" name="token1" value="<?php echo $randomval; ?>">
Regist your entry :
<input type="submit"  value="Regist entry">
<input type="HIDDEN" name="foo" value="x" />
</form>
<HR>

<form action="inquirylist.php" method="GET">
Show your registered entries :
<input type="submit"  value="Show your registered Entry List">
<input type="HIDDEN" name="foo" value="x" />
</form>
<HR>
<form action="removeuser.php" method="GET">
Revoke your account :
<input type="submit" value="Revoke user">
<input type="HIDDEN" name="foo" value="x" />
</form>
<HR>
<!-- A HREF="mypage.php?token1=<?php echo $randomval; ?>">inquiry(Location) </A -->
<P>
<input id="popup" value="popuptest" type="submit" /></p>
<input id="json" value="json" type="submit" /></p>
<HR>
<A HREF="logout.php">logout</A>
</body>
</html>



