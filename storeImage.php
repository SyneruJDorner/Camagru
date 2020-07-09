<?php
    session_start();

    if (!isset($_SESSION['username']))
    {
        header('location: index.php');
        return;
    }

    if (isset($_POST['image']))
    {
        $image = $_POST['image'];
        $name = $_SESSION['username'];
        $image = file_get_contents($image);
        $image = base64_encode($image);
        echo $image."<br/>";
        save_image($name, $image);
    }
    
    display_images();

    function save_image($name, $image)
    {
		include 'config/database.php';
		
		$sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		
		$name = $_SESSION['username'];
		$stmt = $connection->prepare("UPDATE registered_users SET profile_pic=:image WHERE username=:name");
		$result = $stmt->execute(array(':image' => $image, ':name' => $name));

		if ($result)
            echo "Image uploaded.<br/>";
        else
            echo "Image not uploaded.<br/>";
		
		
		
		
		
		
		
		
		/*
        $dbname = 'users';
        $username = 'root';
        $password = "Password1234";

        $con = mysqli_connect("localhost", $username, $password);
        $selected = mysqli_select_db($con, $dbname);
		
        $qry="ALTER TABLE image_pins AUTO_INCREMENT=0;";
        $result=mysqli_query($con, $qry);

        echo $name;
        echo "<br/>";
        $qry = "UPDATE registered_users SET profile_pic='$image' WHERE username='$name'";
        $result=mysqli_query($con, $qry);
    
        if ($result)
            echo "Image uploaded.<br/>";
        else
            echo "Image not uploaded.<br/>";
		*/
    }

    function display_images()
    {
		include 'config/database.php';
		
		$sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		
		$name = $_SESSION['username'];
		$stmt = $connection->prepare("SELECT * FROM registered_users ORDER BY ID ASC");
		$stmt->execute();
		$row_count = $stmt->rowCount();
		
		if ($row_count > 0)
        {
			$row = $stmt->fetchAll();
			$databse_position = 0;
			
			echo '<div class="row">'; 
			while($databse_position < $row_count)
			{
				echo '<div class="polaroid">';
					echo '<img src="data:image;base64,'.$row[6].' " style="width:100%"> ';
					echo '<a href="">';
						echo '<img height="25" width="auto" src="https://techflourish.com/images/thumbs-up-outline-clipart-3.jpg"> ';
					echo '</a>';
					echo '<a href="">';
						echo '<img height="25" width="auto" src="https://techflourish.com/images/thumbs-up-outline-clipart-3.jpg" class="img-vert">';
					echo '</a>';
					echo '<a href="?delete='.$databse_position.'">';
						echo '<img height="25" width="auto" src="https://cdn4.iconfinder.com/data/icons/business-finance-vol-12-2/512/25-512.png" class="img-right" name="delete" href="home.php?page=1""> ';
					echo '</a>';
					
					echo '<div class="container">';
						echo '<p>Cinque Terre</p>';
					echo '</div>';
				echo '</div>';
				$databse_position++;
            }
			echo '</div>';
		}

		
		
		/*
        $dbname = 'users';
        $username = 'root';
        $password = "Password1234";

        $con = mysqli_connect("localhost", $username, $password);
        $selected = mysqli_select_db($con, $dbname);
        $qry = "SELECT * FROM registered_users";
        $result=mysqli_query($con, $qry);
        $databse_position = 0;

        echo '<div class="row">'; 
        while ($row = mysqli_fetch_array($result))
        {
            echo '<div class="polaroid">';
                echo '<img src="data:image;base64,'.$row[6].' " style="width:100%"> ';
                echo '<a href="">';
                    echo '<img height="25" width="auto" src="https://techflourish.com/images/thumbs-up-outline-clipart-3.jpg"> ';
                echo '</a>';
                echo '<a href="">';
                    echo '<img height="25" width="auto" src="https://techflourish.com/images/thumbs-up-outline-clipart-3.jpg" class="img-vert">';
                echo '</a>';
                echo '<a href="?delete='.$databse_position.'">';
                    echo '<img height="25" width="auto" src="https://cdn4.iconfinder.com/data/icons/business-finance-vol-12-2/512/25-512.png" class="img-right" name="delete" href="home.php?page=1""> ';
                echo '</a>';
                
                echo '<div class="container">';
                    echo '<p>Cinque Terre</p>';
                echo '</div>';
            echo '</div>';
            $databse_position++;
        }
        echo '</div>';
        mysqli_close($con);
		*/
    }
?>