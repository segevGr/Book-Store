<html>
  <head>
  <?php
      require 'allPages.php';
  ?>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <link rel="stylesheet" href="css/addBook.css?v=3">
  <link rel="stylesheet" href="css/userOptions.css">
  <title>User Options</title>
  </head>

  <body>

      <?php

        //Connection between the form and SQL
        $segev=mysqli_connect("localhost","root","","singelcon");
        if (mysqli_connect_errno())
            {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }

        $user= $_COOKIE['username'];


        if(isset($_POST['email'])){
            $current  = $_POST['current'];
            $passwordd = $_POST['passwordd'];
            $new = $_POST['new'];

            if (str_contains($current, '\'') || str_contains($passwordd, '\'') || str_contains($new, '\'')) { 
                echo '<script>alert("You must not use the  \'  sign!\r\nplease try again")</script>';
            }
            
            else{
                $checkCurrent = mysqli_query($segev,"SELECT email FROM users WHERE email = '$current';");
                $checkPass = mysqli_query($segev,"SELECT passwordd FROM users WHERE passwordd = '$passwordd';");

                if(mysqli_num_rows($checkCurrent) == 0){
                    echo '<script>alert("Wrong Current Email.\r\nPlease try again")</script>';
                }
                else if(mysqli_num_rows($checkPass) == 0){
                    echo '<script>alert("Wrong Password.\r\nPlease try again")</script>';
                }
                else{
                    $sql = "UPDATE `users` 
                    SET email = '$new'
                    WHERE username = '$user' ";
                    mysqli_query($segev, $sql);
                    echo '<script>alert("email has been updated");</script>';
                }

                mysqli_close($segev);
                $current  = null;
                $passwordd = null;
                $new = null;
                echo '<script> document.getElementById("form").reset();
                location.reload(); </script>';
            }
        }

        if(isset($_POST['changePass'])){
            $current  = $_POST['current'];
            $passwordd = $_POST['passwordd'];
            $new = $_POST['new'];

            if (str_contains($current, '\'') || str_contains($passwordd, '\'') || str_contains($new, '\'')) { 
                echo '<script>alert("You must not use the  \'  sign!\r\nplease try again")</script>';
            }

            else{
                $checkCurrent = mysqli_query($segev,"SELECT passwordd FROM users WHERE passwordd = '$current';");
                $checkUserName = mysqli_query($segev,"SELECT username FROM users WHERE username = '$passwordd';");

                if(mysqli_num_rows($checkCurrent) == 0){
                    echo '<script>alert("Wrong Current Password.\r\nPlease try again")</script>';
                }
                else if(mysqli_num_rows($checkUserName) == 0){
                    echo '<script>alert("Wrong User Name.\r\nPlease try again")</script>';
                }
                else{
                    $sql = "UPDATE `users` 
                    SET passwordd = '$new'
                    WHERE username = '$user' ";
                    mysqli_query($segev, $sql);
                    echo '<script>alert("Password has been updated");</script>';
                }

                mysqli_close($segev);
                $current  = null;
                $new = null;
                $passwordd = null;
                echo '<script> document.getElementById("form").reset();
                location.reload(); </script>';
            }
        }

        echo '<script> document.getElementById("form").reset();
        location.reload(); </script>';

      ?>

    <br>
    <br>

    <!-- Change Password -->
    <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4 changePassword">
    <div class="panel panel-default">
    <div class="panel-body">
    <h2>Change Password</h2>
      <form id = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
      ?>" method = "post" >
          <div class = "bigSize" style="text-align: center;">
          <label for="current">Current Password</label><br>
          <input type="password" id="current" name="current" required><br><br>
          </div>
          <div class = "bigSize" style="text-align: center;">
          <label for="new">New Password</label><br>
          <input type="password" id="new" name="new" required><br><br>
          </div>
          <div class = "bigSize" style="text-align: center;">
          <label for="passwordd">User Name</label><br>
          <input type="text" id="passwordd" name="passwordd" required><br><br>
          </div>
          <button type = "submit" name="changePass" class="btn btn-info btn-block bigSize">Submit to change</button>
      </form>
    </div>
    </div>
    </div>

    <!-- Change Email -->
    <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4 changeEmail">
    <div class="panel panel-default">
    <div class="panel-body">
    <h2>Change Email</h2>
      <form id = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
      ?>" method = "post" >
          <div class = "bigSize" style="text-align: center;">
          <label for="current">Current Email</label><br>
          <input type="email" id="current" name="current" required><br><br>
          </div>
          <div class = "bigSize" style="text-align: center;">
          <label for="new">New Email</label><br>
          <input type="email" id="new" name="new" required><br><br>
          </div>
          <div class = "bigSize" style="text-align: center;">
          <label for="passwordd">Password</label><br>
          <input type="password" id="passwordd" name="passwordd" required><br><br>
          </div>
          <button type = "submit" name="email" class="btn btn-info btn-block bigSize">Submit to change</button>
      </form>
    </div>
    </div>
    </div>
  </body>
</html>

