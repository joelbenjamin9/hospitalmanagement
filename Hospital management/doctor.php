<?php
include("adheader.php");
include("dbconnection.php");

if(isset($_POST['submit'])) {
    $rawPassword    = $_POST['password'];
    $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

    if(isset($_GET['editid'])) {
        $sql = "UPDATE doctor 
                   SET doctorname         = ?,
                       mobileno           = ?,
                       departmentid       = ?,
                       loginid            = ?,
                       password           = ?,
                       status             = ?,
                       education          = ?,
                       experience         = ?,
                       consultancy_charge = ?
                 WHERE doctorid = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "ssissssiii",
            $_POST['doctorname'],
            $_POST['mobilenumber'],
            $_POST['select3'],
            $_POST['loginid'],
            $hashedPassword,
            $_POST['select'],
            $_POST['education'],
            $_POST['experience'],
            $_POST['consultancy_charge'],
            $_GET['editid']
        );
        if(mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Doctor record updated successfully...');</script>";
        } else {
            echo mysqli_error($con);
        }
    } else {
        $sql = "INSERT INTO doctor
                   (doctorname, mobileno, departmentid, loginid, password, status, education, experience, consultancy_charge)
                VALUES (?, ?, ?, ?, ?, 'Active', ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "ssisssii",
            $_POST['doctorname'],
            $_POST['mobilenumber'],
            $_POST['select3'],
            $_POST['loginid'],
            $hashedPassword,
            $_POST['education'],
            $_POST['experience'],
            $_POST['consultancy_charge']
        );
        if(mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Doctor record inserted successfully...');</script>";
        } else {
            echo mysqli_error($con);
        }
    }
}

if(isset($_GET['editid'])) {
    $sql   = "SELECT * FROM doctor WHERE doctorid = ?";
    $stmt  = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_GET['editid']);
    mysqli_stmt_execute($stmt);
    $result  = mysqli_stmt_get_result($stmt);
    $rsedit  = mysqli_fetch_array($result);
}
?>

<div class="container-fluid">
    <div class="block-header">
        <h2 class="text-center"> Add New Doctor </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <form method="post" action="" name="frmdoct" onSubmit="return validateform()" style="padding: 10px">
                    <div class="form-group">
                        <label>Doctor Name</label> 
                        <div class="form-line">
                            <input class="form-control" type="text" name="doctorname" id="doctorname"
                                   value="<?php echo isset($rsedit['doctorname']) ? $rsedit['doctorname'] : ''; ?>" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Mobile Number</label> 
                        <div class="form-line">
                            <input class="form-control" type="text" name="mobilenumber" id="mobilenumber"
                                   value="<?php echo isset($rsedit['mobileno']) ? $rsedit['mobileno'] : ''; ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Department</label> 
                        <div class="form-line">
                            <select name="select3" id="select3" class="form-control show-tick">
                                <option value="">Select</option>
                                <?php
                                $sqldepartment = "SELECT * FROM department WHERE status='Active'";
                                $qsqldepartment = mysqli_query($con, $sqldepartment);
                                while($rsdepartment = mysqli_fetch_array($qsqldepartment)) {
                                    $sel = (isset($rsedit['departmentid']) && $rsdepartment['departmentid'] == $rsedit['departmentid'])
                                           ? "selected" : "";
                                    echo "<option value='{$rsdepartment['departmentid']}' $sel>{$rsdepartment['departmentname']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Login ID</label> 
                        <div class="form-line">
                            <input class="form-control" type="text" name="loginid" id="loginid"
                                   value="<?php echo isset($rsedit['loginid']) ? $rsedit['loginid'] : ''; ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Password</label> 
                        <div class="form-line">
                            <input class="form-control" type="password" name="password" id="password" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label> 
                        <div class="form-line">
                            <input class="form-control" type="password" name="cnfirmpassword" id="cnfirmpassword" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Highest Education</label> 
                        <div class="form-line">
                            <input class="form-control" type="text" name="education" id="education"
                                   value="<?php echo isset($rsedit['education']) ? $rsedit['education'] : ''; ?>" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Experience</label> 
                        <div class="form-line">
                            <input class="form-control" type="text" name="experience" id="experience"
                                   value="<?php echo isset($rsedit['experience']) ? $rsedit['experience'] : ''; ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Consultancy Charge</label> 
                        <div class="form-line">
                            <input class="form-control" type="text" name="consultancy_charge" id="consultancy_charge"
                                   value="<?php echo isset($rsedit['consultancy_charge']) ? $rsedit['consultancy_charge'] : ''; ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Status</label> 
                        <div class="form-line">
                            <select class="form-control show-tick" name="select" id="select">
                                <option value="" hidden>Select</option>
                                <?php
                                $arr = ["Active","Inactive"];
                                foreach($arr as $val) {
                                    $sel = (isset($rsedit['status']) && $rsedit['status']==$val) ? "selected" : "";
                                    echo "<option value='$val' $sel>$val</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <input class="btn btn-default" type="submit" name="submit" id="submit" value="Submit" />
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include("adfooter.php");
?>

<script type="application/javascript">
var alphaExp        = /^[a-zA-Z]+$/;
var alphaspaceExp   = /^[a-zA-Z\s]+$/;
var numericExpression = /^[0-9]+$/;
var alphanumericExp = /^[0-9a-zA-Z]+$/;



function validateform()
{
	if(document.frmdoct.doctorname.value == "")
	{
		alert("Doctor name should not be empty..");
		document.frmdoct.doctorname.focus();
		return false;
	}
	else if(!document.frmdoct.doctorname.value.match(alphaspaceExp))
	{
		alert("Doctor name not valid..");
		document.frmdoct.doctorname.focus();
		return false;
	}
	else if(document.frmdoct.mobilenumber.value == "")
	{
		alert("Mobile number should not be empty..");
		document.frmdoct.mobilenumber.focus();
		return false;
	}
	else if(!document.frmdoct.mobilenumber.value.match(numericExpression))
	{
		alert("Mobile number not valid..");
		document.frmdoct.mobilenumber.focus();
		return false;
	}
	else if(document.frmdoct.select3.value == "")
	{
		alert("Department ID should not be empty..");
		document.frmdoct.select3.focus();
		return false;
	}
	else if(document.frmdoct.loginid.value == "")
	{
		alert("loginid should not be empty..");
		document.frmdoct.loginid.focus();
		return false;
	}
	else if(!document.frmdoct.loginid.value.match(alphanumericExp))
	{
		alert("loginid not valid..");
		document.frmdoct.loginid.focus();
		return false;
	}
	else if(document.frmdoct.password.value == "")
	{
		alert("Password should not be empty..");
		document.frmdoct.password.focus();
		return false;
	}
	else if(document.frmdoct.password.value.length < 8)
	{
		alert("Password length should be more than 8 characters...");
		document.frmdoct.password.focus();
		return false;
	}
	else if(document.frmdoct.password.value != document.frmdoct.cnfirmpassword.value )
	{
		alert("Password and confirm password should be equal..");
		document.frmdoct.password.focus();
		return false;
	}
	else if(document.frmdoct.education.value == "")
	{
		alert("Education should not be empty..");
		document.frmdoct.education.focus();
		return false;
	}
	else if(!document.frmdoct.education.value.match(alphaExp))
	{
		alert("Education not valid..");
		document.frmdoct.education.focus();
		return false;
	}
	else if(document.frmdoct.experience.value == "")
	{
		alert("Experience should not be empty..");
		document.frmdoct.experience.focus();
		return false;
	}
	else if(!document.frmdoct.experience.value.match(numericExpression))
	{
		alert("Experience not valid..");
		document.frmdoct.experience.focus();
		return false;
	}
	else if(document.frmdoct.select.value == "" )
	{
		alert("Kindly select the status..");
		document.frmdoct.select.focus();
		return false;
	}
	else
	{
		return true;
	}
}
</script>