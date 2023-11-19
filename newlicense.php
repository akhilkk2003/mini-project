<?php
session_start();
include_once 'mail.php';
include('connection.php');
if(isset($_SESSION['email'])){

}else{
    echo '<script>
        var confirmed = confirm("user have not signed up or logged in");
        if (confirmed) {
            window.location.href = "signup.php";
        }else{
            window.location.href = "index.php";

        }
    </script>';
}
$learner_id = '';
$photoPath = null;
$aadhaarPath = null;
$birthProofPath = null;
$eyeCertPath = null;
$signature = null;
$package = '';
$id = '';
$flag = 1;
function userId( $email)
{
    global $conn;
    $sql = "SELECT user_id FROM tbl_users WHERE user_email = '$email' ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        $id = $row['user_id'];
    }
    return $id;
}
$username = $_SESSION['name'];
$email = $_SESSION['email'];
$password = $_SESSION['password'];

$userId = userId($email);
function fetchlearner_id($conn, $tablename, $userId, $package)
{
    $sql = "SELECT learner_id FROM $tablename WHERE user_id='$userId' AND package_id='$package'";
    $result = $conn->query($sql);

    // Initialize an empty array to store the data

    if ($result->num_rows > 0) {
        // Loop through the results and store each row in the $data array
        while ($row = $result->fetch_assoc()) {
            $data = $row['learner_id'];
        }
    }

    return $data;
}
function fetchdata($conn, $tablename)
{
    $sql = "SELECT * FROM $tablename";
    $result = $conn->query($sql);

    if (!$result) {
        // Query execution failed, handle the error
        echo "Error: " . $conn->error;
        return null; // or handle the error in another way, depending on your requirements
    }

    $data = array(); // Initialize an empty array to store the data

    if ($result->num_rows > 0) {
        // Loop through the results and store each row in the $data array
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

$packages = fetchdata($conn, 'tbl_package');
//date stored

if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["ADD_DATE"]))) {
    $selectedDate1 = $_POST["test_date1"];
    $selectedDate2 = $_POST["test_date2"];
    $selectedDate3 = $_POST["test_date3"];

    // Check if the selected date is not a weekend or Wednesday
    $email=$_SESSION['email'];
    $userId=userId($email);
    $test_status='pending';
    $dl_status='pending';
    $c_date='is processing';
    $sql = "UPDATE tbl_learners_details SET date1 = '$selectedDate1', date2 = '$selectedDate2', date3 = '$selectedDate3' ,test_status='$test_status',dl_test='$dl_status',choosed_date='$c_date' WHERE user_id='$userId'";

    if ($conn->query($sql) === TRUE) {
        $email=$_SESSION['email'];
        
        email('Date Confirmed','Your Leaners Test Date is Successfully Booked',$email);
        echo '<script>
        var confirmed = confirm("We have received your concerned Dates and the Approved Date will be updated through Email");
        if (confirmed) {
            window.location.href = "index.php";
        }
    </script>';

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


if ((isset($_POST['new_lic']))) {

    // Get form data
    $name = $_POST["name"];
    $dob = $_POST["dob"];
    $ph = $_POST["ph"];
    $bloodGroup = $_POST["blood"];

    $package = $_POST["pack"];
    $packsql = "SELECT * FROM tbl_package WHERE package_id='$package'";
    $result6 = $conn->query($packsql);
    if ($result6->num_rows > 0) {
        // Loop through the results and store each row in the $data array
        while ($row = $result6->fetch_assoc()) {
            $price = $row['package_price'];
        }
    }



    // Process image uploads

    function imageprocessing($var, $image_file)
    {
        if ($var == "photo") {
            $targetDir = "img/photo/";
        } else if ($var == "eyecertificate") {
            $targetDir = "img/eye_cert/";
        } else if ($var == "adhaarcertificate") {
            $targetDir = "img/aadhaar/";
        } else if ($var == "birthproof") {
            $targetDir = "img/birth/";
        } else if ($var == "signature") {
            $targetDir = "img/sig/";
        }


        // Create the target directory if it doesn't exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (move_uploaded_file($_FILES[$var]["tmp_name"], $targetDir . $image_file)) {
            $fileAddress = $targetDir . $image_file;
            return $fileAddress;
        } else {
            echo "Error uploading $var. Error code: " . $_FILES[$var]["error"];
            return false;
        }
    }



    // Move uploaded images to specified directories
    $photopath = imageprocessing("photo", $_FILES["photo"]["name"]);
    $eyecertificate = imageprocessing("eyecertificate", $_FILES["eyecertificate"]["name"]);
    $adhaarcertificate = imageprocessing("adhaarcertificate", $_FILES["adhaarcertificate"]["name"]);
    $birthproof = imageprocessing("birthproof", $_FILES["birthproof"]["name"]);
    $signature = imageprocessing("signature", $_FILES["signature"]["name"]);
    if ($photopath !== "" && $eyecertificate !== "" && $adhaarcertificate !== "" && $birthproof !== "") {
        $sql = "INSERT INTO tbl_learners_details(user_id,full_name, dob, phone_number, blood_group, photo, birth_proof, aadhaar_card, eye_cert,signature,package_id, application_status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?,?, 'Pending')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssss", $userId, $name, $dob, $ph, $bloodGroup, $photopath, $birthproof, $adhaarcertificate, $eyecertificate, $signature, $package);

        if ($stmt->execute()) {
            $flag = 0;
            $_SESSION['id']=$userId;
            
            echo '<script>
        var confirmed = confirm("Documents added successfully. Click OK to continue.");
        if (confirmed) {
            window.location.href = "payment.php?price=' . $price . '";
        }
    </script>';
            $flag = 0;
        } else {
            echo "Error inserting data: " . $stmt->error;
        }
    } else {
        // Handle error: Not all files were uploaded successfully
        echo "Error: Some files were not uploaded successfully.";
    }
    $learner_id = fetchlearner_id($conn, 'tbl_learners_details', $userId, $package);
    $stmt->close();
}
$conn->close();
// $sql = "INSERT INTO form_data (gname, gmail, cname, blood_group, package, photo, aadhaar, birth_proof, eye_cert) 
//         VALUES ('$gname', '$gmail', '$cname', '$bloodGroup', '$package', '$photoPath', '$aadhaarPath', '$birthProofPath', '$eyeCertPath')";
// $conn->query($sql);

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

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>




    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->





    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0" style="background-color: #303134;margin-top: -2px;">
        <a href="index.php" class="navbar-brand d-flex align-items-center border-end px-4  px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-car text-primary me-2 "></i>Drivin</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link active">Home</a>
                <a href="index.php#about" class="nav-item nav-link">About</a>
                <a href="index.php#course" class="nav-item nav-link">Courses</a>
                <a href="newlicense.php?flag=<?= 1 ?>" class="nav-item nav-link">New License</a>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Other Services</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="Otherservices.php" class="dropdown-item">License Renewal</a>
                        <a href="Otherservices.php" class="dropdown-item">Change Of Address</a>

                    </div>
                </div>
                <a href="index.php#trainers" class="nav-item nav-link">Our Trainers</a>
                <a href="index.php#feedback" class="nav-item nav-link">Feedbacks</a>
                <!--<div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="feature.html" class="dropdown-item">Features</a>
                        <a href="appointment.html" class="dropdown-item">Appointment</a>
                        <a href="team.html" class="dropdown-item">Our Team</a>
                        <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                        <a href="404.html" class="dropdown-item">404 Page</a>
                    </div>
                </div>-->
                <a href="appointment.php" class="nav-item nav-link">Book Class Slot</a>
            </div>
            <?php if (isset($_SESSION['name'])) { ?>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><img src="img/icons8-person-30.png" class="btn-primary img-fluid icon" alt=""></a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="user-profile.php" class="dropdown-item"><?php echo $_SESSION['name']; ?></a>
                        <a href="signout.php" class="dropdown-item">Signout</a>

                    </div>

                </div>
            <?php } else {
            ?>

                <a href="signup.php" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block">login/signup<i class="fa fa-arrow-right ms-3"></i></a>
            <?php } ?>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid page-header py-6 my-6 mt-0 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center">
            <h1 class="display-4 text-white text-uppercase animated slideInDown mb-4">Apply new License</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a class="text-white text-uppercase" href="#">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-white text-uppercase" href="#">new License</a></li>
                    <li class="text-primary active text-uppercase" aria-current="page"></li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->
    <?php
    if (isset($_GET['flag']) && $_GET['flag'] == 1) {
    ?>


        <!-- About Start -->
        <div class="container-xxl py-6">
            <div class="container">

                <div class="row g-5">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="position-relative overflow-hidden ps-5 pt-5 h-100" style="min-height: 400px;">
                            <img class="position-absolute w-100 h-100" src="img/about-1.jpg" alt="" style="object-fit: cover;">
                            <img class="position-absolute top-0 start-0 bg-white pe-3 pb-3" src="img/about-2.jpg" alt="" style="width: 200px; height: 200px;">
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="h-100">
                            <div class="bg-primary text-center p-5">
                                <h1 class="mb-4 text-uppercase">new License</h1>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">

                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control border-0" id="gname" name="name" placeholder="Gurdian Name" required>
                                                <label for="gname" ass="text-uppercase">Your Name</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="date" class="form-control border-0" id="gmail" name="dob" placeholder="Gurdian Email" required>
                                                <label for="gmail" class="text-uppercase">date of birth</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="number" class="form-control border-0" id="cname" name="ph" placeholder="Child Name" required>



                                                <label for="cname" class="text-uppercase">phone number</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <select class="form-control border-0" aria-label="Default select example" name="blood" required>
                                                    <option value="1">A+</option>
                                                    <option value="2">A-</option>
                                                    <option value="3">B+</option>
                                                    <option value="4">B-</option>
                                                    <option value="5">O+</option>
                                                    <option value="6">O-</option>
                                                    <option value="7">AB+</option>
                                                    <option value="8">AB-</option>
                                                </select>
                                                <label for="cname" class="text-uppercase">blood group</label>
                                            </div>

                                        </div>
                                        <div class="form-floating">
                                            <select class="form-control border-0" name="pack" aria-label="Default select example" required>

                                                <?php

                                                foreach ($packages as $package) {
                                                    echo '<option value="' . $package['package_id'] . '">' . $package['package_name'] . '</option>';
                                                }
                                                ?>

                                            </select>
                                            <label for="cname" class="text-uppercase">COURSE TYPES</label>




                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="file" class="form-control" name="photo" accept="image/*" required>
                                                <label for="message">Your photo</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="file" class="form-control" name="birthproof" accept="image/*" required>
                                                <label for="message">Birth proof</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="file" class="form-control" name="adhaarcertificate" accept="image/*" required>
                                                <label for="message">Aadhaar</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="file" class="form-control" name="eyecertificate" accept="image/*" required>
                                                <label for="message">Eye certificate</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="file" class="form-control" name="signature" accept="image/*" required>
                                                <label for="message">Signature</label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <input class="btn btn-dark w-100 py-3" type="submit" name="new_lic" value="Submit">

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <!--<div class="col-lg-8 my-6 mb-0 wow fadeInUp" data-wow-delay="0.1s">
                            
                        <h6 class="text-primary text-uppercase mb-2">About Us</h6>
                        <h1 class="display-6 mb-4">We Help Students To Pass Test & Get A License On The First Try</h1>
                        <p>Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet</p>
                        <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet</p>
                        <div class="row g-2 mb-4 pb-2">
                            <div class="col-sm-6">
                                <i class="fa fa-check text-primary me-2"></i>Fully Licensed
                            </div>
                            <div class="col-sm-6">
                                <i class="fa fa-check text-primary me-2"></i>Online Tracking
                            </div>
                            <div class="col-sm-6">
                                <i class="fa fa-check text-primary me-2"></i>Afordable Fee
                            </div>
                            <div class="col-sm-6">
                                <i class="fa fa-check text-primary me-2"></i>Best Trainers
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <a class="btn btn-primary py-3 px-5" href="">Read More</a>
                            </div>
                            <div class="col-sm-6">
                                <a class="d-inline-flex align-items-center btn btn-outline-primary border-2 p-2" href="tel:+0123456789">
                                    <span class="flex-shrink-0 btn-square bg-primary">
                                        <i class="fa fa-phone-alt text-white"></i>
                                    </span>
                                    <span class="px-3">+012 345 6789</span>
                                </a>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    <?php
    } else {
    ?>
        <h1>Choose a Date for the Learners Test</h1><br>
        <h1> You can choose 3 different Preferable Date</h1><br>

        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
            <label for="test_date">Select Date 1:</label>
            <input type="date" id="test_date" name="test_date1" required min="<?php echo date('Y-m-d'); ?>">
            <label for="test_date">Select Date 2:</label>
            <input type="date" id="test_date2" name="test_date2" required min="<?php echo date('Y-m-d'); ?>">
            <label for="test_date">Select Date 3:</label>
            <input type="date" id="test_date3" name="test_date3" required min="<?php echo date('Y-m-d'); ?>">
            <button type="submit" name="ADD_DATE">Submit</button>
        </form>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const dateInput = document.getElementById("test_date");

                dateInput.addEventListener("input", function() {
                    const selectedDate = new Date(this.value);
                    const day = selectedDate.getDay();

                    // Disable Saturdays (6) and Sundays (0)
                    if (day === 6 || day === 0) {
                        alert("Learners' tests are not conducted on weekends. Please choose a different date.");
                        this.value = "";
                    }

                    // Disable Wednesdays (3)
                    if (day === 3) {
                        alert("Learners' tests are not conducted on Wednesdays. Please choose a different date.");
                        this.value = "";
                    }
                });
            });
            document.addEventListener("DOMContentLoaded", function() {
                const dateInput = document.getElementById("test_date2");

                dateInput.addEventListener("input", function() {
                    const selectedDate = new Date(this.value);
                    const day = selectedDate.getDay();

                    // Disable Saturdays (6) and Sundays (0)
                    if (day === 6 || day === 0) {
                        alert("Learners' tests are not conducted on weekends. Please choose a different date.");
                        this.value = "";
                    }

                    // Disable Wednesdays (3)
                    if (day === 3) {
                        alert("Learners' tests are not conducted on Wednesdays. Please choose a different date.");
                        this.value = "";
                    }
                });
            });
            document.addEventListener("DOMContentLoaded", function() {
                const dateInput = document.getElementById("test_date3");

                dateInput.addEventListener("input", function() {
                    const selectedDate = new Date(this.value);
                    const day = selectedDate.getDay();

                    // Disable Saturdays (6) and Sundays (0)
                    if (day === 6 || day === 0) {
                        alert("Learners' tests are not conducted on weekends. Please choose a different date.");
                        this.value = "";
                    }

                    // Disable Wednesdays (3)
                    if (day === 3) {
                        alert("Learners' tests are not conducted on Wednesdays. Please choose a different date.");
                        this.value = "";
                    }
                });
            });
        </script>

    <?php
    }
    ?>
    </div>
    <!-- About End -->


    <!-- Team Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
                <h6 class="text-primary text-uppercase mb-2">Meet The Team</h6>
                <h1 class="display-6 mb-4">We Have Great Experience Of Driving</h1>
            </div>
            <div class="row g-0 team-items">
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item position-relative">
                        <div class="position-relative">
                            <img class="img-fluid" src="img/team-1.jpg" alt="">
                            <div class="team-social text-center">
                                <a class="btn btn-square btn-outline-primary border-2 m-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-outline-primary border-2 m-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-outline-primary border-2 m-1" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="bg-light text-center p-4">
                            <h5 class="mt-2">Full Name</h5>
                            <span>Trainer</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="team-item position-relative">
                        <div class="position-relative">
                            <img class="img-fluid" src="img/team-2.jpg" alt="">
                            <div class="team-social text-center">
                                <a class="btn btn-square btn-outline-primary border-2 m-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-outline-primary border-2 m-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-outline-primary border-2 m-1" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="bg-light text-center p-4">
                            <h5 class="mt-2">Full Name</h5>
                            <span>Trainer</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="team-item position-relative">
                        <div class="position-relative">
                            <img class="img-fluid" src="img/team-3.jpg" alt="">
                            <div class="team-social text-center">
                                <a class="btn btn-square btn-outline-primary border-2 m-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-outline-primary border-2 m-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-outline-primary border-2 m-1" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="bg-light text-center p-4">
                            <h5 class="mt-2">Full Name</h5>
                            <span>Trainer</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="team-item position-relative">
                        <div class="position-relative">
                            <img class="img-fluid" src="img/team-4.jpg" alt="">
                            <div class="team-social text-center">
                                <a class="btn btn-square btn-outline-primary border-2 m-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-outline-primary border-2 m-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-outline-primary border-2 m-1" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="bg-light text-center p-4">
                            <h5 class="mt-2">Full Name</h5>
                            <span>Trainer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team End -->


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer my-6 mb-0 py-6 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4">Get In Touch</h4>
                    <h2 class="text-primary mb-4"><i class="fa fa-car text-white me-2"></i>Drivin</h2>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Quick Links</h4>
                    <a class="btn btn-link" href="">About Us</a>
                    <a class="btn btn-link" href="">Contact Us</a>
                    <a class="btn btn-link" href="">Our Services</a>
                    <a class="btn btn-link" href="">Terms & Condition</a>
                    <a class="btn btn-link" href="">Support</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Popular Links</h4>
                    <a class="btn btn-link" href="">About Us</a>
                    <a class="btn btn-link" href="">Contact Us</a>
                    <a class="btn btn-link" href="">Our Services</a>
                    <a class="btn btn-link" href="">Terms & Condition</a>
                    <a class="btn btn-link" href="">Support</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Newsletter</h4>
                    <form action="">
                        <div class="input-group">
                            <input type="text" class="form-control p-3 border-0" placeholder="Your Email Address">
                            <button class="btn btn-primary">Sign Up</button>
                        </div>
                    </form>
                    <h6 class="text-white mt-4 mb-3">Follow Us</h6>
                    <div class="d-flex pt-2">
                        <a class="btn btn-square btn-outline-light me-1" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-square btn-outline-light me-1" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-outline-light me-1" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-square btn-outline-light me-0" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Copyright Start -->
    <div class="container-fluid copyright text-light py-4 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a href="#">Your Site Name</a>, All Right Reserved.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                    Designed By <a href="https://htmlcodex.com">HTML Codex</a>
                    <br>Distributed By: <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script>
        var cnameInput = document.getElementById('cname');

        cnameInput.addEventListener('input', function() {
            var maxLength = 10;
            if (this.value.length > maxLength) {
                this.value = this.value.slice(0, maxLength); // Truncate the input to maxLength characters
            }
        });
    </script>
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