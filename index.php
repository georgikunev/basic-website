<?php

    session_start();

    $sqluser = "root";
    $sqlpassword = "adminroot";
    $sqldatabase = "myDB";


    $post = $_SERVER['REQUEST_METHOD']=='POST';
    if ($post) {
        if(
            empty($_POST['uname'])||
            empty($_POST['pass'])
        ) $empty_fields = true;

        else {
                require "conn.php";

                $st = $pdo->prepare('SELECT * FROM accounts WHERE username=?');
                $st->execute(array($_POST['uname']));
                $r=$st->fetch();
				
                if($r != null && $r["password"]==$_POST['pass']) {
                    echo $_POST["uname"];
                    echo $_POST["pass"];
                    $_SESSION["uname"] = $_POST["uname"];
					$usrname = $_SESSION["uname"];
                    $_SESSION["pass"] = $_POST["pass"];
                    echo $_SESSION["uname"];
                    echo $_SESSION["pass"];
					$sql = $pdo->prepare("UPDATE accounts SET lastEntered = NOW() WHERE username = '$usrname'");
					$sql->execute();
                    header("Location:homePage.html");
					
                    exit;
					
                } else $login_err = true;
        }
				
    }
?>

<!DOCTYPE HTML>
<html>
<meta charset="UTF-8">
<head>
<link rel="stylesheet" href="indexCSS.css">
</head> 
<body>
<div>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <p>LogIn</p>
    <?php 
    echo 'Username<br><input type="text" name="uname" placeholder="Username"><br>';
    echo '<br>Password<br><input type="password" name="pass" placeholder="Password"><br>';
    if(!empty($login_err)&&$login_err) echo "<span>Wrong username or password.</span>";
    if(!empty($empty_fields)&&$empty_fields) echo "<span>Please enter username and password.</span>";
    ?>
    <br>
    <input type="submit" id="submit" value="LogIn"><br><br>
    Don't have an account? <a href="signup.php">SignUp</a>.<br><br>
</form>
</div>
</body>
</html>