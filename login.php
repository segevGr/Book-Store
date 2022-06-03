<html>
    <head>
        <title>Login</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" href="css/login.css">
    </head>

    <body>

    <script type="text/javascript">
        function disableBack() { window.history.forward(); }
        setTimeout("disableBack()", 0);
        window.onunload = function () { null };
    </script>
    <!-- Connection between the form and SQL -->
    <?php


        if(isset($_COOKIE['type'])){
            echo '<script> window.location.href = "homePage.php"</script>
            <script> window.location.href = "login.php"</script>';
        }

        if (isset($_POST['login'])){
        $segev=mysqli_connect("localhost","root","","singelcon");
        if (mysqli_connect_errno())
            {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }

        $username = $_POST['usernameId'];
        $password = $_POST['passwordId'];

        if (str_contains($username, '\'')) { 
            echo '<script>alert("Username can\'t contain  \'  sign!\r\nplease try again")</script>';
        }
        else if(str_contains($password, '\'')){
            echo '<script>alert("Password can\'t contain  \'  sign!\r\nplease try again")</script>';
        }
        else{
            if(isset($_POST['rememberMe'])){
                $rememberMe = time() + 31536000;
            }
            else $rememberMe = time() + 21600;

            $checkId = mysqli_query($segev,"SELECT username FROM users WHERE username = '$username'");
            if(mysqli_num_rows($checkId) == 1){
                $checkPass = mysqli_query($segev,"SELECT username FROM users WHERE username = '$username' AND passwordd = '$password'");
                if(mysqli_num_rows($checkPass) == 1){
                    if(intval($_COOKIE['try']) != 0 || !(isset($_COOKIE['try']))){
                        $checkType = mysqli_query($segev,"SELECT usertype FROM users WHERE username = '$username' AND passwordd = '$password' AND usertype = 'Manager'");
                        if(mysqli_num_rows($checkType) == 1){
                            setcookie('type','Manager',$rememberMe);
                        }
                        else{
                            setcookie('type','User',$rememberMe);
                        }
                        setcookie('genre','all',$rememberMe);
                        setcookie('username',$username ,$rememberMe);
                        setcookie('try','3',time() + 31536000);
                        echo '<script>alert("Welcome !")</script>
                        <script> window.location.href = "homePage.php"</script>';
                    }

                    else{
                        setcookie('username',$username ,time() + 86400);
                        echo '<script>alert("Correct alternate password\r\nYou are taken to a new password setting page")</script>';
                        echo '<script> window.location.href = "resetPass.php"</script>';
                    }
                }
                else {
                    
                    if($_COOKIE['try'] != 0){
                        $try = intval($_COOKIE['try']) - 1;
                        if($try != 0){
                            setcookie('try',$try ,time() + 31536000);
                            echo '<script>alert("The password does not match the username.\r\nYou have '.$try.' tries left.")</script>';
                        }

                        //RESET PASSWORD
                        else{
                            $gotMailFromDB = mysqli_query($segev, "SELECT email FROM users WHERE username = '$username'");
                            $gotMailFromDB = mysqli_fetch_array($gotMailFromDB);
                            $gotMailFromDB = $gotMailFromDB['email'];


                            $length = 7;    
                            $randPass = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
                
                            $receiver = $gotMailFromDB;
                            $subject = "Password Recovery: Segev Bookstore";
                            $body = "<h2 style='text-align:left;'>Your new temporary password is</h2><br><h3 style='text-align:left;'>$randPass</h3>";
                            $sender = "From:Segev's bookstore";
                            $sender.= "MIME-Version: 1.0\r\n";
                            $sender.= "Content-Type: text/html; charset=UTF-8\r\n";
                            
                            if(mail($receiver, $subject, $body, $sender)){
                                echo '<script>alert("Youve run out of attempts. A new password has been sent to you by email")</script>';
                            }else{
                                echo '<script>alert("ERROR!!!")</script>';
                            }
                
                            $newPassUpdateString = "UPDATE users SET passwordd = '$randPass' WHERE username = '$username'";
                            $newPassUpdate = mysqli_query($segev, $newPassUpdateString);
                            
                            setcookie('try',0 ,time() + 31536000);
                        }
                    }

                    else{
                        echo '<script>alert("You have run out of attempts,\r\na new password has been sent to you by email")</script>';
                    }
                }
            }

            else{
                echo '<script>alert("Username does not exist, please try again")</script>';
                $username = null;
                $password = null;
            }
        }

        //When we are on the login page, the cart is emptied
        $clearCartString = "DELETE FROM cart";
        $clearCart = mysqli_query($segev, $clearCartString);
        }
    ?>
        <!-- End Connection between the form and SQL --> 


        <div class="login-box">
            <h2>Welcome, please Log in</h2>
            <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
                ?>" method = "post" >
                <div class="user-box">
                    <input id="usernameId" name="usernameId" type="text" required>
                    <label>Username</label>
                </div>
                <div class="user-box">
                    <input id="passwordId" name="passwordId" type="password" required>
                    <label>Password</label>
                </div>
                <input type="checkbox" value="rememberMe" class="rememberMe" name="rememberMe"><label for="rememberMe" id="rememberMe">Remember me</label>
                <button type = "submit" name="login" class="btn btn-info btn-block">Submit</button>
            </form>
            <div>
                <h4>Don't have a user?</h4>
                <a href = "signUp.php">Sing Up</a>
            </div>
        </div>

    </body>
</html>

