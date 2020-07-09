<?php
    //header("location: ../index.php");
	include 'database.php';
	
    if($stmt->rowCount() < 1)
        create_database($connection);
    confirm_info_exists($connection);

    function create_database($connection)
    {
        $sql = "CREATE DATABASE IF NOT EXISTS users";
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		
        $sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		
		// Create table for images and info when a person uploads one
		$sql = "CREATE TABLE image_pins (
            ID int(11) PRIMARY KEY,
            title varchar(255) NOT NULL,
            username varchar(255) NOT NULL,
            json_link varchar(255) NOT NULL,
            image longblob,
            image_overlay longblob)";
			
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		
		// Create table for users
        $sql = "CREATE TABLE registered_users (
            ID                  int(11) AUTO_INCREMENT PRIMARY KEY,
            username            varchar(255) NOT NULL,
            email               varchar(255) NOT NULL,
            password            varchar(255) NOT NULL,
            confirmation_code   int(11),
            registered_account  int(11),
            comment_email       int(11),
            profile_pic         longblob)";
			
		$stmt = $connection->prepare($sql);
		$stmt->execute();
    }

    function confirm_info_exists($connection)
    {
        $name = $_POST['register_user'];
        $email = $_POST['register_email'];
        $pass = $_POST['register_password'];
        $confirm_pass = $_POST['register_confirm_password'];
        
        if ($pass != $confirm_pass)
        {
            $_SESSION['message'] = "The passwords entered do not match.";
            echo "The passwords entered do not match.";
            //header("location: ../error.php");
            return;
        }

        if (strlen($pass) < 8)
        {
            $_SESSION['message'] = "The passwords entered is too short." . "         Count: " . strlen($pass);
            header("location: ../error.php");
            return;
        }

        if(preg_match('~[0-9]~', $pass) !== 1 || preg_match('~[A-Z]~', $pass) !== 1 || preg_match('~[a-z]~', $pass) !== 1)
        {
            $_SESSION['message'] = "The passwords entered needs to contain a letter, capital letter and a number!";
            header("location: ../error.php");
            return;
        }

        $sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute();

		$sql = "SELECT * FROM registered_users WHERE username='$name'";
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		
        if ($stmt->rowCount() >= 1)
        {
            $_SESSION['message'] = "The username you have selected is already taken!";
            header('location: ../error.php');
            return;
        }
        else
        {
            insert_info($connection);
			$name = $_POST['register_user'];
            header("location: sendmail.php?username=" . $name);
            return;

        }
    }

    function insert_info($connection)
    {
        $name     = $_POST['register_user'];
        $email    = $_POST['register_email'];
        $code     = generate_pin(4);

        $_POST['register_password'] = stripslashes($_POST['register_password']);
        $pass = hash("sha512", $_POST['register_password']);

        $sql = "INSERT INTO registered_users (username, email, password, confirmation_code, registered_account, comment_email)
            VALUES ('$name', '$email', '$pass', '$code', 0, 1)";

        if ($connection->query($sql) === FALSE)
            debug_to_console("Error: " . $sql . "<br>" . $connection->error);
        return;
    }

    function generate_pin($digits = 4)
    {
        $i = 0;
        $pin = "";
        while($i < $digits)
        {
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin;
    }

    function debug_to_console($data)
    {
        $output = $data;
        if (is_array( $output))
            $output = implode( ',', $output);
    
        echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
    }
?>