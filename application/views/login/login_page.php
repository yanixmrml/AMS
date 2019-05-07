<?php $this->load->view("include/header.php"); ?>
<?php
    $image_url = base_url() . "/assets/css/images/login/";
    $footer_creator = "";
?>
<div id="login-wrapper" class="container">
    <div class="row">
        <div class="col-md-8" id="login-left-nav">
            <img src="<?php echo $image_url . "2.png"; ?>" id="login-image" class="img-fluid" />
            <div id="login-left-footer">
                <p><b>AMS Dashbaord</b> is a web-based application for Human Machine Interface of Attendance Monitoring System,
                a proposed research study by Kent Lloyd Manasan, Donnell Glean Tulod and Eryl Kean Cabilogan of College of Engineering, Mindanao State
                Univerity, Marawi City.
                </p>
            </div>
        </div>
        <div class="col-md-4" id="login-right-nav">
            <div id="login-panel">
                <?php
                    echo form_open("login/login_authentication",array("id"=>"login-frm"));
                ?>
                    <div id="warning-message">
                        <?php if(isset($_SESSION['message'])){ ?>
                            <div class="alert alert-danger">
                                <a href="#" class="close" data-dismiss="alert">
                                   &times;
                                </a>
                                <?php
                                    echo $_SESSION['message'];
                                    unset($_SESSION['message']);
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group" style='text-align: left;'><label for="username">USERNAME</label>&nbsp;<input type="text" name="username" id="username" class="form-control" placeholder="Username" /></div>
                    <div class="form-group" style='text-align: left;'><label for="password">PASSWORD</label>&nbsp;<input type="password" name="password" id="password" class="form-control" placeholder="Password" /></div>
                    <p>
                        <input type="hidden" name="auth" value="<?php echo $auth; ?>">
                        <label class="radio-inline"><input type="radio" checked="checked" name="attendanceType" value="employee" id="employeeType"><b>EMPLOYEE</b></label>
                        <label class="radio-inline"><input type="radio" name="attendanceType" value="student" id="studentType"><b>STUDENT</b></label>&nbsp;&nbsp;
                    </p>
                    <p>
                        <input type="hidden" name="auth" value="<?php echo $auth; ?>">
                        <input type="button" value="Login" id="login-button" class="btn btn-primary">&nbsp;
                        <input type="button" value="Clear" id="clear-button" class="btn btn-primary">
                    </p>
                <?php
                    echo form_close();
                ?>
                <hr/>
                <p><a href="#" id="forgot-password">Forgot password</a>&nbsp;|&nbsp;<a href="#" id="register-account">Create account</a></p>
                <p class="atspms-dashboard-footnote">*To register or create an account and retrieve the password, contact the developers.
                </p>
            </div>
        </div>
    </div>
</div> 
<?php $this->load->view("include/footer.php"); ?>