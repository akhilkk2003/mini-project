<?php
session_start();
include("connection.php");
$message1 = '';
$message2 = '';

if (isset($_SESSION["message1"])) {
	$message1 = $_SESSION["message1"];
	// Store the message content in a JavaScript variable
	$message1 = htmlspecialchars($message1, ENT_QUOTES);
}

if (isset($_SESSION["message2"])) {
	$message2 = $_SESSION["message2"];
	// Store the message content in a JavaScript variable
	$message2 = htmlspecialchars($message2, ENT_QUOTES);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS.css">
    <script src=""></script>
    <title>Document</title>
    
</head>

<body style="background-image: url('img/signup.jpeg'); background-size: cover; /* or 'contain' based on your preference */ background-repeat: no-repeat; background-position: center center;">
    <div class="modal" id="message-modal">
        <div class="modal-content"> 
            <span class="close" onclick="closeModal()">&times;</span>
            <center>
                <p id="message-content"></p>
            </center>
        </div>
    </div>
    <center>

       

        <div class="section">
            <div class="container">
                <div class="row full-height justify-content-center">
                    <div class="col-12 text-center align-self-center py-5">
                        <div class="section pb-5 pt-5 pt-sm-2 text-center">
                            <h6 class="mb-0 pb-3"><span>Log In </span><span>Sign Up</span></h6>
                            <input class="checkbox" type="checkbox" id="reg-log" name="reg-log" />
                            <label for="reg-log"></label>
                            <div class="card-3d-wrap mx-auto" style="   margin-left: 1070px;margin-top: 145px;">
     
                                <div class="card-3d-wrapper">
                                    <div class="card-front">
                                        <div class="center-wrap">
                                            <div class="section text-center">
                                                <form action="api.php" method="POST">
                                                    <h4 class="mb-4 pb-3">Log In </h4>
                                                    <div class="form-group">
                                                        <input type="email" name="email" class="form-style" style=" padding-left: 72px; margin-bottom: 9px;" placeholder="Your Email" id="logemail" autocomplete="off">
                                                        <i class="input-icon uil uil-at"></i>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <input type="password" name="password" class="form-style" style=" padding-left: 72px; margin-bottom: 9px;" placeholder="Your Password" id="logpass" autocomplete="off">
                                                        <i class="input-icon uil uil-lock-alt"></i>
                                                    </div><br><br>
                                                    <input type="submit" href="#" name="login" class="btn mt-4" value="submit">
                                                    <p class="mb-0 mt-4 text-center"><a href="#0" class="link"></a></p>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-back">
                                        <div class="center-wrap">
                                            <div class="section text-center">
                                                <form action="api.php" method="POST">
                                                    <h4 class="mb-4 pb-3">Sign Up</h4>
                                                    <div class="form-group">
                                                        <input type="text" name="name" class="form-style" placeholder="Your Full Name" id="logname" autocomplete="off">
                                                        <i class="input-icon uil uil-user"></i>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <input type="email" name="email" class="form-style" placeholder="Your Email" id="logemail" autocomplete="off">
                                                        <i class="input-icon uil uil-at"></i>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <input type="password" name="password" class="form-style" placeholder="Your Password" id="logpass" autocomplete="off">
                                                        <input type="password" name="confpassword" class="form-style" placeholder="confirm Password" id="logpass" autocomplete="off"><?php echo $message2; ?>
                                                        <i class="input-icon uil uil-lock-alt"></i>
                                                    </div>
                                                    <input type="submit" name="signup" class="btn mt-4" value="submit">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </center>
    <script>
        // JavaScript for modal execution
        console.log("JavaScript for modal execution");

        function closeModal() {
            var modal = document.getElementById('message-modal');
            modal.style.display = 'none';
        }

        // Check if there's a message to display
        var message1 = "<?php echo $message1; ?>";
        var message2 = "<?php echo $message2; ?>";

        if (message1 || message2) {
            document.getElementById('message-content').innerHTML = message1 || message2;
            document.getElementById('message-modal').style.display = 'block';
        }
    </script>

</body>

</html>