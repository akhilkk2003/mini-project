<?php
session_start();
include('connection.php');

if (isset($_SESSION['name']) && $_SESSION['name'] == "admin") {
    header('Location: admin_menu.php');
}

// Initialize $id variable
$id = null;
$f=0;
$flag=0;
// Fetch data from the database table
$email = $_SESSION['email'];
$sql = "SELECT * FROM tbl_users WHERE user_email = '$email'";
$result = $conn->query($sql);

if ($result === false) {
    echo "Error fetching user details: " . $conn->error;
} elseif ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['user_id'];
    }
}

// Check if $id is not null before proceeding
if ($id !== null) {
    $sql = "SELECT * FROM tbl_learners_details WHERE user_id = '$id'";
    $result = $conn->query($sql);
    $details = [];

    if ($result === false) {
        $flag=0;
    } elseif ($result->num_rows > 0) {
        $flag=1;
        while ($row = $result->fetch_assoc()) {
            $details[] = $row;
        }
        $st = 'completed';
    }
}

$sql1 = "SELECT * FROM tbl_services WHERE user_id = '$id'";
    $result1 = $conn->query($sql1);
    $ds = [];

    if ($result1 === false) {
        $f=0;
    } elseif ($result1->num_rows > 0) {
        $f=1;
        while ($row2 = $result1->fetch_assoc()) {
            $ds[] = $row2;
        }
        $st = 'completed';
    }


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Drivin</title>
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
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0" style="background-color: #303134;margin-top: -2px;">
        <a href="index.html" class="navbar-brand d-flex align-items-center border-end px-4  px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-car text-primary me-2 "></i>Drivin</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link active">Home</a>
                <a href="#about" class="nav-item nav-link">About</a>
                <a href="#course" class="nav-item nav-link">Courses</a>
                <a href="newlicense.php?flag=<?= 1 ?>" class="nav-item nav-link">New License</a>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Other Services</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="Otherservices.php" class="dropdown-item">License Renewal</a>
                        <a href="Otherservices.php" class="dropdown-item">Change Of Address</a>

                    </div>
                </div>
                <a href="#trainers" class="nav-item nav-link">Our Trainers</a>
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
    <?php  if($flag==1){?>
    <div class="name">
        <marquee behavior="alternate" direction="right" scrollamount="6">
            <h2>Welcome Back <?php echo $_SESSION['name'] ?></h2>
        </marquee>

    </div>
    <div>
        <table class="col-* table table-success table-striped shadow-lg t-hover">
            <thead>
           <tr>
             
                    <th>Transaction Name</th>
                    <th>Action Name</th>
                    <th>Status</th>
                  




                </tr>
            </thead>
            <tbody> 
                <div class="text-center">
                    <h2>Applicant Status</h2>
                </div>
                <?php foreach ($details as $index => $detail) : ?>
                    <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
            <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                    <td>Learner and Driving Licences</td>
                    <td>FILL APPLICATION DETAILS LL</td>
                    <td><?php echo $st;?></td>
                  

                </tr>
                <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                    <td>Learner and Driving Licences</td>
                    <td>FEE PAYMENT</td>
                    <td><?php echo $detail['payment_status'];?></td>
                    

                </tr>
                <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                    <td>Learner and Driving Licences</td>
                    <td>LEARNERS TEST SLOT BOOK</td>
                    <td>Date on-<?php echo $detail['choosed_date'];?></td>

                </tr>
                <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                    <td>Learner and Driving Licences</td>
                    <td>LEARNER'S TEST RESULT</td>
                    <td><?php echo $detail['learners_test_status'];?></td>
                   

                </tr> 
                <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                    <td>Learner and Driving Licences</td>
                    <td>DRIVING LICENSE TEST</td>
                    <td>Date on-<?php echo $detail['dl_test'];?></td>
                   

                </tr>
                 <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                    <td>Learner and Driving Licences</td>
                    <td>DRIVING LICENSE RESULT</td>
                    <td><?php echo $detail['driving_test_status'];?></td>
                   

                </tr> 
            </tbody>
                

                    <?php endforeach; ?>
        </table>
    </div>
    <?php }
    if($f==1){
    ?>
    <div class="text-center">
        <h2>Change Of Address And Licence Renewal</h2>
    </div>
    <table class="col-* table table-success table-striped shadow-lg t-hover">
            <thead>
           <tr>
             
                    <th>Services</th>
                    <th>payment</th>
                    <th>Status</th>




                </tr>
            </thead>
            <tbody> <?php foreach ($ds as $index => $d) : ?>
                    <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
            <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                    <td><?php echo $d['tbl_category'];?></td>
                    <td><?php echo $d['payment_status'];?></td>
                    <td><?php echo $d['status'];?></td>

                </tr>
                
            </tbody>
                

                    <?php endforeach; ?>
        </table>
    
    <?php } ?>
    


</body>

</html>