<?php

    session_start();

    $sqluser = "root";
    $sqlpassword = "adminroot";
    $sqldatabase = "myDB";

    $post = $_SERVER['REQUEST_METHOD']=='POST';

    if ($post) {
        if(
            empty($_POST['uname'])||
			empty($_POST['name'])||
            empty($_POST['phone'])||
            empty($_POST['pass'])||
            empty($_POST['repass'])
        ) $empty_fields = true;

        else {
            $unmatch = preg_match('/^[A-Za-z][A-Za-z0-9_]{3,}$/', $_POST['uname']);
			$nmatch = preg_match('/^[A-Za-z_ -]+$/', $_POST['name']);
			$phmatch = preg_match('/^[0-9]+$/', $_POST['phone']);
            $pmatch = preg_match('/.{5,}/',$_POST['pass']);
            $peq = $_POST['pass']==$_POST['repass'];
            if($unmatch&&$nmatch&&$phmatch&&$pmatch&&$peq) {
                require "conn.php";
                $st = $pdo->prepare('SELECT * FROM accounts WHERE username=?');
                $st->execute(array($_POST['uname']));
                $uname_err = $st->fetch() != null;
				$st = $pdo->prepare('SELECT * FROM members WHERE name=?');
                $st->execute(array($_POST['name']));
                $name_err = $st->fetch() != null;
				$st = $pdo->prepare('SELECT * FROM members WHERE phone=?');
                $st->execute(array($_POST['phone']));
                $phone_err = $st->fetch() != null;
                if(!$uname_err&&!$phone_err) {
                    $stmt = 'INSERT INTO accounts(username,password) VALUES (?,?)';
                    $pdo->prepare($stmt)->execute(array(
                        $_POST['uname'],
                        $_POST['pass']
                    ));
					
					$stmtM = 'INSERT INTO members(name,phone) VALUES (?,?)';
                    $pdo->prepare($stmtM)->execute(array(
                        $_POST['name'],
                        $_POST['phone']
                    ));
					
                    $_SESSION["uname"] = $_POST["uname"];
                    $_SESSION["pass"] = $_POST["pass"];
					$_SESSION["name"] = $_POST["name"];
                    $_SESSION["phone"] = $_POST["phone"];
                    header("Location:index.php");
                    exit;
                }
            }
        }
    }
?>

<!DOCTYPE HTML>
<html>
<meta charset="UTF-8">
<head>
<link rel="stylesheet" href="signupCSS.css">
</head> 
<body>
<div>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <p>SignUp</p>
    <?php
    echo '<br>Username<br><input type="text" name="uname" placeholder="Username"><br>';
    if($post&&!$empty_fields&&!$unmatch) echo '<span>Username can contain alphabet letters, numbers and underscore(_), but must begin with a letter. It must be at least 4 character long.<br></span>';
    if(!empty($uname_err)&&$uname_err) echo '<span>Username taken. Try another username.</span>';
	echo '<br>Name<br><input type="text" name="name" placeholder="Name"><br>';
	if($post&&!$empty_fields&&!$nmatch) echo '<span>Name can only contain alphabet letters.<br></span>';
	echo '<br>Phone number<br><input type="number" name="phone" placeholder="Phone number"><br>';
    if($post&&!$empty_fields&&!$phmatch) echo '<span>Phone number can only contain digits.<br></span>';
	echo '<br>Password<br><input type="password" name="pass" placeholder="Password"><br>';
    echo '<input type="password" name="repass" placeholder="Confirm password">';
    if($post&&!$empty_fields&&!$pmatch) echo '<span>Password must be at least 5 character long</span>';
    if($post&&!$empty_fields&&$pmatch&&!$peq) echo '<span>Password doesnt\'t match</span><br>';
    if($post &&$empty_fields) echo "<br><span>Please fill all the fields completely.</span><br>";
    ?>
    <br>
    <input type="submit" id="submit" value="SignUp"><br><br>
    Already have an account? <a href="index.php">LogIn</a>.<br><br>
</form>
</div>
</body>
</html>