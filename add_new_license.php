<?php
include('connection.php');
include('mail.php');
global $date1;
global $date2;
global $date3;
global $id;
global $resu;
function fetchemail($conn, $id)
{
    $sql = "SELECT * FROM tbl_users WHERE user_id = '$id'";
    $result = $conn->query($sql);
    global $email;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $email = $row['user_email'];
        }
    }

    return $email;
}

function fetchdata($conn, $tablename)
{
    $sql = "SELECT * FROM $tablename";
    $result = $conn->query($sql);

    $data = array(); // Initialize an empty array to store the data

    if ($result->num_rows > 0) {
        // Loop through the results and store each row in the $data array
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

$learners = fetchdata($conn, 'tbl_learners_details');
function fetchTableData($conn, $tableName)
{
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

$learners = fetchTableData($conn, 'tbl_learners_details');

function imageprocessing($var, $image_file)
{
    $targetDir = "img/license/";

    // Create the target directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (move_uploaded_file($_FILES["$var"]["tmp_name"], $targetDir . $image_file)) {
        $fileAddress = $targetDir . $image_file;
        return $fileAddress;
    } else {
        echo "<script>alert('Error moving file to target directory.')</script>";
        return false;
    }
}

if (isset($_POST['new-license'])) {
    // Retrieve form data
    $name = $_POST["name"];
    $dob = $_POST["dob"];
    $number = $_POST["number"];
    $bloodgroup = $_POST["bloodgroup"];

    // Process and move uploaded files
    $photopath = imageprocessing("photo", uniqid() . '.' . strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION)));
    $eyecertificate = imageprocessing("eyecertificate", uniqid() . '.' . strtolower(pathinfo($_FILES["eyecertificate"]["name"], PATHINFO_EXTENSION)));
    $adhaarcertificate = imageprocessing("adhaarcertificate", uniqid() . '.' . strtolower(pathinfo($_FILES["adhaarcertificate"]["name"], PATHINFO_EXTENSION)));
    $birthproof = imageprocessing("birthproof", uniqid() . '.' . strtolower(pathinfo($_FILES["birthproof"]["name"], PATHINFO_EXTENSION)));

    // Check if file uploads were successful
    if ($photopath && $eyecertificate && $adhaarcertificate && $birthproof) {
        $sql = "INSERT INTO tbl_learners_details(full_name, dob, phone_number, blood_group, photo, birth_proof, aadhaar_card, eye_cert, application_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $name, $dob, $number, $bloodgroup, $photopath, $birthproof, $adhaarcertificate, $eyecertificate);

        if ($stmt->execute()) {
            echo '<script>
                    var confirmed = confirm("Documents added successfully. Click OK to continue.");
                    if (confirmed) {
                        window.location.href = "add_new_license.php";
                    }
                </script>';
        } else {
            echo "Error inserting data: " . $stmt->error;
        }

        $stmt->close();
    }
    $conn->close();
}
//Delete items
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['learner_id'])) {
    $learner_id = $_GET['learner_id'];



    // Delete the doctor from the database
    $delete_sql = "DELETE FROM tbl_learners_details WHERE learner_id = '$learner_id'";
    if ($conn->query($delete_sql) === TRUE) {
        // Deletion successful
        echo "<script>alert(Details deleted)</script>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Deletion failed
        echo "Error deleting record: " . $conn->error;
    }
}
if (isset($_POST['update_details'])) {
    $learnerId = $_POST['learner_id'];
    $fullName = $_POST['full_name'];
    $dob = $_POST['dob'];
    $phoneNumber = $_POST['phone_number'];
    $bloodGroup = $_POST['blood_group'];
    $status = $_POST['status'];

    $sql = "UPDATE tbl_learners_details SET full_name=?, dob=?, phone_number=?, blood_group=?, application_status=? WHERE learner_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $fullName, $dob, $phoneNumber, $bloodGroup, $status, $learnerId);

    if ($stmt->execute()) {
        echo '<script>alert("Details updated successfully.");</script>';
        echo '<script>window.location.href = "add_new_license.php";</script>';
    } else {
        echo '<script>alert("Error updating details. Please try again later.");</script>';
    }

    $stmt->close();
}


if (isset($_POST['fetch_date'])) {
    $selectedLearnerID = $_POST['learner_id'];
    echo $selectedLearnerID;

    // Assuming $conn is your database connection object


    $sql = "SELECT * FROM tbl_learners_details WHERE learner_id = '$selectedLearnerID'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $date1 = $row['date1'];
                $date2 = $row['date2'];
                $date3 = $row['date3'];
            }
        } else {
            echo "No results found for the selected learner.";
        }
    } else {
        echo "Error in SQL query: " . $conn->error;
    }
}
function fetchlearnername($conn, $learner_id)
{
    $sql = "SELECT * FROM tbl_learners_details WHERE learner_id = '$learner_id'";
    $result = $conn->query($sql);
    global $d, $id;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $d = $row['full_name'];
            $id = $row['user_id'];
        }
    }

    return $d;
}
function id($conn, $learner_id)
{
    $sql = "SELECT * FROM tbl_learners_details WHERE learner_id = '$learner_id'";
    $result = $conn->query($sql);
    global $d, $id;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $d = $row['full_name'];
            $id = $row['user_id'];
        }
    }

    return $id;
}
if (isset($_POST['choosed_date'])) {
    $learnerId = $_POST['learner_id'];
    $chooseddate = $_POST['date'];


    $sql = "UPDATE tbl_learners_details SET choosed_date=? WHERE learner_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $chooseddate, $learnerId);

    if ($stmt->execute()) {

        $learner_name = fetchlearnername($conn, $learnerId);
        $subject = "Learners Test Booked Successfully";
        $message = "Subject: Confirmation of Your Learner's Test Date Booking

        Dear " . $learner_name . " :,
        
        We are delighted to inform you that your learner's test date has been successfully booked through our website. This email serves as confirmation of your upcoming learner's test appointment. Please review the details below:\n
        
        - Learner Name:" . $learner_name . "\n\n 
        - Test Date:" . $chooseddate . "\n\n 
        - Test Time: 08:00 AM\n\n
        - Test Location: RTO Office Tripunithura\n\n 
        
        We recommend that you arrive at the test center at least 15 minutes before your scheduled test time. If you have any questions or need to make changes to your appointment, please contact our  team at akhilkk200313@gmail.com or +91-6235278712.
        
        We wish you the best of luck with your learner's test. Remember to stay calm, confident, and well-prepared. Safe driving is a skill that not only enhances your life but also contributes to road safety.
        
        Thank you for choosing our platform for your learner's test booking. We appreciate your trust in our services. If you have any feedback or suggestions for improvement, please do not hesitate to let us know.
        
        Drive safely and best of luck with your upcoming test!
        
        Warm regards,
        
        Akhil K K,\n
        Krishnas Driving School,\n
        6235278712";
        $email = fetchemail($conn, $id);
        email($subject, $message, $email);
        echo '<script>alert("Dates updated successfully.");</script>';
        //echo '<script>window.location.href = "add_new_license.php";</script>';
        header('location:add_new_license.php');
    } else {
        echo '<script>alert("Error updating dates. Please try again later.");</script>';
        header('location:add_new_license.php');
    }

    $stmt->close();
}
//rest date 
if (isset($_POST['fix_date'])) {
    $learnerId = $_POST['learner_id'];
    $chooseddate = $_POST['test_date'];


    $sql = "UPDATE tbl_learners_details SET dl_test=? WHERE learner_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $chooseddate, $learnerId);

    if ($stmt->execute()) {

        $learner_name = fetchlearnername($conn, $learnerId);
        $subject = "Driving License Test Date is Successfully Boooked";
        $message = "Subject: Confirmation of Your Driving License Test Date Booking.

        Dear " . $learner_name . " :,
        
        We are delighted to inform you that your Driving test date has been successfully booked through our website. This email serves as confirmation of your upcoming learner's test appointment. Please review the details below:\n
        
        - Learner Name:" . $learner_name . "\n\n 
        - Test Date:" . $chooseddate . "\n\n 
        - Test Time: 08:30 AM\n\n
        - Test Location: Old Bus Stand Poothotta\n\n 
        
        We recommend that you arrive at the test center at least 15 minutes before your scheduled test time. If you have any questions or need to make changes to your appointment, please contact our  team at akhilkk200313@gmail.com or +91-6235278712.
        
        We wish you the best of luck with your learner's test. Remember to stay calm, confident, and well-prepared. Safe driving is a skill that not only enhances your life but also contributes to road safety.
        
        Thank you for choosing our platform for your learner's test booking. We appreciate your trust in our services. If you have any feedback or suggestions for improvement, please do not hesitate to let us know.
        
        Drive safely and best of luck with your upcoming test!
        
        Warm regards,
        
        Akhil K K,\n
        Krishnas Driving School,\n
        6235278712";
        $email = fetchemail($conn, $id);
        email($subject, $message, $email);
        echo '<script>alert("Dates updated successfully.");</script>';
        //echo '<script>window.location.href = "add_new_license.php";</script>';
    } else {
        echo '<script>alert("Error updating dates. Please try again later.");</script>';
    }

    $stmt->close();
}
//fail or pass
if (isset($_POST['exam_status'])) {
    $learnerId = $_POST['learner_id'];
    $res = $_POST['statusf'];


    $sql = "UPDATE tbl_learners_details SET learners_test_status=? WHERE learner_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $res, $learnerId);

    if ($stmt->execute()) {
        $learner_name = fetchlearnername($conn, $learnerId);
        $subject = "Notification of Learners Test";
        if ($res == 'PASSED') {
            $message = "Subject: Confirmation of Your Learners License Test Result.

        Dear " . $learner_name . " :,
        
        I am delighted to inform you that you have successfully passed your driving learner's test with [Driving School/Testing Authority Name]. Congratulations on this significant achievement!\n
        
        - Learner Name:" . $learner_name . "\n\n  
        Your dedication and hard work in preparing for the test have paid off, It is important to continue to practice safe driving habits and adhere to all traffic regulations as you gain more experience on the road..\n
        Your learner's permit will now allow you to gain practical experience under the guidance of a licensed driver. Make the most of this period by driving with a qualified supervisor to further develop your skills..\n\n

        Sincerely,
        Akhil K K,\n
        Krishnas Driving School,\n
        6235278712";
        } else {
            $message = "Subject: Confirmation of Your Learners License Test Result.

            Dear " . $learner_name . " :,
            
            We regret to inform you that you did not pass the recent driving learner's test with [Driving School/Testing Authority Name].Please remember that setbacks are a part of the learning process, and we wil provide more coaching for improvement.\n
            
            - Learner Name:" . $learner_name . "\n\n  
            We understand that this outcome may be disheartening, but it is essential to view it as a learning experience. Many individuals face similar challenges during their learner's permit journey, and it should not deter you from achieving your goal of becoming a safe and confident driver..\n
            Your safety and the safety of others on the road are our top priorities, and we believe that with dedication and practice, you can achieve success in your next attempt..\n\n
    
            Sincerely,
            Akhil K K,\n
            Krishnas Driving School,\n
            6235278712";
        }
        $email = fetchemail($conn, $id);
        email($subject, $message, $email);
        echo '<script>alert("Dates updated successfully.");</script>';
        //echo '<script>window.location.href = "add_new_license.php";</script>';
    } else {
        echo '<script>alert("Error updating dates. Please try again later.");</script>';
    }

    $stmt->close();
}
if (isset($_POST['driving_date'])) {
    $learnerId = $_POST['learner_id'];
    $resu = $_POST['statusd'];
   
    


    $sql = "UPDATE tbl_learners_details SET driving_test_status=? WHERE learner_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $resu, $learnerId);
    $learner_name = fetchlearnername($conn, $learnerId);

    if ($stmt->execute()) {
       

        $subject = "Notification of Successful Driving Test";
        if ($resu == 'PASSED') {
            $message = "Subject: Confirmation of Your Driving License Test Result.

        Dear " . $learner_name . " :,
        
        I hope this message finds you well. We are pleased to inform you that you have successfully passed your driving test with [Driving School/Testing Authority Name]. Congratulations on this significant achievement!\n
        
        - Learner Name:" . $learner_name . "\n\n  
        Your dedication and hard work in preparing for the test have paid off, and we are confident that you will continue to uphold safe driving practices on the road.\n
        Once again, congratulations on passing your driving test, and we wish you safe and enjoyable journeys on the road.\n\n

        Sincerely,
        Akhil K K,\n
        Krishnas Driving School,\n
        6235278712";
        } else {
            $message = "Subject: Confirmation of Your Driving License Test Result.

            Dear " . $learner_name . " :,
            
            We regret to inform you that the recent driving test you took with[Driving School/Testing Authority Name] did not result in a passing grade.. We will do our Best and  concentrate on where you have faced Difficulties on the Test Ground \n
            
            - Learner Name:" . $learner_name . "\n\n  
            Your performance during the test indicated some areas that require improvement and we are confident that you will improve on upcoming classes.\n
            Failing the test is a valuable learning experience, and with dedication and practice, you can achieve success in your next attempt..\n\n
            Thank you for your commitment to safe driving, and we wish you the best of luck in your continued efforts to obtain your driver's license.\n
    
            Sincerely,
            Akhil K K,\n
            Krishnas Driving School,\n
            6235278712";
        }
        $id = id($conn, $learnerId);
        $email = fetchemail($conn, $id);
        email($subject, $message, $email);
        echo '<script>alert("Dates updated successfully.");</script>';
        //echo '<script>window.location.href = "add_new_license.php";</script>';
    } else {
        echo '<script>alert("Error updating dates. Please try again later.");</script>';
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Drivin - Driving School Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
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
    <link rel="stylesheet" href="css/style.css">

    <!-- Link to your custom CSS -->
    <link rel="stylesheet" href="css/license.css">
    <style>

    </style>


</head>

<body style="background-color: #bebebe;">
    <?php include('admin_menu.php') ?>

    <center style=" margin-top: 35px;">
        <center>
            <h1><u>APPLICATION FORMS</u></h1>
        </center>

        <div class="table-responsive service-container2 ">
            <table class="col-* table table-success table-striped shadow-lg t-hover" style="width: 93%;">
                <thead>
                    <tr>
                        <th>Leaner's ID</th>
                        <th>Full Name</th>
                        <th>DOB</th>
                        <th>Phone Number</th>
                        <th>Blood Group</th>
                        <th>Photo</th>
                        <th>Birth Proof</th>
                        <th>Adhaar Card</th>
                        <th>Eye Certificate</th>
                        <th>Signature</th>

                        <th>Application Status</th>
                        <th>Update</th>




                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($learners as $index => $leaner) : ?>
                        <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                            <td><?= $leaner['learner_id']; ?></td>
                            <td><?= $leaner['full_name']; ?></td>
                            <td><?= $leaner['dob']; ?></td>
                            <td><?= $leaner['phone_number']; ?></td>
                            <td><?= $leaner['blood_group']; ?></td>
                            <td>
                                <a href="#" onclick="openDocumentPopup('<?= $leaner['photo']; ?>')">View Document</a>
                            </td>
                            <td><a href="#" onclick="openDocumentPopup('<?= $leaner['birth_proof']; ?>')">View Document</a></td>
                            <td><a href="#" onclick="openDocumentPopup('<?= $leaner['aadhaar_card']; ?>')">View Document</a></td>
                            <td><a href="#" onclick="openDocumentPopup('<?= $leaner['eye_cert']; ?>')">View Document</a></td>
                            <td><a href="#" onclick="openDocumentPopup('<?= $leaner['signature']; ?>')">View Document</a></td>
                            <td><?= $leaner['application_status']; ?></td>

                            <div class="d-flex">
                                <td class="wrapper">
                                    <a href="javascript:void(0);" class="btn btn-info" onclick="showEditForm(
        '<?= $leaner['learner_id']; ?>',
        '<?= $leaner['full_name']; ?>',
        '<?= $leaner['dob']; ?>',
        '<?= $leaner['phone_number']; ?>',
        '<?= $leaner['blood_group']; ?>',
        '<?= $leaner['application_status']; ?>'
    )">Edit</a>
                                    <a href="#" class="btn btn-danger" onclick="confirmDelete(<?= $leaner['learner_id']; ?>)">Del</a>
                                </td>


                            </div>

                        <?php endforeach; ?>
            </table>
        </div>

        <!-- JavaScript to handle edit form display and submission -->
        <!-- ... your existing code ... -->

        <!-- JavaScript to handle edit form display and submission -->
        <!-- Modal for editing doctor details -->




    </center>


    <!--<div class="container mt-5">

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="license-container">
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="learner-name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="learner-name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="learner-dob" class="form-label">Date Of Birth</label>
                            <input type="text" class="form-control" id="learner-dob" name="dob" placeholder="Date Of Birth">
                        </div>
                        <div class="mb-3">
                            <label for="learner-phonenumber" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="learner-phonenumber" name="number" maxlength="10">
                        </div>
                        <div class="mb-3">
                            <label for="learner-bloodgroup" class="form-label">Blood Group</label>
                            <input type="text" class="form-control" id="learner-bloodgroup" name="bloodgroup">
                        </div>
                        <div class="documents">
                            <div class="row">
                                <div class="col-md-4">PHOTO</label>
                                    <input type="file" class="form-control" name="photo">
                                </div>
                                <div class="col-md-4">
                                    <label for="identificationmark" class="form-label">BIRTH PROOF </label>
                                    <input type="file" class="form-control" name="birthproof" accept="image/*">
                                </div>
                                <div class="col-md-4">
                                    <label for="inputState" class="form-label">Adhaar certificate</label>
                                    <input type="file" class="form-control" name="adhaarcertificate" accept="image/*">
                                </div>
                                <div class="col-md-4">
                                    <label for="inputZip" class="form-label">Eye certificate</label>
                                    <input type="file" class="form-control" name="eyecertificate" accept="image/*">
                                </div>

                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="submit" class="btn btn-primary" id="learner-bloodgroup" name="new-license">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>-->
    <div style="display: grid;justify-content: center;">
        <div style="width: 181%;margin-left: -101px;">
            <center>
                <h2>Learners Test Date Booking </h2>
            </center>
            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">


                <div class="col-sm-12">

                    <div class="form-floating">

                        <select class="form-control border-0" name="learner_id" aria-label="Default select example">

                            <?php

                            foreach ($learners as $index => $learner) {
                                echo '<option value="' . $learner['learner_id'] . '">' . $learner['full_name'] . '</option>';
                            }
                            ?>

                        </select>
                        <label for="gmail">Learner Name</label>
                    </div>
                    <input type="submit" name="fetch_date" value="filter">

                </div>

                <div class="col-sm-12">
                    <div class="form-floating">
                        <select class="form-control border-0" name="date" aria-label="Default select example">
                            <option value="<?php echo $date1; ?>"><?php echo $date1; ?></option>
                            <option value="<?php echo $date2; ?>"><?php echo $date2; ?></option>
                            <option value="<?php echo $date3; ?>"><?php echo $date3; ?></option>

                        </select>

                        <label for="gmail">Learner Date</label>
                        <input type="submit" value="Fix date" name="choosed_date">
                    </div>
                </div>
            </form>
        </div>
        <div style="width: 181%;margin-left: -101px;">
            <div>
                <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                    <center>
                        <h2>Learners Test Result</h2>
                    </center>
                    <select class="form-control border-0" name="learner_id" aria-label="Default select example">

                        <?php

                        foreach ($learners as $index => $learner) {
                            echo '<option value="' . $learner['learner_id'] . '">' . $learner['full_name'] . '</option>';
                        }
                        ?>

                    </select>
                    <select class="form-control border-0" style="margin-top: 12px;" name="statusf" id="status" required>
                        <option value="PASSED" selected>PASSED</option>
                        <option value="FAILED">FAILED</option>

                    </select><br>

                    <input type="submit" name="exam_status" value="Update">
                </form>
            </div>
            <div style="width: 180%;margin-left: -100px;">
                <div class="d-flex">
                    <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                        <center>
                            <h2>Driving Test Date Appointment </h2>
                        </center>
                        <select class="form-control border-0" name="learner_id" aria-label="Default select example">

                            <?php

                            foreach ($learners as $index => $learner) {
                                echo '<option value="' . $learner['learner_id'] . '">' . $learner['full_name'] . '</option>';
                            }
                            ?>

                        </select>
                        <input type="date" class="form-control border-0" style="margin-top: 12px;" name="test_date" id="">
                        <input type="submit" name="fix_date" value="Fix Date">
                    </form>

                </div>
            </div>
        </div>

        <div style="width: 180%;margin-left: -100px;">
            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                <center>
                    <h2>Driving Test Result </h2>
                </center>
                <div class="form-floating">
                    <select class="form-control border-0" name="learner_id" aria-label="Default select example">

                        <?php

                        foreach ($learners as $index => $learner) {
                            echo '<option value="' . $learner['learner_id'] . '">' . $learner['full_name'] . '</option>';
                        }
                        ?>

                    </select>
                    <select style="margin-top: 15px;" class="form-control border-0" name="statusd" id="status" required>
                        <option value="PASSED" selected>PASSED</option>
                        <option value="FAILED">FAILED</option>
                    </select><br>

                    <input type="submit" name="driving_date" value="Update">
                </div>

            </form>
        </div>

    </div>


    <!-- The Document  Modal -->
    <div id="documentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDocumentPopup()">&times;</span>
            <img id="documentImage" src="" alt="User Document">
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                        <input type="hidden" name="learner_id" id="editTblId">
                        <label>Learner Name:</label>
                        <input type="text" name="full_name" id="full_name" required><br>
                        <label>DOB:</label>
                        <input type="text" name="dob" id="dob" required><br>
                        <label>Phone Number:</label>
                        <input type="text" name="phone_number" id="num" required><br>
                        <label>Blood Group:</label>
                        <input type="text" name="blood_group" id="nu" required><br>
                        <label>Status:</label>
                        <select name="status" id="status" required>
                            <option value="Pending" selected>Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select><br>
                        <button type="submit" name="update_details" class="btn btn-success">Update</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript functions to open and close the documents -->
    <script>
        function openDocumentPopup(imageSrc) {
            var modal = document.getElementById("documentModal");
            var image = document.getElementById("documentImage");

            image.src = imageSrc;
            modal.style.display = "block";
        }

        function closeDocumentPopup() {
            var modal = document.getElementById("documentModal");
            modal.style.display = "none";
        }
    </script>
    <script>
        function confirmDelete(learnerId) {
            var confirmed = confirm("Are you sure you want to delete this record?");
            if (confirmed) {
                window.location.href = "<?= $_SERVER["PHP_SELF"] ?>?action=delete&learner_id=" + learnerId;
            }
        }
    </script>







    <script>
        function handleSearch() {
            var searchInput = document.getElementById("searchInput").value.toLowerCase();
            var tableRows = document.querySelectorAll(".table-row");

            tableRows.forEach(function(row) {
                var rowData = row.innerText.toLowerCase();
                if (rowData.includes(searchInput)) {
                    row.style.display = "table-row";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
    <script>
        // Get references to the radio buttons and the "New Address" field
        const licenceRenewalRadio = document.getElementById("licence_renewal");
        const changeAddressRadio = document.getElementById("change_address");
        const newAddressField = document.getElementById("newAddress");
        const new_AddressField = document.getElementById("new_Address");

        newAddressField.style.display = "none";
        new_AddressField.style.display = "none";
        // Add event listeners to the radio buttons
        licenceRenewalRadio.addEventListener("change", function() {
            // Hide the "New Address" field when "Licence Renewal" is selected
            newAddressField.style.display = "none";
            new_AddressField.style.display = "none";

        });
        changeAddressRadio.addEventListener("change", function() {
            // Display the "New Address" field when "Change of Address" is selected
            newAddressField.style.display = "block";
            new_AddressField.style.display = "block";

        });
    </script>
    <script>
        function showEditForm(learnerId, fullName, dob, phoneNumber, bloodGroup, status) {
            var modal = document.getElementById("editModal");
            var learnerIdField = document.getElementById("editTblId");
            var fullNameField = document.getElementById("full_name");
            var dobField = document.getElementById("dob");
            var phoneNumberField = document.getElementById("num");
            var bloodGroupField = document.getElementById("nu");
            var Status = document.getElementById("status");

            learnerIdField.value = learnerId;
            fullNameField.value = fullName;
            dobField.value = dob;
            phoneNumberField.value = phoneNumber;
            bloodGroupField.value = bloodGroup;
            Status.value = status;

            $(modal).modal("show");
        }
    </script>



    <!-- Add Bootstrap JS (optional) -->
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>