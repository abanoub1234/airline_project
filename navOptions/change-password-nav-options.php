<?php
$user_type = $_SESSION['user_type'];
$navOptions = array(
    "Home" => "index.php",
    "About Us" => "aboutUs.php",
    "Book Now" => "booking-form.php",

    "Dashboard" => "{$user_type}-dashboard.php",
    "Settings" => array(
        "Change Password" => "change-password.php",
        "Log out" => "logout.php",

    )

);
?>