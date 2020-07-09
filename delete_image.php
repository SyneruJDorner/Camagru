<?php
	include 'config/database.php';
	
	if (!isset($_SESSION['username']))
    {
        header('location: index.php');
        return;
    }
	
	$databse_position = $_GET['delete'];
    $page = $_GET['page'];
	
	$sql = "use users";
	$stmt = $connection->prepare($sql);
	$stmt->execute(array());
	$ID = 0;
	
	$databse_position = ($databse_position) + (($page - 1) * 5);
	
	$stmt = $connection->prepare("SELECT * FROM image_pins WHERE ID='" . $databse_position . "'");
    $result = $stmt->execute(array());
	
	if ($result)
	{
		$row = $stmt->fetch();
		$json_link = $row[3];

		if (file_exists("./persistent data/" . $json_link . ".json"))
			unlink("./persistent data/" . $json_link . ".json");
	}
	
    $stmt = $connection->prepare("DELETE FROM image_pins WHERE ID='" . $databse_position . "'");
    $result = $stmt->execute(array());
	
	if ($result)
	{
		$stmt = $connection->prepare("SELECT * FROM image_pins ORDER BY ID ASC");
		$stmt->execute();
		$row_count = $stmt->rowCount();
		
        $ID = $databse_position;

        if ($row_count > 0)
        {
			$row = $stmt->fetchAll();
			
			while($ID < $row_count)
			{
				$json_link = $row[$ID][3];
                $stmt = $connection->prepare("UPDATE image_pins SET ID='$ID' WHERE json_link='$json_link'");
                $stmt->execute(array());
                $ID++;
            }
        }
		
		Header("Location: home.php?page=$page");
	}
	else
	{
		$_SESSION['message'] = "Error deleting the image [severe error].";
		header("location: ../error.php");
	}
?>