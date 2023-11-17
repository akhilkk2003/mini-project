<?php
include('connection.php');


// Fetch data from the database table
$sql = "SELECT * FROM tbl_services";
$result = $conn->query($sql);
$services = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

// Handle doctor update
if (isset($_POST['update_service'])) {
    $tbl_id = $_POST['tbl_id'];
    $lic_no = $_POST['lic_no'];
    $dob = $_POST['dob'];
    $newAddress = $_POST['new_address'];
    $status = $_POST['status'];

    $update_sql = "UPDATE tbl_services SET 
                   tbl_id = '$tbl_id', 
                   tbl_license_no = '$lic_no', 
                   tbl_dob = '$dob',tbl_new_address = '$newAddress',status='$status'
                   WHERE tbl_id  = '$tbl_id'";

    if ($conn->query($update_sql) === TRUE) {
        // Update successful
        // echo "Update successful!";
        //header("Location: doctors_list.php");
        //exit();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Update failed
        //  echo "Error updating record: " . $conn->error;
    }


    // Redirect back to the doctor list page after updating
    //header("Location: doctors_list.php");
    //exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['tbl_id'])) {
    $tbl_id = $_GET['tbl_id'];



    // Delete the doctor from the database
    $delete_sql = "DELETE FROM tbl_services WHERE tbl_id = '$tbl_id'";
    if ($conn->query($delete_sql) === TRUE) {
        // Deletion successful
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Deletion failed
        echo "Error deleting record: " . $conn->error;
    }
}
if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['service']))) {
    $licenseNumber = $_POST["license_number"];
    $dateOfBirth = $_POST["date_of_birth"];
    $category = $_POST["category"];
    $newAddress = $_POST["new_address"];

    // Step 4: Sanitize and validate data (you should implement your own validation logic here)

    // Step 5: Insert data into the database
    if ($category == "licence_renewal") {
        $category = "licence renewal";
        $sql = "INSERT INTO tbl_services (tbl_license_no, tbl_dob, tbl_category, tbl_new_address) VALUES (?, ?, ?,'N/A')";


        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $licenseNumber, $dateOfBirth, $category);

        if ($stmt->execute()) {
            echo "<script>alert(Data inserted successfully!)</script>";
            header("Location: add_services.php");
        } else {
            echo "Error: " . $stmt->error;
        }
    } else if ($category == "change_address") {
        $category = "Change Of Address";
        $sql = "INSERT INTO tbl_services (tbl_license_no, tbl_dob, tbl_category,tbl_new_address) VALUES (?, ?, ?,?)";


        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $licenseNumber, $dateOfBirth, $category, $newAddress);

        if ($stmt->execute()) {
            echo "<script>alert(Data inserted successfully!)</script>";
            header("Location: add_services.php");
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
    <link rel="stylesheet" href="css/add_services.css">
    <link rel="stylesheet" href="css/style.css">



</head>

<body class="add-services">
    <div class="menu">
        <?php
        include("admin_menu.php");
        ?>
    </div>
    <div class="service-container">
        <center>
            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                <div class="mb-3 service-form-control">
                    <label for="exampleInputEmail1" class="form-label  service-text">License Number</label>
                    <input type="text" name="license_number" class="form-control service-form-control" id="exampleInputEmail1" aria-describedby="emailHelp">


                </div>
                <div class="mb-3 service-form-control">
                    <label for="exampleInputEmail1" class="form-label service-text">Date of birth</label>
                    <input type="date" name="date_of_birth" class="form-control service-form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>

                <div class="mb-3 service-form-control">
                </div>
                <div class="mb-3 service-form-control">
                    <label class="form-label service-text">Category</label><br>
                    <input type="radio" id="licence_renewal" name="category" value="licence_renewal" checked>
                    <label for="licence_renewal" class="service-text">Licence Renewal</label><br>
                    <input type="radio" id="change_address" name="category" value="change_address">
                    <label for="change_address" class="service-text">Change of Address</label><br>
                </div>

                <div class="mb-3 service-form-control">
                    <label for="" class="service-text" id="new_Address">New Address</label>
                    <input type="text" name="new_address" id="newAddress" class="form-label">


                </div>
                <button type="submit" name="service" class="btn btn-primary">Submit</button>
            </form>

        </center>

    </div>
    <div class="col">
        <form class="d-flex" onsubmit="handleSearch(); return false;">
            <div class="d-flex search-container">
                <div class="d-flex searchb">
                    <input id="searchInput" class="search form-control me-2 btn-outline " style="width: 250px;height: 50px;" type="search" placeholder="Search" aria-label="Search">
                </div>
                <div class="d-flex ">
                    <button class="btn btn-outline  custom-input2" style="margin:auto;" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>
    <div class="table-responsive service-container2 ">
        <table class="col-* table table-success table-striped shadow-lg t-hover">
            <thead>
                <tr>
                    <th>UserID</th>
                    <th>License No</th>
                    <th>DOB</th>
                    <th>Category</th>
                    <th>New Address</th>
                    <th>Status</th>
                    <th>Update</th>



                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $index => $service) : ?>
                    <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                        <td><?= $service['tbl_id']; ?></td>
                        <td><?= $service['tbl_license_no']; ?></td>
                        <td><?= $service['tbl_dob']; ?></td>
                        <td><?= $service['tbl_category']; ?></td>
                        <td><?= $service['status']; ?></td>
                        <td><?= $service['tbl_new_address']; ?></td>

                        <div class="d-flex">
                            <!-- ... your existing table rows ... -->
                            <td class="wrapper">
                                <a href="javascript:void(0);" class="btn btn-info" onclick="showEditForm(
        '<?= $service['tbl_id']; ?>',
        '<?= $service['tbl_license_no']; ?>',
        '<?= $service['tbl_dob']; ?>',
        '<?= $service['tbl_new_address']; ?>',
        '<?= $service['status']; ?>'
        
    )">Edit</a>
                                <a href="<?= $_SERVER["PHP_SELF"] ?>?action=delete&tbl_id=<?= $service['tbl_id'] ?>" class="btn btn-danger">Del</a>
                            </td>

                        </div>

                    <?php endforeach; ?>
        </table>
    </div>

    <!-- JavaScript to handle edit form display and submission -->
    <!-- ... your existing code ... -->

    <!-- JavaScript to handle edit form display and submission -->
    <!-- Modal for editing doctor details -->


    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                        <input type="hidden" name="tbl_id" id="editTblId">
                        <label>Doctor Name:</label>
                        <input type="text" name="lic_no" id="editLicNo" required><br>
                        <label>Age:</label>
                        <input type="text" name="dob" id="editDob" required><br>
                        <label>Address:</label>
                        <input type="text" name="new_address" id="editNewAddress" required><br>

                        <label>Status:</label>
                        <select name="status" id="editStatus" required>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                        </select><br>

                        <button type="submit" name="update_service" class="btn btn-success">Update</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function showEditForm(tblId, tblLicenseNo, tblDob, tblNewAddress, status) {
            var modal = document.getElementById("editModal");
            var editForm = document.getElementById("editForm");
            var editTblId = document.getElementById("editTblId");
            var editLicNo = document.getElementById("editLicNo");
            var editDob = document.getElementById("editDob");
            var editNewAddress = document.getElementById("editNewAddress");
            var editStatus = document.getElementById("editStatus");

            editTblId.value = tblId;
            editLicNo.value = tblLicenseNo;
            editDob.value = tblDob;
            editNewAddress.value = tblNewAddress;
            editStatus.value = status;

            $(modal).modal("show");
        }
    </script>





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
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>