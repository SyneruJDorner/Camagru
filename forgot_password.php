<html>
    <head>
        <title> User Login And Registration </title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    </head>
    <body>
        <div class="container">
            <div class="login-box">
                <div class="row">
                    <div class="col-md-6 place-left">
                        <h2>Forgot Password</h2>
                        <form action="" method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="login_user" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="login_email" class="form-control" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Request New Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <?php
        session_start();
        
        if($_POST)
        {
			include 'config/database.php';
			
            $name = $_POST['login_user'];
            $email = $_POST['login_email'];

            $newPassword = randomPassword();
            $databasePassword = hash("sha512", $newPassword);

			$sql = "use users";
			$stmt = $connection->prepare($sql);
			$stmt->execute();
			
			$stmt = $connection->prepare("UPDATE registered_users SET password=:databasePassword WHERE username=:name");
			$result = $stmt->execute(array(':databasePassword' => $databasePassword, ':name' => $name));

			if ($result)
                echo "Reset password.<br/>";
            else
                echo "Failed to reset password.<br/>";
			
            $to = $email;
            $subject = "Reset password";
            $message = "Dear " . $name . "\n";
            $message .= "Your password has been reset, please use the following password:\n";
            $message .= $newPassword . "\n";
            $message .= "\n";
            $message .= "You can edit the password under 'edit' once you log in" . "\n";
            $headers  = 'From: justicev18@gmail.com' . "\r\n" .
                        'MIME-Version: 1.0' . "\r\n" .
                        'Content-type: text/html; charset=utf-8';
            mail($to, $subject, $message, $headers);
            $_SESSION["Reset_Password"] = true;
            header("Location: index.php");
        }

        function randomPassword()
        {
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $pass = array();
            $alphaLength = strlen($alphabet) - 1;
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            return implode($pass);
        }
    ?>
</html>