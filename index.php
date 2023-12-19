<?php 
require('conn.php');
session_start();
$password_error = "Password is wrong";
$mail_error = "Connect account doesn't exist, please register";
$fout = 0;

// If form submitted, insert values into the database.
if (isset($_POST['mail'])) {
    $mail = stripslashes($_POST['mail']);
    $mail = mysqli_real_escape_string($conn, $mail);
    $password_new = stripslashes($_POST['password_new']);
    $password_new = mysqli_real_escape_string($conn, $password_new);

    // Prepare the SQL statement for admin login
    $query = "SELECT * FROM `users` WHERE mail='$mail' AND password_new='$password_new' AND user_id=1";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $rows = mysqli_num_rows($result);

    if ($rows == 1) {
        $_SESSION['user_id'] = 1; // You may want to set it to the correct user_id
        header("Location: home.php");
    } else {
        // If not admin, check for regular user login
        $query = "SELECT * FROM `users` WHERE mail='$mail' AND password_new='$password_new'";
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $rows = mysqli_num_rows($result);

        if ($rows == 1) {
            // Get user_id and store it in the session
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['user_id'];
            header("Location: home.php");
        } else {
            $fout = 2; // Password error

            // You can add other checks here if needed
        }
    }
}
?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/login.css" />
   
</head>

<body>
   
        <div class="container">
            <div class="left-section">
                <div class="center">
                    <h1>Welcome back!</h1>
                    <form method="POST">
                        <!-- Email -->
                        <div class="txt_field">
                            <label class="input-label">Email</label>
                            <br>
                            <input class="inputfield" type="text" name="mail" placeholder="" required>
                            <p class="error mail-error" id="mailerr">
                               
                                 <?php 
                                   /* if ($fout==1){
                                        echo $mail_error;
                                    }
                                    else if($fout==2){
                                        echo "";
                                    }*/
                                        
                                ?>

                            </p>
                        </div>

                        <!-- Password -->
                        <div class="txt_field" id="password">
                            <label>Password</label>
                            <br>
                            <input class="inputfield" type="password" name="password_new" placeholder="" required>
                            <span class="error password-error" id="pwerr">
                                <?php 
                                    if ($fout==2 ){
                                        echo $password_error;
                                    }       
                                ?>
                            
                           
                            </span>
                        </div>

                        <input class="input-submit d-block mr-0 ml-auto" type="submit" value="Login">
                    </form>


                </div>
                <div class="signup-link d-block mb-0">
                    Not a member? <a href="register.php"> Sign up</a>
                </div>

            </div>
            <div class="right-section">
                
                <img src="assets/images/Connectlogo2.png" class="picture" />
                
            </div>
            
        </div>
</body>

</html>