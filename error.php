<?php
	if (session_status() == PHP_SESSION_NONE)
		session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Error Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
</head>
<body>
    <h1>Error: <?php echo $_SESSION['message']; ?></h1>
    <button type="button" onclick="document.location.href='index.php'">Back to login page</button>
</body>
</html>