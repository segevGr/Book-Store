<html>
  <head>
  <?php
      require 'allPages.php';
  ?>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <link rel="stylesheet" href="css/addBook.css?v=3">
  <title>Add Book</title>
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
            $id = $_POST['ids'];
            $name = $_POST['name'];
            $genre = $_POST['genre'];
            $author = $_POST['author'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $publisher = $_POST['publisher'];
            $stock = $_POST['stock'];
            $picurl = $_POST['picurl'];

            //Checking if primary key already exists
            $checkId = mysqli_query($segev,"SELECT bId FROM books WHERE bId = '$id';");
            if(mysqli_num_rows($checkId) == 1){
                echo '<script>alert("Please change id")</script>';
            }

            //Price conditions
            else if($price<0){
              echo '<script>alert("The price should be bigger than 0")</script>';
            }

            //Stock conditions
            else if($stock<0){
              echo '<script>alert("The stock should be bigger than 0")</script>';
            }

            //If all conditions are right, Inserts in a book table 
            else{
              $sql = "INSERT INTO 
              `books`(`bId`, `bName`, `bGenre`, `bAuthor`, `bDescription`, `bPrice`, `bPublisher`, `bStock`, `bPicUrl`) 
              VALUES ('$id','$name','$genre','$author','$description','$price', '$publisher', '$stock','$picurl')";
              mysqli_query($segev, $sql);

              mysqli_close($segev);

              echo '<script>alert("query has been updated");
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
    <h2>Add Book</h2>
      <form class = "form" id = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
      ?>" method = "post" >
          <div class="row">
          <div class="col-xs-6 col-sm-6 col-md-6">
          <label for="id">Id</label><br>
          <input type="number" id="ids" name="ids" required><br><br>
          </div>
          <div class="col-xs-6 col-sm-6 col-md-6">
          <label for="name">Book Name</label><br>
          <input type="text" id="name" name="name" required><br><br>
          </div>
          </div>
          <div class="row">
          <div class="col-xs-6 col-sm-6 col-md-6">
          <label for="author">Author</label><br>
          <input type="text" id="author" name="author" required><br><br>
          </div>
          <div class="col-xs-6 col-sm-6 col-md-6">
          <label for="publisher">Publisher</label><br>
          <input type="text" id="publisher" name="publisher" required><br><br>
          </div>
          </div>
          <div class = "bigSize" style="text-align: center;">
          <label for="genre">Genre</label><br>
          <select id="genre" name="genre" required>
            <option value="drama">Drama</option>
            <option value="comedy"> Psychology</option>
            <option value="ScienceFiction">Science Fiction</option>
            <option value="action">Action</option>
          </select><br><br>
          </div>
          <div class = "bigSize" style="text-align: center;">
          <label for="description">Description</label><br>
          <textarea type="text" id="description" name="description" rows="5" cols="50" required> </textarea><br><br>
          </div>
          <div class="row">
          <div class="col-xs-6 col-sm-6 col-md-6">
          <label for="price">Price</label><br>
          <input type="number" id="price" name="price" required><br><br>
          </div>
          <div class="col-xs-6 col-sm-6 col-md-6">
          <label for="stock">Stock</label><br>
          <input type="number" id="stock" name="stock" required><br><br>
          </div>
          </div>
          <div class = "bigSize" style="text-align: center;">
          <label for="picurl">Picture url</label><br>
          <textarea type="text" id="picurl" name="picurl" rows="2" cols="50" required> </textarea><br><br>
          </div>
          <button type = "submit" name="login" class="btn btn-info btn-block bigSize">Submit</button>
      </form>
    </div>
    </div>
    </div>
  </body>
</html>

