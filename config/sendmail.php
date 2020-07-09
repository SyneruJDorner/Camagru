<?php
	send_confirmation_mail();
	
    function send_confirmation_mail()
    {
		include 'database.php';
		
		$name = $_GET['username'];
		
		$sql = "use users";
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		
		$stmt = $connection->prepare("SELECT * FROM registered_users WHERE username=:username");
		$stmt->execute(array(':username' => $name));
		
		$result = $stmt->fetchAll();
		//print_r($result[0]);
		
		$ID 		= $result[0]['ID'];
		$email 		= $result[0]['email'];
		$code 		= $result[0]['confirmation_code'];
		$registered = $result[0]['registered_account'];
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $to         = $email;
		$subject    = 'Confirmation for registration on Tumble Images';
        $actual_link = strstr($actual_link, 'sendmail', true);
        
        $message = "";
        $message .= 'Dear: '.$name.'
        ';
        $message .= 'Your account needs to be activated for tumbling_images. Please click on "Confirm" below to confirm your account.';
        $message .= '<html><body>';
        //$message .= '<a href="http://localhost:8080/Camagru/verify.php?id='.$ID.'&code='.$code.'">Confrim Now</a><BR>';
        $message .= '<a href="'.$actual_link.'verify.php?id='.$ID.'&code='.$code.'">Confrim Now</a><BR>';
        $message .= 'to activate  your account.';
        $message .= '</body></html>';

		$headers  = 'From: justicev18@gmail.com' . "\r\n" .
					'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=utf-8';
        if(mail($to, $subject, $message, $headers))
        {
            $_SESSION["Sent_Email"] = True;
            header("location: ../index.php");
        }
        else
        {
            echo "Email sending failed";
        }
    }
    return;
?>