<?php
	session_start();

	if (!isset($_SESSION['username']))
	{
		header('location: index.php');
		return;
	}
		
	ini_set('mysql.connect_timeout', 300);
	ini_set('default_socket_timeout', 300);
?>

<!DOCTYPE html>
<html>
<?php
	if (session_status() == PHP_SESSION_NONE)
		session_start();
	
    if (isset($_POST['image']))
    {
		//Webcam
		if (trim($_POST['image']) != "")
		{
			$image = $_POST['image'];
			$name = $_SESSION['username'];
			echo $image;
			$image = file_get_contents($image);
			$image = base64_encode($image);
			echo $image."<br/>";
			save_image($name, $image);
		}
		else
		{
			debug_to_console("Please select an image");
		}
    }

	function debug_to_console($data)
	{
		$output = $data;
		if ( is_array( $output ) )
			$output = implode( ',', $output);
	
		echo "<script>alert('" . $output . "');</script>";
	}
	
    function save_image($name, $image)
    {
        $dbname = 'users';
        $username = 'Test';
        $password = "Password1234";
        
        $con = mysqli_connect("localhost", $username, $password);
        $selected = mysqli_select_db($con, $dbname);
        
        $qry="ALTER TABLE image_pins AUTO_INCREMENT=0;";
        $result=mysqli_query($con, $qry);
        
        echo $name;
        echo "<br/>";
        $qry="UPDATE registered_users SET profile_pic='$image' WHERE username='$name'";
        $result=mysqli_query($con, $qry);
        
        if ($result)
            echo "Image uploaded.<br/>";
        else
            echo "Image not uploaded.<br/>";
    }
    ?>



<head>
    <title>Capture webcam image with php and jquery - ItSolutionStuff.com</title>
    
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    
	<link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
	<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>

	
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <style type="text/css">
        #results
		{
			padding:20px;
			border:1px solid;
			background:#ccc;
		}

		.center
		{
			display: block;
			margin-left: auto;
			margin-right: auto;
			width: 50%;
		}
    </style>
</head>
<body style="background-image: url('Art/Login Background.jpg'); background-repeat: no-repeat; background-attachment: fixed; background-size: 100% 100%;">
  
<div align="right">
			<span>
				<a class='float-right' href='home.php?page=0'>HOME</a>
            	<br/>
				<a class="float-right" href="logout.php">LOGOUT</a>
			</span>
</div>

<div class="container">
    <h1 class="text-center" style="color: white">Change Profile Image
		<img id="uploadPreview" style="width: 75px; height: 75px; margin: auto; border-radius: 100px;" src="
			<?php
				$dbname = 'users';
				$username = 'Test';
				$password = "Password1234";
				$current_user = $_SESSION['username'];
				
				$con = mysqli_connect("localhost", $username, $password);
				$selected = mysqli_select_db($con, $dbname);
				$qry = "SELECT * FROM registered_users WHERE username='$current_user'";
				$result = mysqli_query($con, $qry);

				while ($row = mysqli_fetch_array($result))
				{
					echo 'data:image;base64,'.$row[7].' " style="width:100%"> ';
					break;
				}
			?>
	</h1>

	<form name="form1" action="change_profile_pic.php" method="post" enctype="multipart/form-data">
		<div id="my_camera" class="center"></div>
		<div class="col-md-6" id="results" hidden>Your captured/uploaded image will appear here...</div>
		
		<div style="position: absolute; top: 500px; left: 50%; margin-left: -150px;">
			<input type=button value="Take Snapshot" onClick="take_snapshot()" class="btn btn-primary">
			<input type="hidden" name="image" class="image-tag">
			<input type="submit" value="Upload Image" id="upload_webcam_image" name="submit" hidden>
						
			<input type=button value="Upload Image" style="cursor: pointer;" onClick="upload_image_click()" class="btn btn-primary">
			<input type="file" id="upload_image_btn" name="fileToUpload" onchange="readURL(this);" accept="image/gif, image/jpeg, image/png" style="opacity: 0; position: absolute; z-index: -1;">

		</div>
		<br/>
    </form>
</div>

<script language="JavaScript">
	function readURL(input)
	{
		if (input.files && input.files[0])
		{
			var reader = new FileReader();

			reader.onload = function (e)
			{
				document.getElementById('results').innerHTML = '<img style="width:100%;height:100%" src="' + e.target.result + '"/>';
			};

			reader.readAsDataURL(input.files[0]);
			document.getElementById("upload_webcam_image").click();
		}
	}
	
	navigator.getMedia = ( navigator.getUserMedia || navigator.webkitGetUserMedia ||
				   navigator.mozGetUserMedia || navigator.msGetUserMedia);

	navigator.getMedia({video: true},
	function()
	{
		Webcam.set({width: screen.width / 3, height: screen.height / 3, image_format: 'jpeg', jpeg_quality: 90 });
	
		Webcam.attach('#my_camera');
	},
	function() 
	{
		if (window.location.href != "http://localhost/Camagru/webcam.php?webcam=false")
		{
			alert("No webcam was detected! You may only upload images.");
			window.location.href = "http://localhost/Camagru/webcam.php?webcam=false";
		}
	});

	function take_snapshot()
	{
		Webcam.snap( function(data_uri)
		{
			$(".image-tag").val(data_uri);
			document.getElementById('results').innerHTML = '<img style="width:100%;height:100%" src="' + data_uri + '"/>';

		});

		document.getElementById("upload_webcam_image").click();
	}

	function upload_image_click()
	{
		document.getElementById("upload_image_btn").click();
	}
</script>
 
</body>
</html>