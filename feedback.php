<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
include("connection.php");

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
$feedbacks=fetchTableData($conn,"tbl_feedbacks");
// Handle doctor update 
if (isset($_POST['update_status'])) {
    $feedbackid = $_POST['feedbackid'];
    $status  = $_POST['status'];
    
   // $icecream_quantity = $_POST['icecream_quantity'];

    //$cat_id  = $_row['cat_id'];




    // Update data in the table 
    $update_sql = "UPDATE tbl_feedbacks SET   status  = ? WHERE feedback_id = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $status,$feedbackid);

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
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['feedback_id'])) {
    $feedbackid = $_GET['feedback_id'];
    
    // Delete the doctor from the database 
    $delete_sql = "DELETE FROM tbl_feedbacks WHERE feedback_id = '$feedbackid'";
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
    <meta charset="utf-8">
    <link rel="icon" href="img/ice-cream.png" type="image/png" sizes="20x20">
    <title>NECTOR ICECREAMS</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">




    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include('admin_menu.php') ?>
    <div class="orderList">
       <h3>Orders</h3>
       <table class=" table table-dark table-striped shadow-lg ">
                <thead>
                    <tr>
                        <th>FEEDBACK_ID</th>
                        <th>USER_ID</th>
                        <th>FEEDBACK</th>           
                        <th>STATUS</th>
                        <th>UPDATE</th>





                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedbacks as $index1=> $feedback) { ?>
                        <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                            <td><?= $feedback['feedback_id']; ?></td>
                            <td><?= $feedback['user_id']; ?></td>
                            <td><?= $feedback['feedback']; ?></td>
                            <td><?= $feedback['status']; ?></td>
                            
                        
                           
                            

                            <div class="d-flex">
                                <!-- ... your existing table rows ... -->
                                <td class="wrapper">
                                    <a href="javascript:void(0);" class="btn btn-info" onclick="showEditForm(<?= $feedback['feedback_id']; ?>,'<?= $feedback['status'];  ?>')">Edit</a>

                                    <a href="<?= $_SERVER["PHP_SELF"] ?>?action=delete&feedback_id=<?= $feedback['feedback_id']; ?>" class="btn btn-danger">Del</a>
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
                            function showEditForm(feedbackid, status) {
                                var modal = document.getElementById("editModal");
                                var modalBody = modal.querySelector(".modal-body");

                                var form = `order
    <form action="" method="post">
        <input type="hidden" name="feedbackid" value="${feedbackid}">
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
    </div>
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
</body>
</html>