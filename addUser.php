<html>
  <head>
  <?php
      require 'allPages.php';
  ?>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <link rel="stylesheet" href="css/addBook.css?v=3">
  <title>Add New User</title>
  </head>

  <body>

      <?php

          //Connection between the form and SQL
          if (isset($_POST['login'])){
            $segev=mysqli_connect("localhost","root","","singelcon");
            if (mysqli_connect_errno())
              {
              echo "Failed to connect to MySQL: " . mysqli_connect_error();
              }


            //Getting data from the form and set in php variable
            $username  = $_POST['username'];
            $passwordd = $_POST['passwordd'];
            $email = $_POST['email'];
            $usertype = $_POST['usertype'];

            //Checking if primary key already exists
            $checkId = mysqli_query($segev,"SELECT username FROM users WHERE username = '$username';");
            if(mysqli_num_rows($checkId) == 1){
                echo '<script>alert("Username already exists.\r\nPlease choose another name")</script>';
                $username = null;
                $passwordd = null;
                $email = null;
                $usertype = null;
            }

            //If all conditions are right, Inserts into the Users table 
            else{
              $sql = "INSERT INTO 
              `users`(`username`, `passwordd`, `email`, `usertype`) 
              VALUES ('$username','$passwordd','$email','$usertype')";
              mysqli_query($segev, $sql);

              mysqli_close($segev);

              echo '<script>alert("user has been updated");
                  document.getElementById("form").reset();
                  location.reload(); </script>';
            }
          }
      ?>


    <br>
    <br>
    <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
    <div class="panel panel-default">
    <div class="panel-body">
    <h2>Add New User</h2>
      <form id = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
      ?>" method = "post" >
          <div class = "bigSize" style="text-align: center;">
          <label for="username">username</label><br>
          <input type="text" id="username" name="username" required><br><br>
          </div>
          <div class = "bigSize" style="text-align: center;">
          <label for="passwordd">password</label><br>
          <input type="password" id="passwordd" name="passwordd" required><br><br>
          </div>
          <div class = "bigSize" style="text-align: center;">
          <label for="email">email</label><br>
          <input type="email" id="email" name="email" required><br><br>
          </div>
          <div class = "bigSize" style="text-align: center;">
          <label for="usertype">usertype</label><br>
          <select id="usertype" name="usertype" required>
            <option value="Manager">Manager</option>
            <option value="User"> User</option>
          </select><br><br>
          </div>
          <button type = "submit" name="login" class="btn btn-info btn-block bigSize">Submit</button>
      </form>
    </div>
    </div>
    </div>
  </body>
</html>

