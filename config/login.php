<?php
	include 'database.php';
	
	if($stmt->rowCount() < 1)
	{
		$_SESSION['message'] = "Login failed! Please register and account or enter the valid info to login!";
		header("location: ../error.php");
		return;
	}

	confirm_login_exists($connection);
	
	function confirm_login_exists($connection)
    {
        $username = $_POST['login_user'];
        $pass = $_POST['login_password'];

        $sql = "use users";
        $connection->exec($sql);
        
        $stmt = $connection->prepare("SELECT * FROM registered_users WHERE username = :username");
        $stmt->execute(array(':username' => $username));

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($result))
        {
            if ($result['registered_account'] == 0)
            {
                $_SESSION['message'] = 'Your account has not been verified.';
                header("location: ../error.php");
				return;
            }

            $_POST['login_password'] = stripslashes($_POST['login_password']);
            $pass = hash("sha512", $_POST['login_password']);

            if ($pass == $result['password'])
            {
				$_SESSION['username'] = $result['username'];
                $_SESSION['message'] = 'Password is valid!';
				header("location: ../home.php?page=1");
				return;
            }
            else
            {
                $_SESSION['message'] = 'Invalid password.';
                header("location: ../error.php");
				return;
            }
        }
        else
        {
            $_SESSION['message'] = "Login failed!";
            header("location: ../error.php");
            return;
        }
    }
?>