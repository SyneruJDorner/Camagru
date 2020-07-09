<html>
    <head>
        <title> User Login And Registration </title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    </head>
    <body style="background-image: url('Art/Login Background.jpg'); background-repeat: no-repeat; background-attachment: fixed; background-size: 100% 100%;">
        <div class="container">
            <div class="login-box">
                <div class="row">
                    <div class="col-md-6 place-left" style="background-color: rgba(165, 165, 165, 0.5);">
                        <h2>Login Here</h2>
                        <form action="config/login.php" method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="login_user" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="login_password" class="form-control" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                        <form action="forgot_password.php" method="post">
                            <button type="submit" class="btn btn-primary">Forgot Password</button>
                        </form>
                        <a href="home.php?not_registered=true">View Public Pages</a>
                    </div>




                    <div class="col-md-6 place-right" style="background-color: rgba(255, 255, 255, 1.0);">
                        <h2>Register Here</h2>
                        <form action="config/setup.php" method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="register_user" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="register_email" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="register_password" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="register_confirm_password" class="form-control" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
            session_start();
            if (isset($_SESSION["Sent_Email"]))
            {
                if ($_SESSION["Sent_Email"] == True)
                {
                    echo '<script language="javascript">';
                    echo 'alert("Email has been sent successfully, please check you email.")';
                    echo '</script>';
                    $_SESSION["Sent_Email"] = False;
                }
            }

            if (isset($_SESSION["Verified"]))
            {
                if ($_SESSION["Verified"] == True)
                {
                    echo '<script language="javascript">';
                    echo 'alert("Your account has now been registered and you are now able to use all the features!")';
                    echo '</script>';
                    $_SESSION["Verified"] = False;
                }
            }

            if (isset($_SESSION["Reset_Password"]))
            {
                if ($_SESSION["Reset_Password"] == True)
                {
                    echo '<script language="javascript">';
                    echo 'alert("Your password has been reset, please check your email for yur new password!")';
                    echo '</script>';
                    $_SESSION["Reset_Password"] = False;
                }
            }
        ?>
    </body>
</html>