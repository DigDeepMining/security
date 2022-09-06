<?php
    session_start();

    $getInvalid = array("\"", "/", "+", "=", "*", "&", "^", "%", "$", "£", "!", ">", "<", ",", ".", "|", "{", "}", "[", "]", ";", ":", "±", "§", "`", "~", "#", "'", "\\");
    $postUserNameClean = str_replace($getInvalid, " ", $_POST['uName']);

    if(!isset($_POST['uName']) || ($postUserNameClean !== $_POST['uName']))
    {
        header("Location: ../index.php?error=wrongDetails");
    }
    else
    {
        include("connectDB.php");
        $pWord = $_POST['pWord'];
        $sql = "SELECT *  
                FROM users
                WHERE username = '$postUserNameClean'
                AND password = '$pWord'";

        $result = mysqli_query($conn, $sql);
        $resultCount = mysqli_num_rows($result);

        while($row = mysqli_fetch_assoc($result))
        {
            $_SESSION['message'] = "Authenticated";
            header("Location: ../index.php");
        }

        if($resultCount === 0)
        {
            header("Location: ../index.php?error=wrongDetails");
        }   
    }
    
    ?>