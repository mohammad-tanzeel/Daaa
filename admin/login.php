<?php
@ob_start();
//session_start();
session_name('suncros');
if (isset($_POST['formsubmit']) && $_POST['formsubmit'] == 1) {
    $userName = $_POST['username'];
    $password = $_POST['userpassword'];

    $status = _check_user_login($userName, $password);
}
?>

<?php
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    $_SESSION['is_admin'] = 1;
    header('Location: project_list.php');
} else {
    ?>	
    <div class="form-box" id="login-box">
        <div class="header">Sign In</div>       
        <form action="" method="POST" id="user-login">
            <div class="body bg-gray">
                <div class="form-group">
                    <?php if (isset($status) && $status == 0) { }
                        $msg = "<div class='alert alert-danger alert-dismissable'>
                                          <i class='fa fa-ban'></i>
                                          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"
                                ."User Name or Passwor is wrong</div>";
                        echo $msg;
                   
                    ?>

                </div>
                <div class="form-group">
                    <input type="text" class="form-control" required="" name="username" id="user-name" placeholder="User Name" />
                </div>
                <div class="form-group">
                    <input type="password" name="userpassword" required="" id="user-passwor" class="form-control" placeholder="Password" />
                </div>
                <input type="hidden" name="formsubmit" value="1" />
            </div>
            <div class="footer">
                <input type="submit" value="Submit" class="submitBtns btn bg-olive btn-block">
            </div>
        </form>
    </div>
<?php } ?>
