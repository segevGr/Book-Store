<html>
    <head>
        <title>Reset Password</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" href="css/login.css">

        <?php
            if (isset($_POST['login'])){
                $segev=mysqli_connect("localhost","root","","singelcon");
                if (mysqli_connect_errno()){
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                }
        
                $newPass = $_POST['newPass'];
                if(isset($_POST['rememberMe'])){
                    $rememberMe = time() + 31536000;
                }
                else $rememberMe = time() + 86400;
                
                $username = $_COOKIE['username'];
                $newPassUpdateString = "UPDATE users SET passwordd = '$newPass' WHERE username = '$username'";
                $newPassUpdate = mysqli_query($segev, $newPassUpdateString);

                setcookie('genre','all',$rememberMe);
                setcookie('try','3',time() + 31536000);

                $checkType = mysqli_query($segev,"SELECT usertype FROM users WHERE username = '$username' AND usertype = 'Manager'");
                    if(mysqli_num_rows($checkType) == 1){
                        setcookie('type','Manager',$rememberMe);
                    }
                    else{
                        setcookie('type','User',$rememberMe);
                }

                //Send Mail with new password
                $gotMailFromDB = mysqli_query($segev, "SELECT email FROM users WHERE username = '$username'");
                $gotMailFromDB = mysqli_fetch_array($gotMailFromDB);
                $gotMailFromDB = $gotMailFromDB['email'];

                $receiver = $gotMailFromDB;
                $subject = "Update password change: Segev Bookstore";
                $body = "<h2 style='text-align:left;'>The new password has been updated to</h2><br><h3 style='text-align:left;'>$newPass</h3>";
                $sender = "From:Segev's bookstore";
                $sender.= "MIME-Version: 1.0\r\n";
                $sender.= "Content-Type: text/html; charset=UTF-8\r\n";
    
                if(mail($receiver, $subject, $body, $sender)){
                    echo '<script>alert("The password has been updated and sent to you by email.\r\nWelcome!")</script>';
                }else{
                    echo '<script>alert("ERROR!!!")</script>';
                }


                //End and move to homepage
                echo '<script> window.location.href = "homePage.php"</script>';


            }
        ?>
    </head>

    <body>
        
        <div class="login-box">
            <h2>Enter your new password here</h2>
            <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
                ?>" method = "post" >
                <div class="user-box">
                    <input id="newPass" name="newPass" type="password" required>
                    <label>new password</label>
                </div>
                <input type="checkbox" value="rememberMe" class="rememberMe" name="rememberMe"><label for="rememberMe" id="rememberMe">Remember me</label>
                <button type = "submit" name="login" class="btn btn-info btn-block">Submit</button>
            </form>
        </div>
    </body>
</html>