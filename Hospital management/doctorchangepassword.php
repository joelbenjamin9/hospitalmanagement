<?php

include("adheader.php");
include("dbconnection.php");
if(isset($_POST['submit'])) {
    $stmt = mysqli_prepare($con,
        "SELECT password 
           FROM doctor 
          WHERE doctorid = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['doctorid']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $currentHash);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if(password_verify($_POST['oldpassword'], $currentHash)) {
        $newHash = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);

        $upd = mysqli_prepare($con,
            "UPDATE doctor 
                SET password = ?
              WHERE doctorid = ?");
        mysqli_stmt_bind_param($upd, "si", $newHash, $_SESSION['doctorid']);
        mysqli_stmt_execute($upd);
        if(mysqli_stmt_affected_rows($upd) === 1) {
            echo "<script>alert('Password has been updated successfully.');</script>";
        } else {
            echo "<script>alert('Password update failed.');</script>";
        }
        mysqli_stmt_close($upd);
    }
    else {
        echo "<script>alert('Old password is incorrect.');</script>";
    }
}

?>

<div class="container-fluid">
    <div class="block-header">
        <h2 class="text-center"> Doctor's Password</h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <form method="post" action="" name="frmdoctchangepass" onSubmit="return validateform()"
                    style="padding: 10px">
                    <div class="form-group">
                        <label>Old Password</label>
                        <div class="form-line">
                            <input class="form-control" type="password" name="oldpassword" id="oldpassword" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <div class="form-line">
                            <input class="form-control" type="password" name="newpassword" id="newpassword" />

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="form-line">
                            <input class="form-control" type="password" name="password" id="password" />
                        </div>
                    </div>

                    <input class="btn btn-raised g-bg-cyan" type="submit" name="submit" id="submit" value="Submit" />


                </form>
                <p>&nbsp;</p>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
</div>
<?php
include("adfooter.php");
?>
<script type="application/javascript">
function validateform1() {
    if (document.frmdoctchangepass.oldpassword.value == "") {
        alert("Old password should not be empty..");
        document.frmdoctchangepass.oldpassword.focus();
        return false;
    } else if (document.frmdoctchangepass.newpassword.value == "") {
        alert("New Password should not be empty..");
        document.frmdoctchangepass.newpassword.focus();
        return false;
    } else if (document.frmdoctchangepass.newpassword.value.length < 8) {
        alert("New Password length should be more than 8 characters...");
        document.frmdoctchangepass.newpassword.focus();
        return false;
    } else if (document.frmdoctchangepass.newpassword.value != document.frmdoctchangepass.password.value) {
        alert(" New Password and confirm password should be equal..");
        document.frmdoctchangepass.password.focus();
        return false;
    } else {
        return true;
    }
}
</script>