<?php
include('connection.php');
function slot($conn, $day)
{
    $sql = "SELECT * FROM tbl_timeslot WHERE days=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $day);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['slot_id'];
    }

    $stmt->close();

    return $id;
}

if (isset($_POST["timeslot"])) {
    timeSlot();
}
function timeSlot()
{
    global $conn;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["timeslot"])) {
        $service_id = $_POST["coursename"];
        $instructor_id = $_POST["instructor_id"];
        $availability_days = isset($_POST["availability_days"]) ? $_POST["availability_days"] : [];
        $sqld = "SELECT * FROM tbl_instructor_time WHERE course_id = '$service_id' AND instructor_id='$instructor_id'";
        $result1 = mysqli_query($conn, $sqld);
        if (mysqli_num_rows($result1)) {
            echo '<script>
            if (confirm("Doctor time was already added ")) {
                window.location.href = "instructors.php";
            }
        </script>';
        } else {

            if (empty($availability_days)) {
                echo '<script>alert("Please choose at least one available day");</script>';
            } else {
                // Loop through selected days and insert into tbl_appointment
                foreach ($availability_days as $day) {
                    // Check if checkboxes for morning, afternoon, and evening are checked
                    $id = slot($conn, $day);
                    //$morning_checked = isset($_POST[$day . "_morning"]) ? 1 : 0;
                    //$afternoon_checked = isset($_POST[$day . "_afternoon"]) ? 1 : 0;
                    //$evening_checked = isset($_POST[$day . "_evening"]) ? 1 : 0;
                    if (isset($_POST[$day . "_morning"])) {
                        $m_active = "Active";
                    } else {
                        $m_active = "deactive";
                    }
                    
                    if (isset($_POST[$day . "_evening"])) {
                        $e_active = "Active";
                    } else {
                        $e_active = "deactive";
                    }
                    // Insert data into tbl_appointment
                    echo $instructor_id .' '.$service_id.' '.$id;
                    $sql = "INSERT INTO tbl_instructor_time (instructor_id, course_id, slot_id, morning,evening, status, created_at) 
                    VALUES ( ?, ?, ?, ?, ?, 'Active', NOW())";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iiiss", $instructor_id, $service_id, $id, $m_active, $e_active);
                   

                    if ($stmt->execute()) {
                        // Handle success
                    } else {
                        echo "Error inserting data: " . $stmt->error;
                    }

                    $stmt->close();
                    $m_active = "deactive";
                   
                    $e_active = "deactive";
                    $id = "";
                }

                echo '<script>
                    if (confirm("Instructor availability added successfully. Click OK to continue.")) {
                        window.location.href = "instructors.php";
                         }
                </script>';
            }
            $conn->close();
        }
    }
}
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
        $get_image_sql = "SELECT instructor_image FROM tbl_instructors WHERE instructor_id = '$instructor_id'";
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
      <link href="css/instructor.css" rel="stylesheet">

</head>

<body>
    <div style="width:100%;">
        <?php include('admin_menu.php'); ?>
    </div>
    <div class="">
        <div style="width:100%;float:left;display:grid;">
            <div style=" margin-top: 50px; justify-content: center;display:flex; width: 97%;">
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" style="display:flex;justify-content:center" enctype="multipart/form-data" method="post">
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
                        <!-- <label for="coursename">course List:</label>
                        <select id="service_id" name="coursename" required>
                            <?php

                            // Fetch doctor IDs and names from tbl_doctors
                            $sql = "SELECT * FROM tbl_package";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $package_id = $row["package_id"];
                                    $package_name = $row["package_name"];
                                    echo "<option value=\"$package_id\">$package_name</option>";
                                }
                            } else {
                                echo "<option value=\"\">No course available</option>";
                            }


                            ?>
                        </select> -->
                        <br /><br />

                        <label for="instructor_id">Select Instructor:</label>
                        <select id="instructor_id" name="instructor_id" required>
                            <?php

                            // Fetch doctor IDs and names from tbl_doctors
                            $sql = "SELECT * FROM tbl_instructors";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $instructor_id = $row["instructor_id"];
                                    $instructor_name = $row["instructor_name"];
                                    echo "<option value=\"$instructor_id\">$instructor_name</option>";
                                }
                            } else {
                                echo "<option value=\"\">No instructor available</option>";
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

                        <input type="checkbox" id="monday_morning" name="Monday_morning" value="Morning" />
                        <label for="monday_morning">Morning</label>

                       

                        <input type="checkbox" id="monday_evening" name="Monday_evening" value="Evening" />
                        <label for="monday_evening">Evening</label>
                    </div>

                    <div class="checkbox">
                        <input type="checkbox" id="tuesday" name="availability_days[]" value="Tuesday" />
                        <label for="tuesday">Tuesday</label>

                        <input type="checkbox" id="tuesday_morning" name="Tuesday_morning" value="Morning" />
                        <label for="tuesday_morning">Morning</label>

                       

                        <input type="checkbox" id="tuesday_evening" name="Tuesday_evening" value="Evening" />
                        <label for="tuesday_evening">Evening</label>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" id="wednesday" name="availability_days[]" value="Wednesday"  style="
    margin-left: 4px;
"/>
                        <label for="wednesday">Wednesday</label>

                        <input type="checkbox" id="wednesday_morning" name="Wednesday_morning" value="Morning"  style="margin-left: -31px;" >
                        <label for="wednesday_morning">Morning</label>

                        

                        <input type="checkbox" id="wednesday_evening" name="Wednesday_evening" value="Evening"  style="margin-left: -16px;"/>
                        <label for="wednesday_evening">Evening</label>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" id="Thursday" name="availability_days[]" value="Thursday" />
                        <label for="Thursday">Thursday</label>

                        <input type="checkbox" id="Thursday_morning" name="Thursday_morning" value="Morning" />
                        <label for="tuesday_morning">Morning</label>


                        <input type="checkbox" id="Thursday_evening" name="Thursday_evening" value="Evening" />
                        <label for="tuesday_evening">Evening</label>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" id="Friday" name="availability_days[]" value="Friday" />
                        <label for="Friday">Friday</label>

                        <input type="checkbox" id="Friday_morning" name="Friday_morning" value="Morning" />
                        <label for="Friday_morning">Morning</label>

                       

                        <input type="checkbox" id="Friday_evening" name="Friday_evening" value="Evening" />
                        <label for="Friday_evening">Evening</label>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" id="Saturday" name="availability_days[]" value="Saturday" />
                        <label for="Saturday">Saturday</label>

                        <input type="checkbox" id="Saturday_morning" name="Saturday_morning" value="Morning" />
                        <label for="Saturday_morning">Morning</label>


                        <input type="checkbox" id="Saturday_evening" name="Saturday_evening" value="Evening" />
                        <label for="Saturday_evening">Evening</label>
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
        <div>
            <table class=" table table-dark table-striped shadow-lg " style=" width: 94%; margin-left: 3%;">
                <thead>
                    <tr>
                        <th>instructor ID</th>
                        <th>instructor Name</th>
                        <th>Status</th>
                        <th>instructor image</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($instructors as $index => $instructor) : ?>
                        <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                            <td><?= $instructor['instructor_id']; ?></td>
                            <td><?= $instructor['instructor_name']; ?></td>
                            <td><?= $instructor['status']; ?></td>
                            <td class="flex">
                                <div class="image-container">
                                    <img src="<?= $instructor['instructor_image']; ?>" alt="" style="width:106px;" class="icon list-icon">
                                    <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data" class="upload-form">
                                        <input type="file" name="new_image" accept="image/*">
                                        <input type="hidden" name="instructor_id" value="<?= $instructor['instructor_id']; ?>">
                                        <input class="btn btn-primary" type="submit" name="update_image" value="Change Image" style=" width: 22%; margin-left: -122px;">
                                    </form>
                                </div>
                            </td>



                            <div class="d-flex">
                                <!-- ... your existing table rows ... -->
                                <td class="wrapper">
                                    <a href="javascript:void(0);" class="btn btn-info" onclick="showEditForm(
                                                 <?= $instructor['instructor_id']; ?>,
                                                 '<?= $instructor['instructor_name']; ?>',
                                                 '<?= $instructor['status']; ?>'
                                                                                
                                                                                
                                             )">Edit</a>

                                    <a href="<?= $_SERVER["PHP_SELF"] ?>?action=delete&instructor_id=<?= $instructor['instructor_id']; ?>" class="btn btn-danger">Del</a>
                                </td>

                            </div>

                        <?php endforeach; ?>
            </table>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Instructor Details</h5>
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
                                             <label>Instructor Name:</label>
                                             <input type="text" name="instructor_name" value="${instructorname}" required><br>
                                             <label>Status:</label>
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