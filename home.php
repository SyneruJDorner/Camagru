<?php
	if (session_status() == PHP_SESSION_NONE)
		session_start();
	
	if (!isset($_GET['not_registered']))
	{
		if (!isset($_SESSION['username']))
		{
			header('location: index.php');
			return;
		}
	}

	if (isset($_GET['not_registered']))
		session_destroy();

	ini_set('default_socket_timeout', 300);
	
	Set_Session_Data();
	Ensure_Pagination();
	
	function Set_Session_Data()
	{
		include 'config/database.php';
		
		$sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		
		if (isset($_SESSION['username']))
		{
			$name = $_SESSION['username'];

			$stmt = $connection->prepare("SELECT * FROM registered_users WHERE username=:username");
			$stmt->execute(array(':username' => $name));

			while($row = $stmt->fetch())
			{
				$_SESSION["ID"]             = $row['ID'];
				$_SESSION["username"]       = $row['username'];
				$_SESSION["email"]          = $row['email'];
				$_SESSION["code"]           = $row['confirmation_code'];
				$_SESSION["registered"]     = $row['registered_account'];
				$_SESSION["comment_email"]     = $row['comment_email'];
			}
		}
	}

	function Ensure_Pagination()
	{
		include 'config/database.php';
		
		$sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		
		$results_per_page = 5;

		$sql = "SELECT * FROM image_pins";
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		$row_count = $stmt->rowCount();
		
		$number_of_pages = ceil($row_count / $results_per_page);
		
		$page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
		
		if ($page > $number_of_pages)
			Header("Location: home.php?page=" . ($page - 1));
	}
?>

<!DOCTYPE html>
<html>
<style>
body
{
	margin: 25px;
}

div.polaroid
{
	margin: 0px auto;
	width: 30%;
	height: 30%;
	background-color: white;
	box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	margin-bottom: 25px;
}

div.container
{
	text-align: center;
	padding: 10px 20px;
}

.center-screen
{
  flex-direction: row;
  text-align: center;
}

.img-right
{
	float: right;
}

.float-right
{
	position: relative;
	top: -65px;
	float: right;
	vertical-align: middle;
}

*
{
    box-sizing: border-box;
}

body
{
    margin: 0;
    font-family: Arial;
}

.row
{
	margin: 100px auto;
	margin-right: 200px;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    padding: 0 4px;
}

.image_options_row
{
	margin: auto;
	margin-right: 200px;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    padding: 0 4px;
}

/* Create four equal columns that sits next to each other */
.column
{
    -ms-flex: 25%;
    flex: 25%;
    max-width: 25%;
    padding: 0 4px;
}

.column img
{
    margin-top: 8px;
    vertical-align: middle;
}

/* Responsive layout - makes a two column-layout instead of four columns */
@media screen and (max-width: 800px)
{
    .column
    {
        -ms-flex: 50%;
        flex: 50%;
        max-width: 50%;
    }
}

/* Responsive layout - makes the two columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px)
{
    .column
    {
        -ms-flex: 100%;
        flex: 100%;
        max-width: 100%;
    }
}

#header_bar
{
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 75px;
	background-color: grey;
	z-index: 3;
}


#footer_bar
{
	position: fixed;
	bottom: 0;
	left: 0;
	width: 100%;
	height: 75px;
	background-color: darkgrey;
	z-index: 1;
}

#side_bar
{
	position: fixed;
	top: 0;
	right: 0;
	width: 200px;
	height: 100%;
	background-color: grey;
	z-index: 2;
}

.center
{
    margin: 75px auto;
    width: 100%;
    padding: 12%;
}

#overlay_image
{
	position: absolute;
	border-radius: 100px;
	left: 15px;
	top: 10px;
	width: 90px;
	height: 90px;
}

#ImageOverlayPreview
{
	position: absolute;
}

#image_overlay_container
{
    position: relative;
}

#overlay_image_uploaded
{
    position: absolute;
}











span.psw {
  float: right;
  padding-top: 16px;
}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  padding-top: 60px;
}

/* Modal Content/Box */
.modal-content-upload {
  background-color: #fefefe;
  margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
  border: 1px solid #888;
  width: 25%; /* Could be more or less, depending on screen size */
}

/* The Close Button (x) */
.close {
  position: absolute;
  right: 25px;
  top: 0;
  color: #000;
  font-size: 35px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: red;
  cursor: pointer;
}

/* Add Zoom Animation */
.animate
{
  -webkit-animation: animatezoom 0.6s;
  animation: animatezoom 0.6s
}

@-webkit-keyframes animatezoom
{
  from {-webkit-transform: scale(0)} 
  to {-webkit-transform: scale(1)}
}
  
@keyframes animatezoom
{
  from {transform: scale(0)} 
  to {transform: scale(1)}
}
</style>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Home Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="style.css" />
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body style="background-image: url('Art/Login Background.jpg');">
	<div id="header_bar" style="background-color: rgba(165, 165, 165, 0.5);">
		<img id="overlay_image" src="
		<?php
			include 'config/database.php';
			
			$sql = "use users";
			$stmt = $connection->prepare($sql);
			$stmt->execute();
			
			if (isset($_SESSION['username']))
			{
				$name = $_SESSION['username'];
				$stmt = $connection->prepare("SELECT * FROM registered_users WHERE username=:username");
				$stmt->execute(array(':username' => $name));
				$profile_image = null;
				
				while($row = $stmt->fetch())
					$profile_image = $row['profile_pic'];
				
				if($profile_image != null)
					echo 'data:image;base64,'.$profile_image;
				else
					echo "https://www.edgehill.ac.uk/health/files/2017/12/blank-profile.png";
			}
			else
			{
				echo "Art/Transparent.png";
			}
		?>
		"/>
		<div style="padding-left: 110px;padding-top: 10px;"><h1 style="color: white;">Welcome
			<?php 
				if(isset($_SESSION['username']))
					echo $_SESSION['username'];
				else
					echo "Stranger";
			?>
			</h1>
		</div>
		<div align="right" style="margin-top: 22px; margin-bottom: 22px;">
			<span>
				<?php 
				if (isset($_SESSION['username']))
				{
					echo '<a class="float-right" href="edit_account.php">EDIT ACCOUNT</a>';
					echo '<br/>';
				}
				?>
				<a class="float-right" href="logout.php">LOGOUT</a>
			</span>
		</div>
	</div>
	






	<div id="id01" class="modal">
  		<form class="modal-content animate" method="post" enctype="multipart/form-data" style="margin-left: 50%;position: absolute;top: 50%;left: 50%;width: 500px;height: 500px;margin-top: -250px;margin-left: -250px;">
			<div>
				<img id="ImageOverlayPreview" style="margin-left: 50%;position: absolute;top: 50%;left: 50%;width: 300px;height: 300px;margin-top: -150px;margin-left: -150px;"/>
				<img id="uploadPreview" style="margin-left: 50%;position: absolute;top: 50%;left: 50%;width: 300px;height: 300px;margin-top: -150px;margin-left: -150px;"/>
			</div>

			<input id="uploadImage" type="file" name="image" onchange="PreviewImage();" style="display: none;"/>
			<input id="ImageOverlay" type="file" name="overlayImage" onchange="PreviewImage('Overlay');" style="display: none;"/>
			
			<div style="position: absolute; top: 85%; left: 9%; width: 500px;">
				<input class="btn btn-primary" type="button" value="Upload Image..." style="float:left;margin-left:25px;width:100px;font-size: 12px;" onclick="document.getElementById('uploadImage').click();"/>
				<input class="btn btn-primary" type="button" value="Add Overlay" style="float:left;margin-left:25px;width:100px;font-size: 12px;" onclick="document.getElementById('ImageOverlay').click();"/>
				<input class="btn btn-primary" type="submit" name="submit" style="float:left;margin-left:25px;width:100px;font-size: 12px;" value="Upload"/>
			</div>
		</form>
	</div>



	<script type="text/javascript">
	    function PreviewImage($args = null)
	    {
			if ($args == null)
			{
				var oFReader = new FileReader();
				oFReader.readAsDataURL(document.getElementById("uploadImage").files[0]);

				oFReader.onload = function (oFREvent)
				{
					document.getElementById("uploadPreview").src = oFREvent.target.result;
				};
			}
			else
			{
				var oFReader = new FileReader();
				oFReader.readAsDataURL(document.getElementById("ImageOverlay").files[0]);

				oFReader.onload = function (oFREvent)
				{
					document.getElementById("ImageOverlayPreview").src = oFREvent.target.result;
				};
			}
	    };


		//DISPLAY MODAL
		var modal = document.getElementById('id01');

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
	</script>

	<?php
    if (session_status() == PHP_SESSION_NONE)
		session_start();
	
	if (isset($_POST['submit']))
	{
		if ($_FILES['image']['size'] == 0)
		{
			echo "<br/>Please select an image.<br/>";
		}
		else
		{
			$image = addslashes($_FILES['image']['tmp_name']);
			$image_overlay = addslashes($_FILES['overlayImage']['tmp_name']);
			$name = addslashes($_FILES['image']['name']);
			$overlay_name = addslashes($_FILES['overlayImage']['name']);

			$image = file_get_contents($image);
			$image = base64_encode($image);

			$image_overlay = file_get_contents($image_overlay);
			$image_overlay = base64_encode($image_overlay);

			save_image($name, $image, $image_overlay);
		}
	}
	
	setup_pagination();

	function setup_pagination()
	{
		include 'config/database.php';

		$sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute();

		$results_per_page = 5;

		$sql = "SELECT * FROM image_pins";
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		$row_count = $stmt->rowCount();
		
		$number_of_pages = ceil($row_count / $results_per_page);
		$page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
		
		echo '<div id="footer_bar" style="background-color: rgba(165, 165, 165, 0.5);">';
			echo '<div align="left"><h5 style="margin-top: 50px; color: white;">&#169; copyright Justin Dorner</h5></div>';
			echo '<div style="float: right;position: relative;right: 20px;bottom: 160%;">';
			echo	 '<input type="image" id="addbtn_upload_image" src="Art/Add_Button.png" style="width: 75px;height: 75px;border: none;outline: none;" onclick="document.getElementById(\'id01\').style.display=\'block\'"/>';
			echo '</div>';
			if ($number_of_pages != 0)
			{
				$page = 1;
				
				if (!isset($_GET['page']))
					$page = 1;
				else
					$page = $_GET['page'];
			
				echo '<div class="center-screen" style="margin-top: -3.0em;">';
					if ($page > 1)
						echo '<a href="home.php?page=' . ($page - 1) . '" style="color: white;">' . "< " . '</a>';
					for ($page_counter = 1; $page_counter <= $number_of_pages; $page_counter++)
						echo '<a style="color: white;" href="home.php?page=' . $page_counter . '">' . (string)$page_counter . "	" . '</a>';
					if ($page < $number_of_pages - 1)
						echo '<a href="home.php?page=' . ($page + 1) . '" style="color: white;">' . " >" . '</a>';
				echo '</div>';
			}
		echo '</div>';
	}
	
	display_images();
	
	function unique_code_generator($prefix='',$post_fix='')
	{
		$t = time();
		return ($prefix . $t . "_" . rand(000,111) . $post_fix . "_" . rand(000,111));
	}

	function save_image($name, $image, $image_overlay = null)
	{
		include 'config/database.php';

		$sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute(array());
		$ID = 0;

		$json_link = unique_code_generator('link_', '_key');
		$title = (!isset($title)) ? $name : $title;
		$logged_in_user = $_SESSION['username'];
		//$connection->query("SELECT * FROM image_pins");
		
		$stmt = $connection->prepare("SELECT * FROM image_pins");
		$stmt->execute(array());
		$ID = $stmt->rowCount();
		
		$sql = "INSERT INTO image_pins (ID, title, username, json_link, image, image_overlay) VALUES (:ID, :title, :logged_in_user, :json_link, :image, :image_overlay)";
		$stmt = $connection->prepare($sql);
		$result = $stmt->execute(array(':ID' => $ID, ':title' => $title, ':logged_in_user' => $logged_in_user, ':json_link' => $json_link, ':image' => $image, ':image_overlay' => $image_overlay));
		
		if ($result)
			echo "<br/>Image uploaded.";
		else
			echo "<br/>Image not uploaded.";
	}
	
	function debug_to_console($data)
	{
		$output = $data;
		if ( is_array( $output ) )
			$output = implode( ',', $output);
	
		echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
	}

	function display_images()
	{
		include 'config/database.php';

		$sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute(array());

		$sql = "SELECT * FROM image_pins";
		$stmt = $connection->prepare($sql);
		$stmt->execute(array());
		
		$databse_position = 0;
		$results_per_page = 5;
		$number_of_results = $stmt->rowCount();
		
		$number_of_pages = ceil($number_of_results / $results_per_page);
		$page = (!isset($_GET['page'])) ? 1 : ($_GET['page'] < 1) ? 1 : $_GET['page'];
		$this_page_first_result = ($page - 1) * $results_per_page;
		
		$sql = "SELECT * FROM image_pins ORDER BY ID ASC LIMIT " . $this_page_first_result . ',' . $results_per_page;
		$stmt = $connection->prepare($sql);
		$result = $stmt->execute(array());
		
		echo '<div class="row">' . "\n";
		while($row = $stmt->fetch())
		{
			$db_image = $row[4];
			$db_image_overlay = $row[5];
			echo '<div class="polaroid">' . "\n";
				echo '<a href="image_options.php?title='.$row[1].'&username='.$row[2].'&json_comment='.$row[3].'&like=0">' . "\n";
					echo "<div id='image_overlay_container'>";

						if ($db_image_overlay == null)
							echo '<img id="overlay_image_uploaded" src="Art/Transparent.png" style="width:100%; height:100%">' . "\n";
						else
							echo '<img id="overlay_image_uploaded" src="data:image;base64,'.$db_image_overlay.' " style="width:100%; height:100%">' . "\n";

						echo '<img src="data:image;base64,'.$db_image.' " style="width:100%">' . "\n";
					echo "</div>";
				echo '</a>';
				
				echo '<div style="width: 100%; display: table;">';
					echo '<div class="image_options_row">';
						if (isset($_SESSION['username']))
						{
							if ($row[2] == $_SESSION['username'])
							{
								echo '<a href="image_options.php?title='.$row[1].'&username='.$row[2].'&json_comment='.$row[3].'&like=1">' . "\n";
									$likeColour = (determine_likes($_SESSION['username'], $row[3]) == 1) ? 'rgba(0,255,0, 1)' : 'rgba(180,180,180, 1)';
									echo '<div style="background-color: ' . $likeColour . '; width: 25px; height: 25px; mask: url(#mymask);
									-webkit-mask-box-image: url(Art/Like.png);"></div>';
								echo '</a>' . "\n";
								echo '<a href="image_options.php?title='.$row[1].'&username='.$row[2].'&json_comment='.$row[3].'&like=-1">' . "\n";
									$unlikeColour = (determine_likes($_SESSION['username'], $row[3]) == -1) ? 'rgba(255,0,0, 1)' : 'rgba(180,180,180, 1)';
									echo '<div style="background-color: ' . $unlikeColour . '; width: 25px; height: 25px; mask: url(#mymask);
									-webkit-mask-box-image: url(Art/Dislike.png);" class="img-vert"></div>';
								echo '</a>' . "\n";
								echo '<a href="delete_image.php?delete='.$databse_position.'&page='.$page.'">' . "\n";
									echo '<img height="25" width="auto" src="Art/Delete.png" class="img-right" name="delete" href="home.php?page=1""> ' . "\n";
								echo '</a>' . "\n";
							}
						}
					echo '</div>';
				echo '</div>';
					
				
				echo '<div class="container">' . "\n";
					echo '<p>' . $row[1] . ' by ' . $row[2] . '</p>' . "\n";
				echo '</div>' . "\n";
				echo '<div>' . "\n";
					echo "Likes:" . determine_total_likes($row[3], 1) . "\n";
					echo "<br/>";
					echo "Disikes:" . determine_total_likes($row[3], -1) . "\n";
				echo '</div>' . "\n";
			echo '</div>' . "\n";

			$databse_position++;
		}
		echo '</div>';
		
		if(isset($_POST['review']))
		{
			comment($row[3]);
		}
	}

	function determine_likes($username, $json_link)
	{
		$path = './persistent data';
		if (!file_exists($path))
			mkdir($path , 0777, true);
		
		if(file_exists("./persistent data/".$json_link.".json"))
		{		
			$string = file_get_contents("./persistent data/".$json_link.".json");
			$json_a = json_decode($string, true);
			$all_comments = array();
			
			foreach ($json_a as $key => $value)
			{
				if ($value == "Like: " . $username . "=1")
					return (1);
				if ($value == "Like: " . $username . "=-1")
					return (-1);
			}
		}
		return (0);
	}

	function determine_total_likes($json_link, $likes_dislikes)
	{
		$path = './persistent data';
		if (!file_exists($path))
			mkdir($path , 0777, true);
		
		if(file_exists("./persistent data/".$json_link.".json"))
		{
			$string = file_get_contents("./persistent data/".$json_link.".json");
			$json_a = json_decode($string, true);
			$all_comments = array();
			$likes = 0;
			$dislikes = 0;

			foreach ($json_a as $key => $value)
			{
				debug_to_console($value);
				if ($likes_dislikes == 1)
				{
					if (strpos($value, '=1') == true)
						$likes++;
				}
				else if ($likes_dislikes == -1)
				{
					if (strpos($value, '=-1') == true)
						$dislikes++;
				}
			}

			if ($likes_dislikes == 1)
				return ($likes);
			else if ($likes_dislikes == -1)
				return ($dislikes);
			else
				return (0);
		}
		return (0);
	}
	?>
</body>
</html>