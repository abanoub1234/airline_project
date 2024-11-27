<?php
session_start();
require_once('includes/showMessage.php');
require 'includes/functions.php';

if (!isset($_SESSION['user_type'])) {
    header('location: login.php');
    exit();
}

displaySessionMessage();

if (isset($_POST['flight_but'])) {
    require 'connection.php'; 

    $source_date = $_POST['source_date'];
    $source_time = $_POST['source_time'];
    $dest_date = $_POST['dest_date'];
    $dest_time = $_POST['dest_time'];
    $dep_airport = $_POST['dep_airport'];
    $arr_airport = $_POST['arr_airport'];
    $seats = $_POST['seats'];
    $price = $_POST['price'];
    $airline_name = $_POST['airline_name'];
    $flight_class = $_POST['flight_class'];
    if (empty($arr_airport) || empty($dep_airport) || $airline_name == 'Select Airline') {
        setSessionMessage("Please select all required fields.");
        header('Location: add-flight.php');
        exit();
    }

    if ($dep_airport === $arr_airport) {
        setSessionMessage("Source and Destination airport can't be the same");
        header('Location: add-flight.php');
        exit();
    }

    $source_timestamp = strtotime("$source_date $source_time");
    $dest_timestamp = strtotime("$dest_date $dest_time");

    if ($source_timestamp >= $dest_timestamp) {
        setSessionMessage("Destination time or date should be greater than the Source time or date");
        header('Location: add-flight.php');
        exit();
    }

    $dep_airport_id_query = "SELECT airport_id FROM airport WHERE airport_name = '$dep_airport'";
    $arr_airport_id_query = "SELECT airport_id FROM airport WHERE airport_name = '$arr_airport'";
    $airline_email_query = "SELECT email FROM airline WHERE airline_name = '$airline_name'";

    $dep_airport_id_result = mysqli_query($con, $dep_airport_id_query);
    $arr_airport_id_result = mysqli_query($con, $arr_airport_id_query);
    $airline_email_result = mysqli_query($con, $airline_email_query);

    if (!$dep_airport_id_result || !$arr_airport_id_result || !$airline_email_result) {
        header("Location: add-flight.php?error=sqlerr");
        exit();
    }

    $dep_airport_id_row = mysqli_fetch_assoc($dep_airport_id_result);
    $arr_airport_id_row = mysqli_fetch_assoc($arr_airport_id_result);
    $airline_email_row = mysqli_fetch_assoc($airline_email_result);

    $dep_airport_id = $dep_airport_id_row['airport_id'];
    $arr_airport_id = $arr_airport_id_row['airport_id'];
    $airline_email = $airline_email_row['email'];
    $sql = "INSERT INTO flight (source_date, source_time, dest_date, dest_time, dep_airport, arr_airport, seats, price, flight_class, airline_name, dep_airport_id, arr_airport_id, airline_email) 
            VALUES ('$source_date', '$source_time', '$dest_date', '$dest_time', '$dep_airport', '$arr_airport', '$seats', '$price','$flight_class', '$airline_name', $dep_airport_id, $arr_airport_id, '$airline_email')";

    if (mysqli_query($con, $sql)) {
        setSessionMessage("Successfully inserted");
        header('Location: add-flight.php');
        exit();
    } else {
        header("Location: add-flight.php?error=sqlerr");
        exit();
    }
} else {
    header("Location: add-flight.php");
    exit();
}
?>
