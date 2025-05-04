<?php
include("adheader.php");


include("dbconnection.php");
if(isset($_POST['submit'])) {
    // 1) load current hash
    $stmt = mysqli_prepare($con,
      "SELECT password 
         FROM patient 
        WHERE patientid = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['patientid']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $currentHash);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // 2) verify old password
    if(!password_verify($_POST['oldpassword'], $currentHash)) {
        echo "<div class='alert alert-danger'>Old password is incorrect.</div>";
        exit;
    }

    // 3) hash new password
    $newHash = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);

    // 4) update with hashed password
    $upd = mysqli_prepare($con,
      "UPDATE patient 
          SET password = ?
        WHERE patientid = ?");
    mysqli_stmt_bind_param($upd, "si", $newHash, $_SESSION['patientid']);
    mysqli_stmt_execute($upd);

    if(mysqli_stmt_affected_rows($upd) === 1) {
        echo "<div class='alert alert-success'>Password has been updated successfully.</div>";
    } else {
        echo "<div class='alert alert-warning'>Update Failed</div>";
    }
    mysqli_stmt_close($upd);
}

?>

<div class="container-fluid">
    <div class="block-header">
        <h2 class="text-center"> Patient's Password</h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
               <form method="post" action="" name="frmpatchange" onSubmit="return validateform()"
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
function validateform()
{
	if(document.frmpatchange.oldpassword.value == "")
	{
		alert("Old password should not be empty..");
		document.frmpatchange.oldpassword.focus();
		return false;
	}
	else if(document.frmpatchange.newpassword.value == "")
	{
		alert("New Password should not be empty..");
		document.frmpatchange.newpassword.focus();
		return false;
	}
	else if(document.frmpatchange.newpassword.value.length < 6)
	{
		alert("New Password length should be more than 6 characters...");
		document.frmpatchange.newpassword.focus();
		return false;
	}
	else if(document.frmpatchange.newpassword.value != document.frmpatchange.password.value )
	{
		alert(" New Password and confirm password should be equal..");
		document.frmpatchange.password.focus();
		return false;
	}
	else
	{
		return true;
	}
}
</script>
