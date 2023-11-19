<?php
include('connection.php');
function fetchTableData($conn, $tableName)
{
  $sql = "SELECT * FROM $tableName ORDER BY appointment_id DESC";
  $result = $conn->query($sql);
  $data = [];

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
  }

  return $data;
}

$appointments=fetchTableData($conn,"tbl_appointments"); 
function fetchname($conn, $tableName, $colname, $id)
{
    $sql = "SELECT * FROM $tableName WHERE $colname = '$id'";
    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

// Handle doctor update 
if (isset($_POST['update_status'])) {
    $learnerid = $_POST['learnerid'];
    $status  = $_POST['status'];
    
   // $icecream_quantity = $_POST['icecream_quantity'];

    //$cat_id  = $_row['cat_id'];




    // Update data in the table 
    $update_sql = "UPDATE tbl_appointments SET   status  = ? WHERE learner_id = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $status,$learnerid);

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
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['learner_id'])) {
    $learnerid = $_GET['learner_id'];
    
    // Delete the doctor from the database 
    $delete_sql = "DELETE FROM tbl_appointments WHERE learner_id = '$learnerid'";
    if ($conn->query($delete_sql) === TRUE) {
        // Deletion successful 
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Deletion failed 
        echo "Error deleting record: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>

<body>
    <?php include('admin_menu.php'); ?>
    <div class="d-flex">
        <div class="orderList">
            <h3></h3>
            <table class=" table table-dark table-striped shadow-lg ">
                <thead>
                    <tr>
                        <th>APPOINTMENT_ID</th>
                        <th>LEARNER NAME</th>
                        <th>EMAIL</th>
                        <th>SERVICE</th>
                        <th>INSTRUCTOR NAME</th>
                        <th>SECTION</th>
                        <th>APPOINTMENT TIME</th>
                        <th>CLASS NEED DATE</th>
                        <th>STATUS</th>
                        <th>UPDATE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $index1 => $appointment) { ?>
                        <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                            <td><?= $appointment['appointment_id']; ?></td>
                            <td><?php $l_id=$appointment['learner_id']; 
                                $ns=fetchname($conn,'tbl_learners_details','learner_id',$l_id);
                                foreach ($ns as $index => $n) {
                                echo $n['full_name'];
                                }
                            ?></td>
                            <td><?= $appointment['user_email']; ?></td>
                            <td><?php $s_id= $appointment['service_id'];
                            $ss=fetchname($conn,'tbl_package','package_id',$s_id);
                            foreach ($ss as $index => $s) {
                                echo $s['package_name'];
                                } ?></td>
                            <td><?php $i_id=$appointment['instructor_id']; 
                             $is=fetchname($conn,'tbl_instructors','instructor_id',$i_id);
                             foreach ($is as $index => $i) {
                                 echo $i['instructor_name'];
                                 }?></td>
                            <td><?= $appointment['section']; ?></td>
                            <td><?= $appointment['appointment_time']; ?></td>
                            <td><?= $appointment['classneed_date']; ?></td>
                            <td><?= $appointment['status']; ?></td>





                            <div class="d-flex">
                                <!-- ... your existing table rows ... -->
                                <td class="wrapper">
                                    <a href="javascript:void(0);" class="btn btn-info" onclick="showEditForm('<?= $appointment['learner_id']; ?>','<?= $appointment['status'];?>')">Edit</a>

                                    <a href="<?= $_SERVER["PHP_SELF"] ?>?action=delete&learner_id=<?= $appointment['learner_id']; ?>" class="btn btn-danger">Del</a>
                                </td>

                            </div>

                        <?php } ?>
            </table>
        </div>
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Status</h5>
                        <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>


                    </div>
                    <div class="modal-body">
                        <script>
                            function showEditForm(learnerid, status) {
                                var modal = document.getElementById("editModal");
                                var modalBody = modal.querySelector(".modal-body");

                                var form = `
                                                  <form action="" method="post">
                                                      <input type="hidden" name="learnerid" value="${learnerid}">
                                                      <label>status</label>
                                                      <select name="status" required>
                                                          <option value="approved" ${status === 'approved' ? 'selected' : ''}>Approved</option>
                                                          <option value="rejected" ${status === 'rejected' ? 'selected' : ''}>Rejected</option>
                                                      </select><br>
                                                      <button type="submit" name="update_status" class="btn btn-success">Update</button>
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
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/isotope/isotope.pkgd.min.js"></script>
        <script src="lib/lightbox/js/lightbox.min.js"></script>

        <!-- Contact Javascript File -->
        <script src="mail/jqBootstrapValidation.min.js"></script>
        <script src="mail/contact.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
</body>

</html>