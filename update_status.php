<?php
session_start();
include('connection.php');
$timeSlot = "";
function timeFetch($s, $e, $course)
{

    // Define the start and end times
    $start = strtotime($s);
    $end = strtotime($e);

    // Initialize an empty array to store the time intervals
    $timeIntervals = [];

    // Loop to generate time intervals
    while ($start < $end) {

        // Calculate the end time of the interval (15 minutes later)
        if ($course === "1") {
            $intervalEnd = strtotime('+30 minutes', $start);
        } else {
            $intervalEnd = strtotime('+15 minutes', $start);
        }

        // Format the times in AM/PM format
        $formattedStart = date('g:iA', $start);
        $formattedEnd = date('g:iA', $intervalEnd);

        // Create the interval string and add it to the array
        $timeIntervalString = $formattedStart . '-' . $formattedEnd;
        $timeIntervals[] = $timeIntervalString;

        // Move the start time to the next interval
        $start = $intervalEnd;
    }

    // Join the time intervals into a comma-separated string
    $timeIntervalsString = implode(', ', $timeIntervals);
    return $timeIntervalsString;
}


if (isset($_POST['section'])) {
    if (isset($_POST['section'])  && isset($_POST['appointmentDate'])) {
        $course = $_POST['course'];
        $instructor = $_POST['instructor'];
        $section = $_POST['section'];
        $appointmentDate = $_POST['appointmentDate'];
        $timestamp = strtotime($appointmentDate);

        if ($timestamp === false) {
            echo "";
        } else {
            // Use the date() function to get the day of the week (0 = Sunday, 1 = Monday, ...)
            $dayOfWeek = date("w", $timestamp);

            // Define an array to map day of the week numbers to their names
            $daysOfWeek = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

            // Get the day name based on the day of the week
            $dayName = $daysOfWeek[$dayOfWeek];
            if ($dayName == "Sunday") {
                $dayNum = "0";
            } elseif ($dayName == "Monday") {
                $dayNum = "1";
            } elseif ($dayName == "Tuesday") {
                $dayNum = "2";
            } elseif ($dayName == "Wednesday") {
                $dayNum = "3";
            } elseif ($dayName == "Thursday") {
                $dayNum = "4";
            } elseif ($dayName == "Friday") {
                $dayNum = "5";
            } elseif ($dayName == "Saturday") {
                $dayNum = "6";
            } else {
                echo "<script>alert(jhfjfjg)</script>";
            }

            $statusValue = "active";
            $statusvalue = "Active";

            if ($section == "morning") {


                $timeInterval = timeFetch("8:00AM", "12:00PM", $course);

                $update_sql = "SELECT * FROM tbl_instructor_time  
                       WHERE instructor_id = '$instructor' AND slot_id = '$dayNum' AND status = '$statusvalue' AND morning = '$statusValue '";
            } else {
                $timeInterval = timeFetch("4:00PM", "6:00PM", $course);
                $update_sql = "SELECT * FROM tbl_instructor_time   
                       WHERE instructor_id = '$instructor' AND slot_id = '$dayNum' AND status = '$statusvalue' AND evening = '$statusValue '";
            }

            $result = $conn->query($update_sql);





            // Check if any data is fetched
            if ($result->num_rows > 0) {
                $htmlContent = '';

                // echo "Update successful<br>";
                // Split the $timeInterval string into an array of time slots
                $timeSlots = explode(', ', $timeInterval);

                // Return the available time slots in HTML format
                foreach ($timeSlots as $timeSlot) {
                    $checksql = "SELECT * FROM tbl_appointments WHERE instructor_id = '$instructor' AND classneed_date ='$appointmentDate' AND appointment_time = '$timeSlot'";
                    $result2 = $conn->query($checksql);
                    if ($result2->num_rows > 0) {
                        echo "";
                    } else {
                        $htmlContent .= '<div class="col-3" style="width:207px;">';
                        $htmlContent .= '<input type="radio" class="btn btn-primary py-2 px-4 ms-3 time-slot-button"  value="' . $timeSlot . '" style="height:81%;font-size:x-small" name="time" > <label for="" style="margin-left:2px;margin-top:7px;color:#fff;">' . $timeSlot . '</label>';
                        $htmlContent .= '</div>';
                    }
                }

                // Send the generated HTML content as the response
                echo $htmlContent;
            } else {
                $response = '<div style="color:red;" class="col-3">';
                $response .= "<p >No Consulting</p>";
                $response .= '</div>';
                echo $response;
            }
        }
        $timestamp = $appointmentDate = $section = $course = $instructor = "";
    } else {
        $response .= "<script>alert(Please choose your needed time)</script>";
        echo $response;
    }
}




// Process the data and fetch corresponding information
// You can replace this with your actual database queries and data formatting

// Example response (replace with your data)
// $response = "<p>Available time slots for $section on $appointmentDate:for $serviceId</p>";
// $response .= "<ul>";
//$response .= "<li>9:00 AM - 10:00 AM</li>";
// $response .= "<li>10:30 AM - 11:30 AM</li>";
// Add more time slots here based on your data
//$response .= "</ul>";

    // Perform any necessary validation on the data here
