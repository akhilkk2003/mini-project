
<?php
session_start();

include('connection.php');

if (isset($_POST["signup"])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confpassword = $_POST['confpassword'];
    if ($password == $confpassword) {
        $sqlcheck = "SELECT * FROM tbl_users WHERE user_email = '$email'";
        $result1 = mysqli_query($conn, $sqlcheck);

        if ($result1) { // Check if the query was successful
            if (mysqli_num_rows($result1) > 0) {
                $_SESSION["message2"] = "User already exists with this email address";
                header('Location: signup.php');
            } else {
                // Continue with your registration code

                $sql = "INSERT INTO tbl_users (user_name, user_email, password) VALUES ('$name', '$email', '$password')";

                if (mysqli_query($conn, $sql)) {
                    $userid=mysqli_insert_id($conn);
                    $_SESSION["id"] = $userid;
                    $_SESSION["name"] = $name;
                    $_SESSION["email"] = $email;
                    $_SESSION["password"] = $password;
                    header('Location: index.php');
                    exit();
                } else {
                    $_SESSION['message2'] = "Error: " . mysqli_error($conn);
                }
            }
        } else {
            $_SESSION['message2'] = "Error in the SQL query: " . mysqli_error($conn);
            header('Location: signup.php');
            exit();
        }
    } else {
        $_SESSION["message2"] = "Passwords do not match.";
        header('Location: signup.php');
        exit();
    }
}


if (isset($_POST["login"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM tbl_users WHERE user_email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // User exists, perform further actions
        // For example, set session variables or redirect to a logged-in page
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $row['user_name'];
        $_SESSION['password'] = $row['password'];
        $_SESSION['id'] = $row['user_id'];

        if ($row['user_name'] == "admin") {
            $_SESSION['name'] = $row['user_name'];
            header("Location: dashboard_mainpage.php");
        } else {
            header('Location: index.php');
            exit();
        }
    } else {
        $_SESSION["message1"] = "Invalid email or password";

        header('Location: signup.php');
        exit();
    }
}
?>