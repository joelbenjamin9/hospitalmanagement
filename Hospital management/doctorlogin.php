<?php
session_start();
error_reporting(0);
include("dbconnection.php");

if (isset($_SESSION['doctorid'])) {
    echo "<script>window.location='doctoraccount.php';</script>";
    exit;
}

$err = '';
if (isset($_POST['submit'])) {
    $stmt = mysqli_prepare($con, "
        SELECT doctorid, password 
          FROM doctor 
         WHERE loginid = ? 
           AND status = 'Active'
        LIMIT 1
    ");
    mysqli_stmt_bind_param($stmt, "s", $_POST['loginid']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $doctorid, $hashFromDb);

    if (mysqli_stmt_fetch($stmt)) {
        if (password_verify($_POST['password'], $hashFromDb)) {
            $_SESSION['doctorid'] = $doctorid;
            echo "<script>window.location='doctoraccount.php';</script>";
            exit;
        }
    }

    $err = "<div class='alert alert-danger'>
                <strong>Oh !</strong> Invalid username or password.
            </div>";
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>HMS - Doctor Login</title>
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/login.css" rel="stylesheet">
    <link href="assets/css/themes/all-themes.css" rel="stylesheet" />
</head>
<body class="theme-cyan login-page authentication" style="background-image:url('images/login.jpeg');background-repeat: no-repeat;background-size: 100%">
<div class="container">
    <div id="err"><?php echo $err; ?></div>
    <div class="card-top"></div>
    <div class="card">
        <h1 class="title"><span>Hospital Management System</span>Login <span class="msg">Hello, Doctor!</span></h1>
        <div class="col-md-12">
            <form method="post" action="" name="frmdoctlogin" id="sign_in" onsubmit="return validateform()">
                <div class="input-group">
                    <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                    <div class="form-line">
                        <input type="text" name="loginid" id="loginid" class="form-control" placeholder="Username" />
                    </div>
                </div>
                <div class="input-group">
                    <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
                    <div class="form-line">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" />
                    </div>
                </div>
                <div class="text-center">
                    <input type="submit" name="submit" id="submit" value="Login" class="btn btn-raised waves-effect g-bg-cyan" />
                </div>
            </form>
        </div>
    </div>    
</div>
<script src="assets/bundles/libscripts.bundle.js"></script>
<script src="assets/bundles/vendorscripts.bundle.js"></script>
<script src="assets/bundles/mainscripts.bundle.js"></script>
<script type="application/javascript">
var alphanumericExp = /^[0-9a-zA-Z]+$/;
function validateform() {
    if (document.frmdoctlogin.loginid.value == "") {
        alert("Login ID should not be empty.");
        document.frmdoctlogin.loginid.focus();
        return false;
    }
    if (!document.frmdoctlogin.loginid.value.match(alphanumericExp)) {
        alert("Login ID not valid.");
        document.frmdoctlogin.loginid.focus();
        return false;
    }
    if (document.frmdoctlogin.password.value == "") {
        alert("Password should not be empty.");
        document.frmdoctlogin.password.focus();
        return false;
    }
    if (document.frmdoctlogin.password.value.length < 8) {
        alert("Password length should be at least 8 characters.");
        document.frmdoctlogin.password.focus();
        return false;
    }
    return true;
}
</script>
</body>
</html>
