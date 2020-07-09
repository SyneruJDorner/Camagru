<?php
	session_start();

	if (!isset($_SESSION['username']))
	{
		header('location: index.php');
		return;
	}

	ini_set('default_socket_timeout', 300);

	if (isset($_GET['username']) && isset($_GET['username']))
	{
		if ($_GET['username'] == $_GET['username'])
		{
			UpdateUsername($_GET['username']);
			header('location: edit_account.php');
			return;
		}
		
		echo "Failed to change username";
	}
	
	if (isset($_GET['email']) && isset($_GET['confirm_email']))
	{
		if ($_GET['email'] == $_GET['confirm_email'])
		{
			UpdateEmail($_GET['email']);
			header('location: edit_account.php');
		}
		
		echo "Failed to change email";
	}

	if (isset($_GET['password']) && isset($_GET['confirm_password']))
	{
		if ($_GET['password'] == $_GET['confirm_password'])
			UpdatePassword($_GET['password']);
		
		header('location: edit_account.php');
	}

	if (isset($_GET['recieve_email']))
	{
		UpdateCommentEmail();
		header('location: edit_account.php');
	}

	function UpdateUsername($newUsername)
	{
		include 'config/database.php';
		
		$oldName = $_SESSION['username'];
		
		$sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute(array());
		
		$stmt = $connection->prepare("UPDATE registered_users SET username=:newUsername WHERE username=:oldName");
		$result = $stmt->execute(array(':newUsername' => $newUsername, ':oldName' => $oldName));
	
		if ($result)
		{
			$_SESSION["username"]          = $newUsername;
			echo "New Username.<br/>";
		}
		else
			echo "Failed update username.<br/>";
	}
	
	function UpdateEmail($email)
	{
		include 'config/database.php';
		
		$name = $_SESSION['username'];
		
		$sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute(array());
		
		$stmt = $connection->prepare("UPDATE registered_users SET email=:email WHERE username=:name");
		$result = $stmt->execute(array(':email' => $email, ':name' => $name));
	
		if ($result)
		{
			$_SESSION["email"]          = $email;
			echo "New Email.<br/>";
		}
		else
			echo "Failed update email.<br/>";
	}
	
	function UpdatePassword($newPassword)
	{
		include 'config/database.php';
		$name = $_SESSION['username'];
		$databasePassword = hash("sha512", $newPassword);
		
		$sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute(array());
		
		$stmt = $connection->prepare("UPDATE registered_users SET password=:databasePassword WHERE username=:name");
		$result = $stmt->execute(array(':databasePassword' => $databasePassword, ':name' => $name));
	
		if ($result)
			echo "New Password.<br/>";
		else
			echo "Failed update email.<br/>";
	}
	
	function UpdateCommentEmail()
	{
		include 'config/database.php';
		$name = $_SESSION['username'];
		
		$sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute(array());
		
		$stmt = $connection->prepare("SELECT * FROM registered_users WHERE username=:username");
		$stmt->execute(array(':username' => $name));
		$row = $stmt->fetch();
		$recieveEmail = ($row['comment_email'] == 0) ? 1 : 0;

		$stmt = $connection->prepare("UPDATE registered_users SET comment_email=:recieveEmail WHERE username=:name");
		$result = $stmt->execute(array(':recieveEmail' => $recieveEmail, ':name' => $name));
	
		if ($result)
		{
			$_SESSION["comment_email"]          = $recieveEmail;
			echo "New Recieve email state.<br/>";
		}
		else
			echo "Failed update recieve email state.<br/>";
	}
?>

<!DOCTYPE html>
<html>
    <style>
    #container
    {
        position: relative;
        width: 100%;
        height: 250px;
    }

    #main_image
    {
        width: 100%;
        height: 250px;
    }
    
    #overlay_image
    {
        position: absolute;
        border-radius: 100px;
        top: 225px;
        right: 10px;
        width: 150px;
        height: 150px;
    }
	
	

	
	
	
	body
	{
		font-family: Arial, Helvetica, sans-serif;
	}
	
	*
	{
		box-sizing: border-box;
	}

	.open-button
	{
	  background-color: #555;
	  color: white;
	  padding: 16px 20px;
	  border: none;
	  cursor: pointer;
	  opacity: 0.8;
	  position: fixed;
	  bottom: 23px;
	  right: 28px;
	  width: 280px;
	}

	.form-popup
	{
	  display: none;
	  position: fixed;
	  bottom: 0;
	  right: 15px;
	  z-index: 9;
	}

	.form-container
	{
	  max-width: 300px;
	  padding: 10px;
	  background-color: transparent;
	}

	.form-container input[type=text], .form-container input[type=password]
	{
	  width: 100%;
	  padding: 15px;
	  margin: 5px 0 22px 0;
	  border: none;
	  background: #f1f1f1;
	}

	.form-container input[type=text]:focus, .form-container input[type=password]:focus
	{
	  background-color: #ddd;
	  outline: none;
	}

	.form-container .btn
	{
	  background-color: #007bff;
	  color: white;
	  padding: 16px 20px;
	  border: none;
	  cursor: pointer;
	  width: 100%;
	  margin-bottom:10px;
	  opacity: 0.8;
	}

	.form-container .cancel
	{
	  background-color: red;
	}

	.form-container .btn:hover, .open-button:hover
	{
	  opacity: 1;
	}
    </style>
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <head>
        <title>
            Profile Page
        </title>
    </head>

    <body style="background-image: url('Art/Login Background.jpg'); background-repeat: no-repeat; background-attachment: fixed; background-size: 100% 100%;">
		<div align="right">
			<span>
				<a class='float-right' href='home.php?page=0'>HOME</a>
            	<br/>
				<a class="float-right" href="logout.php">LOGOUT</a>
			</span>
		</div>
        <div id="container" style="position: static;">
            <img id="main_image" src="Art/Banner.jpg"/>
            <a href="webcam.php">
                <img id="overlay_image" src="
					<?php
						include 'config/database.php';
						
						$sql = "use users";
						$connection->exec($sql);

						$name = $_SESSION['username'];
						$stmt = $connection->prepare("SELECT * FROM registered_users WHERE username='$name'");
						$stmt->execute(array(':username' => $name));
						$profile_image;
						
						while($row = $stmt->fetch())
							$profile_image = $row['profile_pic'];
						
						if($profile_image != null)
						{
							echo 'data:image;base64,'.$profile_image;
						}
						else
							echo "https://www.edgehill.ac.uk/health/files/2017/12/blank-profile.png";
					?>
					"/>
            <a>
        </div>
		
		<div style="position: absolute; top: 300px;">
			<div align="left">
				<font style="font-size: 26px; color: white;">Username:        </font>
				<font style="font-size: 20px; color: white;"> <?php echo $_SESSION['username']; ?> </font>

				<img style="width:20px;height:20px;" onclick="Edit_Name_Open()" src="https://cdn1.iconfinder.com/data/icons/hawcons/32/698873-icon-136-document-edit-512.png"/>
			</div>

			<div align="left">
				<font style="font-size: 20px; color: white;">Email:		</font>
				<font style="font-size: 15px; color: white;"> <?php echo $_SESSION['email']; ?> </font>
				
				<img style="width:20px;height:20px;" onclick="Edit_Email_Open()" src="https://cdn1.iconfinder.com/data/icons/hawcons/32/698873-icon-136-document-edit-512.png"/>
			</div>

			<div align="left">
				<font style="font-size: 20px; color: white;">Activation Status:		</font>
				<font style="font-size: 15px; color: white;"> <?php echo ($_SESSION['registered'] == 1) ? "Activated" : "Not Activated"; ?> </font>
			</div>
			
			<div align="left">
				<font style="font-size: 20px; color: white;">Change Password:		</font>
				<button type="button" class="btn btn-primary" onclick="Edit_Password_Open()">Change Password</button>
			</div>
			
			<div align="left">
				<font style="font-size: 20px;  color: white;">Receive Email on Comments:		</font>
				<input type="checkbox" name="Recieve_Email" value="Recieve Email On Image Comments" 

				onclick=<?php
					echo '"document.location.href=';
					echo "'";
					$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')  . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					$actual_link .= '?recieve_email=1';
					echo $actual_link;
					echo "'";
					echo '"';
				?>

				<?php 
					$emailcommet = $_SESSION["comment_email"];
					if ($emailcommet == 1)
						echo "Checked";
					else
						echo "";
				?>
				/>
			</div>
		</div>
		
		
		
		
		
		
		<div class="form-popup" id="popupEditName" style="background-color: rgba(165, 165, 165, 0.5);">
		  <form action="" class="form-container">
			<h1>Change Username</h1>

			<label for="text"><b>Username</b></label>
			<input type="text" placeholder="New Username" name="username" required>

			<label for="text"><b>Confirm Username</b></label>
			<input type="text" placeholder="Confirm Username" name="confirm_username" required>

			<button type="submit" class="btn">Change Username</button>
			<button type="button" class="btn cancel" onclick="Edit_Name_Close()">Cancel</button>
		  </form>
		</div>
		
		<div class="form-popup" id="popupEditEmail" style="background-color: rgba(165, 165, 165, 0.5);">
		  <form action="" class="form-container">
			<h1>Change Email</h1>

			<label for="email"><b>New Email</b></label>
			<input type="text" placeholder="Enter Email" name="email" required>

			<label for="confirm_email"><b>Confirm Email</b></label>
			<input type="text" placeholder="Enter Email" name="confirm_email" required>
			
			<button type="submit" class="btn">Change Email</button>
			<button type="button" class="btn cancel" onclick="Edit_Name_Close()">Cancel</button>
		  </form>
		</div>
		
		<div class="form-popup" id="popupEditPassword" style="background-color: rgba(165, 165, 165, 0.5);">
		  <form action="" class="form-container">
			<h1>Change Password</h1>

			<label for="Password"><b>Password</b></label>
			<input type="Password" placeholder="New Password" name="password" required>

			<label for="Password"><b>Confirm Password</b></label>
			<input type="Password" placeholder="Confirm Password" name="confirm_password" required>

			<button type="submit" class="btn">Change Password</button>
			<button type="button" class="btn cancel" onclick="Edit_Name_Close()">Cancel</button>
		  </form>
		</div>
		
		<script>
		function Edit_Name_Open()
		{
			Edit_Email_Close();
			Edit_Password_Close();
			document.getElementById("popupEditName").style.display = "block";
		}

		function Edit_Name_Close()
		{
			document.getElementById("popupEditName").style.display = "none";
		}
		
		function Edit_Email_Open()
		{
			Edit_Name_Close();
			Edit_Password_Close();
			document.getElementById("popupEditEmail").style.display = "block";
		}

		function Edit_Email_Close()
		{
			document.getElementById("popupEditEmail").style.display = "none";
		}
		
		function Edit_Password_Open()
		{
			Edit_Name_Close();
			Edit_Email_Close();
			document.getElementById("popupEditPassword").style.display = "block";
		}

		function Edit_Password_Close()
		{
			document.getElementById("popupEditPassword").style.display = "none";
		}
		</script>
    </body>
</html>