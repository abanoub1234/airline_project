<?php
if(isset($_POST['submit'])){
    $db_host = "localhost"; 
    $db_user = "root"; 
    $db_pass = ""; 
    $db_name = "booking"; 

    $con = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($con->connect_error) {
        die("connection failed: " . $con->connect_error);
    }

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $check_query = "SELECT * FROM customer WHERE email = ? OR userName = ?";
    $check_stmt = $con->prepare($check_query);
    $check_stmt->bind_param("ss", $email, $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email or username already exists. Please choose a different email or username.";
    } else {
        $insert_query = "INSERT INTO customer (firstName, lastName, userName, email, phone, gender, pass, confirmPass) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($insert_query);
        $stmt->bind_param("ssssisss", $first_name, $last_name, $username, $email, $phone, $gender, $password, $confirm_password);

        if ($stmt->execute()) {
            echo "Data inserted successfully.";
        } else {
            echo "Error: " . $insert_query . "<br>" . $con->error;
        }
        $stmt->close();
    }

    // Close the check statement and connection
    $check_stmt->close();
    $con->close();
}
?>