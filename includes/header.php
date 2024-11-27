<?php

global $current_page;
$current_page = pathinfo(strtok(basename($_SERVER['PHP_SELF']), '?'), PATHINFO_FILENAME);
session_start();
if (isset($_SESSION['user_type'])) {
  $user_type = $_SESSION['user_type'];

  include("navOptions/{$user_type}-dashboard-nav-options.php");
} else {
  include("navOptions/{$current_page}-nav-options.php");    
}

if(!isset($_SESSION['option'])) {
  $_SESSION['option'] = 'customer';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Easyfly</title>
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">

  <link rel="stylesheet" type="text/css" href="css/<?php echo $current_page; ?>.css">

  <link rel="stylesheet" type="text/css" href="css/general.css">
</head>

<body>
  <header>

  </header>
  <nav>
    <a class="logo" href="index.php"> <img src="images/Easyfly.png" alt="site-logo"> </a>
    <?php include('navOptions/nav.php') ?>
  </nav>