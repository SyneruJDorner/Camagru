<?php
	include 'config/database.php';

    if (!isset($_SESSION['username']))
    {
        header('location: index.php');
        return;
    }
        
    ini_set('default_socket_timeout', 300);

    $name       = $_SESSION["username"];
    $title = null;
    $uploader_name = null;
    $json_link = null;
    $like = null;

    if(isset($_GET["title"]))
        $title = $_GET["title"];

    if(isset($_GET["username"]))
        $uploader_name = $_GET["username"];

    if(isset($_GET["json_comment"]))
        $json_link = $_GET["json_comment"];

    if(isset($_GET["like"]))
        $like = $_GET["like"];

    if (isset($like) && $like != 0)
    {
        like_unlike($name, $like, $json_link);
        header("location: home.php?page=1");
        return;
    }
    
    function like_unlike($name, $like, $json_link)
    {
        $path = './persistent data';
        if (!file_exists($path))
            mkdir($path , 0777, true);
        
        echo $json_link;
        $string = file_get_contents("./persistent data/".$json_link.".json");
        $json_a = json_decode($string, true);
        $all_comments = array();
        $changed_like = false;

        foreach ($json_a as $key => $value)
        {
            if ($value == "Like: " . $name . "=" . $like)
                return;
            if ($like == 1)
            {
                if ($value == "Like: " . $name . "=-1")
                {
                    array_push($all_comments, "Like: " . $name . "=1");
                    $like = 1;
                    $changed_like = true;
                    continue;
                }
            }
            else if ($like == -1)
            {
                if ($value == "Like: " . $name . "=1")
                {
                    array_push($all_comments, "Like: " . $name . "=-1");
                    $like = -1;
                    $changed_like = true;
                    continue;
                }
            }

            array_push($all_comments, $value);
        }

        if ($changed_like == false)
            array_push($all_comments, "Like: " . $name . "=" . $like);
        $encoded_json = json_encode($all_comments);
        file_put_contents("./persistent data/".$json_link.".json", $encoded_json);
    }

    $dbname = 'users';
    $username = 'Test';
    $password = "Password1234";

    $path = './persistent data';
    if (!file_exists($path))
        mkdir($path , 0777, true);
	
	$sql = "use users";
	$stmt = $connection->prepare($sql);
	$stmt->execute(array());
	
	$stmt = $connection->prepare("SELECT * FROM image_pins WHERE json_link=:json_link");
    $result = $stmt->execute(array(':json_link' => $json_link));
	$row = $stmt->fetch();
    
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css\">";
    echo "<body style=\"background-image: url('Art/Login Background.jpg'); background-repeat: no-repeat; background-attachment: fixed; background-size: 100% 100%;\">";
        echo "<div align='right'>";
            echo "<span>";
                echo "<a class='float-right' href='home.php?page=0'>HOME</a>";
                echo "<br/>";
                echo "<a class='float-right' href='logout.php'>LOGOUT</a>";
            echo "</span>";
        echo "</div>";
        
        echo '<form action="" method="POST">';
            $db_image = $row[4];
            $db_image_overlay = $row[5];

            echo '<h1 style="color: white;">' . $title . ' by ' . $uploader_name . '</h1>';
            echo "<div style='position: relative;'>";
                echo '<img src="data:image;base64,'.$db_image_overlay.' " style="position: absolute;top: 50%;left: 50%;width: 500px;height: 500px;margin-top: -250px;margin-left: -250px;">' . "\n";
                echo '<img src="data:image;base64,'.$db_image.' " style="height:512px;display: block;margin-left: auto;margin-right: auto;width: 500px;"> ' . "\n";
            echo "</div>";

            echo '<h5 style="color: white;">Comments:</h5>';
            echo '<textarea rows="10" cols="30" name="commentContent" style="width:100%"></textarea><br/>';
            echo '<input type="submit" class="btn btn-primary" value="Post!"><br/>';
        echo '</form>';
    echo "</body>";
        
    if($_POST)
    {
        $path = './persistent data';
        if (!file_exists($path))
            mkdir($path , 0777, true);
		
		$all_comments = array();
		
		if (file_exists("./persistent data/" . $json_link . ".json"))
		{
			$string = file_get_contents("./persistent data/".$json_link.".json");
			$json_a = json_decode($string, true);

			foreach ($json_a as $key => $value)
			{
				array_push($all_comments, $value);
			}
		}
		
        $comment = $_POST['commentContent'];
        array_push($all_comments, $name . ": " . $comment);
        $encoded_json = json_encode($all_comments);
        file_put_contents("./persistent data/".$json_link.".json", $encoded_json);

        if ($uploader_name != $name)
        {
			$stmt = $connection->prepare("SELECT * FROM registered_users WHERE username=:uploader_name");
			$result = $stmt->execute(array(':uploader_name' => $uploader_name));
			$row = $stmt->fetch();
			
			if ($row['comment_email'] == 1)
			{
				$to = $row['email'];
                $subject = "Comment on image";
                $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                str_replace("like=1", "like=0", $actual_link);
                str_replace("like=-1", "like=0", $actual_link);
                $message = $name . " has commented on you image titled: " . $title;
                $message .= "\n";
                $message .= '<html><body>';
                $message .= '<a href="'.$actual_link.'">View Comment Now</a><BR>';
                $message .= '</body></html>';
                $headers  = 'From: justicev18@gmail.com' . "\r\n" .
                            'MIME-Version: 1.0' . "\r\n" .
                            'Content-type: text/html; charset=utf-8';

                mail($to, $subject, $message, $headers);
			}
        }
    }

    function update_live_comments($all_comments)
    {
        foreach ($all_comments as $value)
        {
            echo $value . "\n";
            echo "<br/>";
        }
    }
	
	if (file_exists("./persistent data/".$json_link.".json"))
	{
		$string = file_get_contents("./persistent data/".$json_link.".json");
		$json_a = json_decode($string, true);
		$all_comments = array();

		foreach ($json_a as $key => $value)
		{
			if (strpos($value, "Like: ") === FALSE)
				array_push($all_comments, $value);
		}
		
		if (count($all_comments) > 0)
			update_live_comments($all_comments);
	}
?>