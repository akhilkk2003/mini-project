<?php
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $guardianName = $_POST["name"];

    // Handle image upload
    $targetDirectory = "img/trainer/";

    // Create the target directory if it doesn't exist
    if (!is_dir($targetDirectory)) {
        mkdir($targetDirectory, 0755, true);
    }

    $targetFile = $targetDirectory . basename($_FILES["inst_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file input is set
    if (isset($_FILES["inst_image"])) {
        $check = getimagesize($_FILES["inst_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (optional)
        if ($_FILES["inst_image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow only certain file formats (you can customize this as needed)
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload the file
            if (move_uploaded_file($_FILES["inst_image"]["tmp_name"], $targetFile)) {
                echo "The file " . htmlspecialchars(basename($_FILES["inst_image"]["name"])) . " has been uploaded.";

                // Here you can save the $guardianName and $targetFile (image path) to your database or perform any other necessary actions.
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    $sql = "INSERT INTO tbl_instructors (instructor_name, instructor_image, status) VALUES (?, ?, 'Active')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $guardianName, $targetFile);

    if ($stmt->execute()) {
        // The data has been successfully inserted.
        // You can add any further actions or redirection here.
    }
}

$sql = "SELECT * FROM tbl_instructors";
$result = $conn->query($sql);
$instructors = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $instructors[] = $row;
    }
    // Handle doctor update 
    if (isset($_POST['update_instructor'])) {
        $instructor_id = $_POST['instructor_id'];
        $instructor_name  = $_POST['instructor_name'];
        $status = $_POST['status'];
        // $instructor_quantity = $_POST['instructor_quantity'];

        //$cat_id  = $_row['cat_id'];




        // Update data in the table 
        $update_sql = "UPDATE tbl_instructors SET  
                    instructor_name  = ?, status = ? WHERE instructor_id = ?";

        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssi", $instructor_name, $status, $instructor_id);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error updating data: " . $stmt->error;
        }


        // Redirect back to the doctor list page after updating 
        //header("Location: doctors_list.php"); 
        //exit(); 
    }
    if (isset($_POST['update_image'])) {
        $instructor_id = $_POST['instructor_id'];

        // Check if a file was uploaded successfully 
        if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "img/trainer/";
            $file_extension = strtolower(pathinfo($_FILES["new_image"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;

            // Move the uploaded file to the target directory 
            if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $target_file)) {
                // Delete previous image if it exists 
                $get_previous_image_sql = "SELECT instructor_image FROM tbl_instructors WHERE instructor_id = '$instructor_id'";
                $previous_image_result = $conn->query($get_previous_image_sql);
                if ($previous_image_result->num_rows === 1) {
                    $previous_image = $previous_image_result->fetch_assoc()['instructor_image'];
                    if ($previous_image && file_exists($previous_image)) {
                        unlink($previous_image);
                    }
                }

                // Update the doctor's image path in the database 
                $update_image_sql = "UPDATE tbl_instructors SET instructor_image = '$target_file' WHERE instructor_id = '$instructor_id'";
                if ($conn->query($update_image_sql) === TRUE) {
                    // Image update successful 
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    // Image update failed 
                    echo "Error updating image in database: " . $conn->error;
                }
            } else {
                // Failed to move the uploaded file 
                echo "Failed to move uploaded file.";
            }
        }
    }
    // ... your existing code ... 

    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['instructor_id'])) {
        $instructor_id = $_GET['instructor_id'];

        // Get the image path and delete the image file 
        $get_image_sql = "SELECT instructor-image FROM tbl_instructors WHERE instructor_id = '$instructor_id'";
        $image_result = $conn->query($get_image_sql);
        if ($image_result->num_rows === 1) {
            $image_path = $image_result->fetch_assoc()['instructor_image'];
            if ($image_path && file_exists($image_path)) {
                unlink($image_path); // Delete the image file 
            }
        }

        // Delete the doctor from the database 
        $delete_sql = "DELETE FROM tbl_instructors WHERE instructor_id = '$instructor_id'";
        if ($conn->query($delete_sql) === TRUE) {
            // Deletion successful 
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            // Deletion failed 
            echo "Error deleting record: " . $conn->error;
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
      <!--<link href="css/instructor.css" rel="stylesheet">-->

</head>

<body>
    <div style="width:100%;">
        <?php include('admin_menu.php'); ?>
    </div>
    <div class="">
        <div style="width:50%;float:left;display:flex;">
            <div style=" margin-top: 50px; justify-content: center; width: 100%;">
            
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" style="width:100%;display:flex;justify-content:center" enctype="multipart/form-data" method="post">
                    <div class="row g-3 d-flex " style="width: 55%;">
                        <h2 class="text-uppercase d-flex justify-content-center ">Add instructor</h2>
                        <div class="col-sm-6">
                            <div class="form-floating">
                                <input type="text" class="form-control border-0" id="gname" name="name" placeholder="Gurdian Name" required>
                                <label for="gname" ass="text-uppercase">Your Name</label>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-floating">
                                <input type="file" class="form-control" name="inst_image" accept="image/*" required>
                                <label for="gmail" class="text-uppercase">Instructor image</label>
                            </div>
                        </div>
                        <input class="btn btn-dark w-100 py-3" type="submit" name="add" value="ADD">
                </form>
            </div>

            <div class="service-container1">
                <div class="doctors service-container">
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                        <!-- Doctor Availability -->
                        <center>
                            <h3>Instructor Availability</h3>
                        </center>
                        <label for="servicename">Service Name:</label>
                        <select id="service_id" name="servicename" required>
                            <?php

                            // Fetch doctor IDs and names from tbl_doctors
                            $sql = "SELECT * FROM tbl_services";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $service_id = $row["service_id"];
                                    $service_name = $row["service_name"];
                                    echo "<option value=\"$service_id\">$service_name</option>";
                                }
                            } else {
                                echo "<option value=\"\">No doctors available</option>";
                            }


                            ?>
                        </select>
                        <br /><br />

                        <label for="doctor_id">Select Doctor:</label>
                        <select id="doctor_id" name="doctor_id" required>
                            <?php

                            // Fetch doctor IDs and names from tbl_doctors
                            $sql = "SELECT * FROM tbl_doctors";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $doctor_id = $row["doctor_id"];
                                    $doctor_name = $row["doctor_name"];
                                    echo "<option value=\"$doctor_id\">$doctor_name</option>";
                                }
                            } else {
                                echo "<option value=\"\">No doctors available</option>";
                            }


                            ?>
                        </select>
                        <br /><br />

                        <div class="d-flex">
                            <label for="available_days">Available Days:</label> <label for="" class="range">Time Start & Ends</label>
                        </div>
                        <div class="checkbox">

                            <input type="checkbox" id="monday" name="availability_days[]" value="Monday" />
                            <label for="monday">Monday</label>

                            <input type="text" id="monday_start" name="Monday_start" placeholder="starting time" />
                            <input type="text" id="monday_end" name="Monday_end" placeholder="Ending time" />

                            <input type="checkbox" id="tuesday" name="availability_days[]" value="Tuesday" />
                            <label for="tuesday">Tuesday</label>
                            <input type="text" id="tuesday_start" name="Tuesday_start" placeholder="starting time" />
                            <input type="text" id="tuesday_end" name="Tuesday_end" placeholder="Ending time" />

                            <input type="checkbox" id="Wednesday" name="availability_days[]" value="Wednesday" />
                            <label for="Wednesday">Wednesday</label>
                            <input type="text" id="Wednesday_start" name="Wednesday_start" placeholder="starting time" />
                            <input type="text" id="Wednesday_end" name="Wednesday_end" placeholder="Ending time" />

                            <input type="checkbox" id="Thursday" name="availability_days[]" value="Thursday" />
                            <label for="Thursday">Thursday</label>
                            <input type="text" id="Thursday_start" name="Thursday_start" placeholder="starting time" />
                            <input type="text" id="Thursday_end" name="Thursday_end" placeholder="Ending time" />

                            <input type="checkbox" id="Friday" name="availability_days[]" value="Friday" />
                            <label for="tuesday">Friday</label>
                            <input type="text" id="Friday_start" name="Friday_start" placeholder="starting time" />
                            <input type="text" id="Friday_end" name="Friday_end" placeholder="Ending time" />

                            <input type="checkbox" id="Saturday" name="availability_days[]" value="Saturday" />
                            <label for="Saturday">Saturday</label>
                            <input type="text" id="Saturday_start" name="Saturday_start" placeholder="starting time" />
                            <input type="text" id="Saturday_end" name="Saturday_end" placeholder="Ending time" />

                            <!-- Repeat for other days -->
                        </div>
                        <br /><br />


                        <center>
                            <input type="submit" class="btn btn-primary py-2 px-4 ms-3" name="timeslot" value="Submit" />
                        </center>
                    </form>
                </div>
            </div>
        </div>
        <br>
        

        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Icecream Details</h5>
                        <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>


                    </div>
                    <div class="modal-body">
                        <script>
                            function showEditForm(instructorid, instructorname, status) {
                                var modal = document.getElementById("editModal");
                                var modalBody = modal.querySelector(".modal-body");
                                var form = `
                                         <form action="" method="post">
                                             <input type="hidden" name="instructor_id" value="${instructorid}">
                                             <label>Ice Cream Name:</label>
                                             <input type="text" name="instructor_name" value="${instructorname}" required><br>
                                             <label>Price:</label>
                                             <select name="status" required>
                                             <option value="Active">Active</option>
                                             <option value="Inactive">Inactive</option>
                                       
                                     
                                        </select><br>
                                        <button type="submit" name="update_instructor" class="btn btn-success">Update</button>
                                    </form>
                                `;


                                modalBody.innerHTML = form;
                                $(modal).modal("show");
                            }
                        </script>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>