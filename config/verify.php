<?php
    include 'database.php';
    $id;
    $code;

    if(isset($_GET['id']))
    {
        $id = $_GET['id'];
        if ($id == null)
        {
            $_SESSION['message'] = "Account confirmation error!";
            header('location: ../error.php');
            return;
        }
    }

    if(isset($_GET['code']))
    {
        $code = $_GET['code'];
        if ($code == null)
        {
            $_SESSION['message'] = "Account confirmation error!";
            header('location: ../error.php');
            return;
        }
    }

    $sql = "use users";
    $connection->exec($sql);

    $stmt = $connection->prepare("SELECT * FROM registered_users WHERE ID=:ID");
    $stmt->execute(array(':ID' => $id));

    while($row = $stmt->fetch())
    {
        $_SESSION["ID"]             = $row['ID'];
        $_SESSION["username"]       = $row['username'];
        $_SESSION["email"]          = $row['email'];
        $_SESSION["code"]           = $row['confirmation_code'];
        $_SESSION["registered"]     = $row['registered_account'];

        if ($row['registered_account'] == 1)
        {
            $_SESSION['message'] = "Account confirmation error!";
            header('location: ../error.php');
            return;
        }
    }

    $sql = ("UPDATE registered_users SET registered_account=1 WHERE ID='$id' AND confirmation_code='$code'");
    $connection->exec($sql);
    $_SESSION["Verified"] = True;
    header("location: ../index.php");
?>