<?php
session_start();
include('connection.php');
function userid($conn, $email)
{
    $qry = "SELECT user_id FROM tbl_users WHERE user_email = '$email'";
    $result6 = $conn->query($qry);
    $data = [];
    if ($result6->num_rows > 0) {
        // Loop through the  results and store each row in the $data array
        while ($row = $result6->fetch_assoc()) {
            $data = $row['user_id'];
        }
    }
    return $data;
}
if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['service']))) {
    $licenseNumber = $_POST["license_number"];
    $dateOfBirth = $_POST["date_of_birth"];
    $category = $_POST["category"];
    $newAddress = $_POST["new_address"];
    $email = $_SESSION["email"];
    $id = userid($conn, $email);
    // Step 4: Sanitize and validate data (you should implement your own validation logic here)

    // Step 5: Insert data into the database
    if ($category == "licence_renewal") {
        $category = "licence renewal";
        $sql = "INSERT INTO tbl_services (user_id,tbl_license_no, tbl_dob, tbl_category, tbl_new_address,status) VALUES (?,?, ?, ?,'N/A',?)";


        $stmt = $conn->prepare($sql);
        $status = 'pending';
        $stmt->bind_param("sssss", $id, $licenseNumber, $dateOfBirth, $category, $status);
        $oprice = 450;
        if ($stmt->execute()) {
            echo '<script>
        var confirmed = confirm("Details Updated Successfully ");
        if (confirmed) {
            window.location.href = "payment.php?oprice=' . $oprice . '";
        }
    </script>';
        } else {
            echo "Error: " . $stmt->error;
        }
    } else if ($category == "change_address") {
        $category = "Change Of Address";
        $sql = "INSERT INTO tbl_services (user_id,tbl_license_no, tbl_dob, tbl_category,tbl_new_address,status) VALUES (?,?, ?, ?,?,?)";


        $stmt = $conn->prepare($sql);
        $status = 'pending';
        $stmt->bind_param("ssssss", $id, $licenseNumber, $dateOfBirth, $category, $newAddress, $status);
        $oprice = 600;

        if ($stmt->execute()) {
            echo '<script>
            var confirmed = confirm("Details Updated Successfully");
            if (confirmed) {
                window.location.href = "payment.php?oprice=' . $oprice . '";
            }
        </script>';
        } else {
            echo "Error: " . $stmt->error;
        }
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

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start 
    <div class="container-fluid bg-dark text-light p-0">
        <div class="row gx-0 d-none d-lg-flex">
            <div class="col-lg-7 px-5 text-start">
                <div class="h-100 d-inline-flex align-items-center me-4">
                    <small class="fa fa-map-marker-alt text-primary me-2"></small>
                    <small>123 Street, New York, USA</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center">
                    <small class="far fa-clock text-primary me-2"></small>
                    <small>Mon - Fri : 09.00 AM - 09.00 PM</small>
                </div>
            </div>
            <div class="col-lg-5 px-5 text-end">
                <div class="h-100 d-inline-flex align-items-center me-4">
                    <small class="fa fa-phone-alt text-primary me-2"></small>
                    <small>+012 345 6789</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center mx-n2">
                    <a class="btn btn-square btn-link rounded-0 border-0 border-end border-secondary" href=""><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-square btn-link rounded-0 border-0 border-end border-secondary" href=""><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-square btn-link rounded-0 border-0 border-end border-secondary" href=""><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-square btn-link rounded-0" href=""><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
    Topbar End -->
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0" style="background-color: #303134;margin-top: -2px;">
        <a href="index.html" class="navbar-brand d-flex align-items-center border-end px-4  px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-car text-primary me-2 "></i>Driving</h2>
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
            <h1 class="display-4 text-white animated slideInDown mb-4">Other Services</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a class="text-white" href="#">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="#">Other Services</a></li>
                    <!-- <li class="breadcrumb-item text-primary active" aria-current="page">Contact</li>-->
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Contact Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 450px;" style=" animation-delay: 0.3s;">
                    <div class="position-relative h-100">
                        <img src="img/img-1.avif" alt="something went wrong">
                        <!-- <iframe class="position-relative w-100 h-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3001156.4288297426!2d-78.01371936852176!3d42.72876761954724!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4ccc4bf0f123a5a9%3A0xddcfc6c1de189567!2sNew%20York%2C%20USA!5e0!3m2!1sen!2sbd!4v1603794290143!5m2!1sen!2sbd"
                        frameborder="0" style="min-height: 450px; border:0;" allowfullscreen="" aria-hidden="false"
                        tabindex="0"></iframe>-->
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <h6 class="text-primary text-uppercase mb-2">Please fill The Below Form For</h6>
                    <h1 class="display-6 mb-4"> License Renewal or Change Of Address</h1>
                    <p class="mb-4"> <a href="https://htmlcodex.com/contact-form"></a>.</p>
                    <!--<form action="<?php $_SERVER["PHP_SELF"]; ?>" method="post">
                        <div class="row g-3">
                             <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control border-0 bg-light" id="name" placeholder="Your Name">
                                    <label for="name"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control border-0 bg-light" id="email" placeholder="Your Email">
                                    <label for="email"></label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control border-0 bg-light" id="licenseNumber" name="licenseNumber" placeholder="License Number" required>
                                    <label for="licenseNumber">Enter License Number</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="date" class="form-control border-0 bg-light" id="dob" name="dob" placeholder="Date of Birth" required>
                                    <label for="dob">Enter Date of Birth</label>
                                    <div id="error-message" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="mb-3 service-form-control">
                                <label class="form-label service-text">Category</label><br>
                                <input type="radio" id="" name="category" value="lic" checked>
                                <label for="" class="service-text">Licence Renewal</label>
                                <input type="radio" id="" name="category" value="change_address" style="margin-left: 30px;">
                                <label for="" class="service-text">Change of Address</label><br>
                            </div>

                            <div class="form-floating">

                                <input type="text" name="new_address" id="newAddress" class="form-control border-0 bg-light" required>
                                <label for="subject" class="service-text" id="new_Address">New Address</label>

                            </div>

                            <div class="col-12">
                                <input class="btn btn-primary py-3 px-5 text-uppercase" type="submit" name="proceed" value="proceed">
                            </div>
                        </div>-->
                    <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                        <div class="mb-3 service-form-control">
                            <label for="exampleInputEmail1" class="form-label  service-text">License Number</label>

                            <input type="text" name="license_number" class="form-control border-0 bg-light" id="exampleInputEmail1" aria-describedby="emailHelp" required>


                        </div>
                        <div class="mb-3 service-form-control">
                            <label for="exampleInputEmail1" class="form-label service-text">Date of birth</label>
                            <input type="date" name="date_of_birth" class="form-control border-0 bg-light" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                        </div>

                        <div class="mb-3 service-form-control">
                        </div>
                        <div class="mb-3 service-form-control">
                            <label class="form-label service-text">Category</label><br>
                            <input type="radio" id="licence_renewal" name="category" value="licence_renewal" checked required>
                            <label for="licence_renewal" class="service-text">Licence Renewal</label><br>
                            <input type="radio" id="change_address" name="category" value="change_address" required>
                            <label for="change_address" class="service-text">Change of Address</label><br>
                        </div>

                        <div class="mb-3 service-form-control">
                            <label for="" class="service-text" id="new_Address">New Address</label>
                            <input type="text" name="new_address" id="newAddress" class="form-control border-0 bg-light">
                        </div>
                        <input type="submit" name="service" class="btn btn-primary py-3 px-5 text-uppercase"value="Submit">
                    </form>


                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer my-6 mb-0 py-6 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-5">
                <!-- <div class="col-lg-3 col-md-6">
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
                    </div> -->
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
                    &copy; <a href="#">KK</a>, All Right Reserved.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                    Designed By <a href="">KK.com</a>
                    <br>Distributed By: <a href="h" target="_blank">Akhil KK</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script>
        var licenseNumberInput = document.getElementById('licenseNumber');
        var dobInput = document.getElementById('dob');
        var errorMessageElement = document.getElementById('error-message');

        dobInput.addEventListener('input', function(event) {
            if (event.target.id === 'dob') {
                var inputValue = this.value;
                if (inputValue.trim() === '') {
                    errorMessageElement.textContent = ''; // Clear error message if input is empty
                } else {
                    var isValidDate = Date.parse(inputValue);
                    if (isNaN(isValidDate)) {
                        errorMessageElement.textContent = 'Invalid date format. Please enter a valid date.';
                    } else {
                        errorMessageElement.textContent = '';
                    }
                }
            }
        });

        licenseNumberInput.addEventListener('input', function(event) {
            if (event.target.id === 'licenseNumber') {
                // You can add validation logic for license number here if needed
                errorMessageElement.textContent = ''; // Clear error message for license number
            }
        });
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