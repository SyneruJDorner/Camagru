<?php
	session_start();
	$current_user = $_SESSION['username'];
	
	if (isset($_POST['image']) && $_POST['image'] != null)
    {
		$image = $_POST['image'];
        $name = "Web_Cam_Capture";
        $image = file_get_contents($image);
		$image = base64_encode($image);
        save_image($current_user, $name, $image);
    }
	else
	{
		$image = addslashes($_FILES['fileToUpload']['tmp_name']);
		$name = addslashes($_FILES['fileToUpload']['name']);
		$image = file_get_contents($image);
		$image = base64_encode($image);
		save_image($current_user, $name, $image);
	}

	function save_image($current_user, $name, $image)
	{
		include 'config/database.php';

		if ($name == null)
		{
			Header("location: Webcam.php");
			return;
		}

		$sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		
		$name = $_SESSION['username'];
		$stmt = $connection->prepare("SELECT * FROM registered_users ORDER BY ID ASC");
		$result = $stmt->execute();
		$row_count = $stmt->rowCount();
		
		if ($row_count > 0)
        {
			$row = $stmt->fetchAll();
			$count = 0;
			
			while($count < $row_count)
			{
                $stmt = $connection->prepare("UPDATE registered_users SET profile_pic=:image WHERE username=:current_user");
                $stmt->execute(array(':image' => $image, ':current_user' => $current_user));
                $count++;
            }
        }
		
		if ($result)
		{
			echo "<br/>Updated profile pic.";
			Header("location: Webcam.php");
		}
		else
		{
			echo "<br/>failed to update profile pic.";
		}
	}
?>