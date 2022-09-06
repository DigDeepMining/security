<?php
    session_start();

    if((isset($_SESSION['message'])) && ($_SESSION['message'] !== "Authenticated"))
    {
        header("Location: index.php?error=wrongDetails");
    }
?>