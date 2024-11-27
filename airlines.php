<?php require_once('includes/showMessage.php') ?>
<?php
session_start();
if(!isset($_SESSION['user_type'])) {
    header('location: login.php');
}
require 'includes/functions.php';
displaySessionMessage();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Adding Airlines Company</title>
    <link rel="stylesheet" href="css/style.css" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  </head>

<body>

<?php include('includes/admin-nav.php') ?>;
    <main>

        <div class="container mt-5">
            <h2>Airlines</h2>


            <div class="mb-3">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAirlineModal">Add
                    Airline</button>
            </div>

            <table class="table table-striped" style="margin-bottom:0px;">
            <thead class="table-dark">
                <tr>
                    <th style="width: 20%;">Email</th>
                    <th>Password</th>
                    <th>Airline Name</th>
                    <th>Logo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                    <?php
                    include("connection.php");



                    
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_airline"])) {
                        $newEmail = $_POST["new_email"];
                        $newPassword = $_POST["new_password"];
                        $newAirlineName = $_POST["new_airline_name"];


                        $targetDir = "uploads/";
                        $targetFile = $targetDir . basename($_FILES["new_logo"]["name"]);


                        if (!file_exists($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }

                        if (move_uploaded_file($_FILES["new_logo"]["tmp_name"], $targetFile)) {
                            $newLogoPath = $targetFile;


                            $emailCheckSql = "SELECT * FROM airline WHERE email = '$newEmail'";
                            $emailCheckResult = $con->query($emailCheckSql);


                            $nameCheckSql = "SELECT * FROM airline WHERE airline_name = '$newAirlineName'";
                            $nameCheckResult = $con->query($nameCheckSql);

                            if ($emailCheckResult->num_rows > 0) {

                                setSessionMessage("Airline with this email is already registered");
                            } elseif ($nameCheckResult->num_rows > 0) {

                                setSessionMessage("The airline name is already taken");
                            } else {

                                $insertSql = "INSERT INTO airline VALUES ('$newEmail', '$newPassword', '$newAirlineName', '$newLogoPath')";
                                if ($con->query($insertSql) === TRUE) {
                                    setSessionMessage("Airline added successfully");
                                } else {
                                    setSessionMessage("An error occurred while adding the record");
                                }
                            }

                            header('location:airlines.php');

                        } else {
                            setSessionMessage("Error uploading logo");
                            header('location:airlines.php');
                        }
                    }


                    if (isset($_POST["edit_airline"])) {
                        $editEmail = $_POST["edit_email"];
                        $updateSql = "UPDATE airline SET ";

                        $updateValues = array(); 
                    

                        if (!empty($_POST["edit_password"])) {
                            $editPassword = $_POST["edit_password"];
                            $updateValues[] = "pass='$editPassword'";
                        }


                        $editAirlineName = $_POST["edit_airline_name"];
                        if (!empty($_POST["edit_airline_name"])) {
                            $nameCheckSql = "SELECT * FROM airline WHERE airline_name = '$editAirlineName'";
                            $nameCheckResult = $con->query($nameCheckSql);
                            if ($nameCheckResult->num_rows > 0) {

                                setSessionMessage("The airline name is already taken");
                                header('location: airlines.php');
                                exit();
                                
                            }
                            $updateValues[] = "airline_name='$editAirlineName'";
                        }


                        if (!empty($_FILES["edit_logo"]["name"])) {

                            $targetDir = "uploads/";
                            $targetFile = $targetDir . basename($_FILES["edit_logo"]["name"]);


                            if (!file_exists($targetDir)) {
                                mkdir($targetDir, 0777, true);
                            }

                            if (move_uploaded_file($_FILES["edit_logo"]["tmp_name"], $targetFile)) {
                                $editLogoPath = $targetFile;
                                $updateValues[] = "logo='$editLogoPath'";
                            } else {
                                setSessionMessage("Error updating logo");
                                exit;
                            }
                        }
                        

                        $updateSql .= implode(", ", $updateValues);
                        $updateSql .= " WHERE email='$editEmail'";
                        


                        if (empty($updateValues)) {
        setSessionMessage("No values provided for update");
        header('location: airlines.php');
        exit();
    }

                        if ($con->query($updateSql) === TRUE) {
                            setSessionMessage("Record updated successfully");
                            header('location: airlines.php');
                        } else {
                            echo "<script>showModal('errorModal', 'Error updating record: " . $con->error . "');</script>";
                            header('location: airlines.php');
                        }
                    }



                    if ($con->connect_error) {
                        die("connection failed: " . $con->connect_error);
                    }


                    if (isset($_GET["delete"])) {
                        $deleteEmail = $_GET["delete"];


                        $deleteSql = "DELETE FROM airline WHERE email = '$deleteEmail'";

                        if ($con->query($deleteSql) === TRUE) {
                            setSessionMessage("Airline deleted successfully");
                        } else {
                            echo 'Error deleting record: ' . $con->error;
                        }
                        header('location: airlines.php');
                    }




                    $sqlAirlines = "SELECT * FROM airline";
                    $resultAirlines = $con->query($sqlAirlines);
                    ?>
                  
                            <?php
                            while ($rowAirline = $resultAirlines->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $rowAirline["email"] . "</td>";
                                echo "<td>" . $rowAirline["pass"] . "</td>";
                                echo "<td>" . $rowAirline["airline_name"] . "</td>";
                                echo "<td><img src='" . $rowAirline["logo"] . "' alt='Airline Logo' height='50'></td>";
                                echo "<td>";
                                echo "<button class='btn btn-primary btn-sm edit-record' data-id='" . $rowAirline["email"] . "' data-toggle='modal' data-target='#editAirlineModal'>Edit</button>";
                                echo "<button class='btn btn-danger btn-sm delete-record' data-id='" . $rowAirline["email"] . "' data-toggle='modal' data-target='#deleteAirlineModal'>Delete</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <?php
                    $con->close();
                    ?>

                    <div class="modal fade" id="addAirlineModal" tabindex="-1" role="dialog"
                        aria-labelledby="addAirlineModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addAirlineModalLabel">Add Airline</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="airlines.php" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="new_email">Email</label>
                                            <input type="email" class="form-control" id="new_email" name="new_email"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_password">Password</label>
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_airline_name">Airline Name</label>
                                            <input type="text" class="form-control" id="new_airline_name"
                                                name="new_airline_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_logo">Logo Image</label>
                                            <input type="file" class="form-control-file" id="new_logo" name="new_logo"
                                                accept="image/*" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary"
                                                name="add_airline">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="editAirlineModal" tabindex="-1" role="dialog"
                        aria-labelledby="editAirlineModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editAirlineModalLabel">Edit Airline</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="airlines.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="edit_email" id="edit_email_hidden">
                                        <div class="form-group">
                                            <label for="edit_password">Password</label>
                                            <input type="password" class="form-control" id="edit_password"
                                                name="edit_password">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_airline_name">Airline Name</label>
                                            <input type="text" class="form-control" id="edit_airline_name"
                                                name="edit_airline_name">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_logo">Logo Image</label>
                                            <input type="file" class="form-control-file" id="edit_logo" name="edit_logo"
                                                accept="image/*">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary" name="edit_airline">Save
                                                Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="deleteAirlineModal" tabindex="-1" role="dialog"
                        aria-labelledby="deleteAirlineModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteAirlineModalLabel">Confirm Delete</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this airline?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>




                  
                    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


                    <script>
                      
                        function setEditEmail(editEmail) {
                            $('#edit_email_hidden').val(editEmail);
                        }

                        $(document).on("click", ".edit-record", function () {
                            var editEmail = $(this).data('id');
                            setEditEmail(editEmail);
                        });


                        $(document).on("click", ".delete-record", function () {
                            var email = $(this).data('id');
                            $("#confirmDelete").data('id', email);
                        });

                        $(document).on("click", "#confirmDelete", function () {
                            var email = $(this).data('id');
                            window.location.href = "?delete=" + email;

                        });



                    </script>

</body>

</html>