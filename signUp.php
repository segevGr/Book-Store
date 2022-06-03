<html>
    <head>
        <title>Sign Up</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" href="css/login.css?v=3">
    </head>

    <body>

    <!-- Connection between the form and SQL -->  
    <?php

        if (isset($_POST['login'])){
        $segev=mysqli_connect("localhost","root","","singelcon");
        if (mysqli_connect_errno())
            {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }

        $username = $_POST['usernameId'];
        $password = $_POST['passwordId'];
        $email = $_POST['email'];

        if(str_contains($username, '\'')||str_contains($username, '\'')||str_contains($username, '\'')){
            echo '<script>alert("You must not use the  \'  sign!\r\nplease try again")</script>';
        }
        else{
            $checkId = mysqli_query($segev,"SELECT username FROM users WHERE username = '$username'");
            if(mysqli_num_rows($checkId) == 1){
                    echo '<script>alert("Username already in use, please select another")</script>';
                    $username = null;
                    $password = null;
                    $email = null;    
                }

            else{
                $sql = "INSERT INTO users (username, passwordd, email, usertype)
                values ('$username', '$password', '$email','User')";
                mysqli_query($segev, $sql);

                mysqli_close($segev);
                echo '<script>alert("User updated in system, now you can log in")</script>
                <script> window.location.href = "login.php"</script>';

            }
        }
        }
    ?>
        <!-- End Connection between the form and SQL --> 


        <div class="login-box">
            <h2>Welcome, please sign up</h2>
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
                <div class="user-box">
                    <input id="email" name="email" type="email" required>
                    <label>email</label>
                </div>
                <button type = "submit" name="login" class="btn btn-info btn-block">Sign Up</button>
            </form>
            <div>
                <h4>Already have a user?</h4>
                <a href = "login.php">Log In</a>
            </div>
        </div>

    </body>
</html>