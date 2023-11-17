<?php
session_start();
include('connection.php');
$id='';
function userId($username, $email, $password)
{
    global $conn;
    $sql = "SELECT user_id FROM tbl_users WHERE user_name = '$username' AND user_email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        $id = $row['user_id'];
    }
    return $id;
}

if ((isset($_POST['book_now']))) {

    // if (($_POST['selectedTimeSlot']) !== 'undefined') { //

    // Send the response back to the JavaScript

    // Retrieve data from the POST request
    //$name = $_POST['name'];
    // $email = $_POST['email'];
    // $phoneNumber = $_POST['phoneNumber'];
    $course = $_POST['course'];
    $instructor = $_POST['instructor'];
    $section = $_POST['section'];
    $appointmentDate = $_POST['appointmentDate'];
    $selectedTimeSlot = $_POST['selectedTimeSlot'];

    //echo $section;

    //echo $selectedTimeSlot . " " . $appointmentDate;
    $username = $_SESSION['name'];
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];

    $userId = userId($username, $email, $password);

    $sql = "SELECT * FROM tbl_learners_details WHERE user_id = ? AND package_id=? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $course);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If there are rows in the result, fetch and use the data here
        while ($row = $result->fetch_assoc()) {
            // Access data from the row
            $learner_id = $row['learner_id'];
            $learner_name = $row['full_name'];
            $appo_sql = "SELECT * FROM tbl_appointments WHERE learner_id =? AND classneed_date=?";
            $stmt3 = $conn->prepare($appo_sql);
            $stmt3->bind_param("is", $learner_id, $appointmentDate);
            $stmt3->execute();
            $result3 = $stmt3->get_result();
            if ($result3->num_rows > 0) {
                echo "" . $learner_name . " you have already booked on the same date " . $appointmentDate;
                $stmt3->close();
            } else {
                //$email = $_SESSION['email'];
                $sql = "INSERT INTO tbl_appointments (learner_id,instructor_id,user_email, service_id,section,appointment_time, status, classneed_date, created_at)
                            VALUES (?, ?,?, ?,?,?, 'pending', ?, NOW())";
                $stmt2 = $conn->prepare($sql);
                $stmt2->bind_param("iisssss", $learner_id, $instructor, $email, $course, $section, $selectedTimeSlot, $appointmentDate);

                // You may need to determine the patient_id based on the email or other criteria.
                // For this example, I'm assuming you have a patients table with an email column.



                $stmt2->execute();
                if($stmt2->errno) {
                    echo "Error: " . $stmt2->error;
                }
                

                //$result2 = $stmt2->get_result();


                // Echo the success message directly
                echo "Appointment booked successfully!";

                //header("location: user-appointment.php");
                unset($_POST['selectedTimeSlot']);
                // Close the database connection
                $stmt->close();
                $stmt2->close();
                $conn->close();
            }
        }
    } else {
        echo "first select the course and then book ";
    }
}
?>