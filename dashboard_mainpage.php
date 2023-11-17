<?php
include('connection.php');
$today = date("Y-m-d");

function countAppointmentsBookedToday($conn, $table_name)
{
    // Get the current date in the format YYYY-MM-DD
    $today = date("Y-m-d");

    // Query to count the number of appointments booked today
    $sql = "SELECT COUNT(*) AS appointment_count FROM $table_name WHERE created_at = '$today'";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $appointmentCount = $row['appointment_count'];
        return $appointmentCount;
    } else {
        return 0; // No appointments booked today
    }
}

function countAppointmentsBooked($conn, $table_name, $cat, $date_column)
{
    // Get the current date in the format YYYY-MM-DD
    $today = date("Y-m-d");

    // Escape and quote the category to prevent SQL injection
    $cat = $conn->real_escape_string($cat);

    // Query to count the number of appointments booked today based on the specified date_column and category
    $sql = "SELECT COUNT(*) AS appointment_count FROM $table_name WHERE created_at = '$today' AND tbl_category = '$cat'";
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $appointmentCount = $row['appointment_count'];
        return $appointmentCount;
    } else {
        return 0; // No appointments booked today
    }
}

$appointmentCountToday = countAppointmentsBookedToday($conn, "tbl_appointments");

// Define variables for appointment counts for different categories
$changeOfAddressCount = countAppointmentsBooked($conn, 'tbl_services', 'Change Of Address', 'appointment_date');
$licenceRenewalCount = countAppointmentsBooked($conn, 'tbl_services', 'licence renewal', 'appointment_date');

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
    <?php
    include('admin_menu.php');
    ?>
    <div class="d-flex justify-content-center" style="margin-top:38px;">
        <div class="row" style="width: 78%;">
            <div class="col" style="margin:10px;margin-left:30px;background-color: aqua;justify-content: center;display: flex;">
            <center><p style="margin-left: 80px;font-size: smaller;">Today's New License Appointment
                   <br><?php echo $appointmentCountToday; ?></p></center><br><br><br><br>
                    <p></p>
                <img src="img/drivers-license 2.png" alt="ppp" style=" width: 40%;margin-top:70px;">
               
                   
                
            </div>
            <div class="col" style="margin:10px;margin-left:30px;background-color: aqua;justify-content: center;display: flex;">
                <img src="img/driver-license 3.png" alt="ppp" style=" width: 40%;margin-top:70px;">
                <?php echo $changeOfAddressCount; ?>
            </div>
            <div class="col" style="margin:10px;margin-left:30px;background-color: aqua;justify-content: center;display: flex;">
                <img src="img/driver-license 1.png" alt="ppp" style=" width: 40%;margin-top:70px;">
                <?php echo $licenceRenewalCount; ?>

            </div>
        </div>
    </div>
</body>

</html>