<?php
session_start();
include('connection.php');
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

$packages = fetchdata($conn, 'tbl_package');
$instructors = fetchdata($conn, 'tbl_instructors');
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
            <h1 class="display-4 text-white animated slideInDown mb-4">Appointment</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a class="text-white" href="#">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="#">Pages</a></li>
                    <li class="breadcrumb-item text-primary active" aria-current="page">Appointment</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Appointment Start -->
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
                    <h6 class="text-primary text-uppercase mb-2">Appointment</h6>
                    <h1 class="display-6 mb-4">Make An Appointment To Pass Test & Get A License On The First Try</h1>
                    <form method="post">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <select class="form-control border-0" name="course" aria-label="Default select example">

                                        <?php

                                        foreach ($packages as $package) {
                                            echo '<option value="' . $package['package_id'] . '">' . $package['package_name'] . '</option>';
                                        }
                                        ?>

                                    </select>

                                    <label for="gname">Course Type</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <select class="form-control border-0" name="instructor" aria-label="Default select example">

                                        <?php

                                        foreach ($instructors as $instructor) {
                                            echo '<option value="' . $instructor['instructor_id'] . '">' . $instructor['instructor_name'] . '</option>';
                                        }
                                        ?>

                                    </select>
                                    <label for="gmail">Instructors</label>
                                </div>
                            </div>
                            <div class="row  t-section">
                                <div class="col-12 col-sm-6  " style="color:#fff">
                                    <input type="radio" name="section" id="morning" value="morning" onchange="fetchAvailableTimeSlots()" required>
                                    <label for="morning">Morning 09:00 AM - 12:00 PM</label>
                                </div>
                                <div class="col-12 col-sm-6 " style="color:#fff">
                                    <input type="radio" name="section" id="Evening" value="evening" onchange="fetchAvailableTimeSlots()" required>
                                    <label for="Evening">Evening 04:00 PM - 06:00 PM</label>
                                </div>

                            </div>
                            <div class="col-12 col-sm-6 l2">
                                <label for="date" class="text-uppercase">Choose needed date :</label>
                                <div>
                                    <input type="date" id="appointmentDate" name="appointmentDate" style="margin-top:3px;" class="form-control border-0 bg-light px-4" min="" required onchange="fetchAvailableTimeSlots()">
                                </div>
                                <div id="timeSlotsContainer" class="row" style=" width: 141%;">

                                </div>


                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control border-0 bg-light" id="cname" placeholder="Child Name">
                                    <label for="cname">Courses Type</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control border-0 bg-light" id="cage" placeholder="Child Age">
                                    <label for="cage">Car Type</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control border-0 bg-light" placeholder="Leave a message here" id="message" style="height: 150px"></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>-->
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" name="book_now" type="submit" onclick="submitForm()">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Appointment End -->


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
        // Function to fetch and update time slots based on selected options
        function fetchAvailableTimeSlots() {
            // Gather selected values
            var course = $("select[name='course']").val();
            var instructor = $("select[name='instructor']").val();
            var section = $("input[name='section']:checked").val();
            var appointmentDate = $("#appointmentDate").val();

            // Send AJAX request to fetch data
            $.ajax({
                type: "POST",
                url: "update_status.php", // Replace with your PHP script URL
                data: {
                    course: course,
                    instructor: instructor,
                    section: section,
                    appointmentDate: appointmentDate
                },

                success: function(response) {
                    // Handle the response from PHP here
                    // You can update the #timeSlotsContainer with the received data
                    $("#timeSlotsContainer").html(response);
                },
                error: function() {
                    // Handle errors here if needed
                    console.error("An error occurred during the AJAX request.");
                }
            });
        }

        // Attach onchange event listeners to the relevant form elements
        $("select[name='course']").change(fetchAvailableTimeSlots);
        $("select[name='instructor']").change(fetchAvailableTimeSlots);
        $("input[name='section']").change(fetchAvailableTimeSlots);
        $("#appointmentDate").change(fetchAvailableTimeSlots);

        // Initial fetch when the page loads
        fetchAvailableTimeSlots();
        // JavaScript function to handle form submission
        function submitForm() {
            // Get form data
            var course = $("select[name='course']").val();
            var instructor = $("select[name='instructor']").val();
            var section = $("input[name='section']:checked").val();
            var appointmentDate = $("#appointmentDate").val();
            var selectedTimeSlot = $('input[name="time"]:checked').val();
            var Submit = $('input[name="book_now"]').val();

            // Create a FormData object to send data as a POST request
            var formData = new FormData();
            formData.append('course', course);
            formData.append('instructor', instructor);
            formData.append('section', section);
            formData.append('appointmentDate', appointmentDate);
            formData.append('selectedTimeSlot', selectedTimeSlot);
            formData.append('book_now', Submit); // Assuming 'book_now' is your submit button name

            var xhr = new XMLHttpRequest();

            // Define the POST request details
            xhr.open('POST', 'update_appo.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Handle the response from update_status.php here
                    var response = xhr.responseText;

                    // Display the response message in an alert
                    alert(response);

                    // Clear all form inputs and reset them to their default values
                    $("form")[0].reset();

                    // Clear the #timeSlotsContainer if needed
                    $("#timeSlotsContainer").html("");
                }
            };

            // Send the POST request
            xhr.send(formData);
        }

        // Initial fetch when the page loads
        fetchAvailableTimeSlots();
    </script>
    
    <script>
        // Get the current date in yyyy-mm-dd format    
        const currentDate = new Date().toISOString().split('T')[0];
        // Set the minimum date for the input field to the current date
        document.getElementById("appointmentDate").min = currentDate;
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