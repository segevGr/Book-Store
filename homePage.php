<html lang="en">
    <head>
        <?php
            require 'allPages.php';
        ?>
    <title>Home Page</title>
    </head>

    <body>

        <?php

            $segev=mysqli_connect("localhost","root","","singelcon");
            if (mysqli_connect_errno())
            {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }
            
            $genre = $_COOKIE['genre'];
            if($genre=='all'){
                $sql = 'SELECT * FROM `books`';
                $result = mysqli_query($segev, $sql); 
                $genre = 'All Books';   
            }
            else {
                if($genre=='Science-Fiction'){
                    $genre = 'Science Fiction';
                }
                $sql = "SELECT * FROM `books` WHERE bGenre = '$genre'";
                $result = mysqli_query($segev, $sql);    
            }

            echo '<br>';
            echo '<h1 style="text-align: center;" class = "titles"><strong>'.$genre.'</strong></h1>';
            
            echo '<div class="container">';
            echo '<div class="row">';
    
            


            while($row = mysqli_fetch_array($result))
                {
                echo '<div class="col-md-3 col-sm-6">';
                    echo  '<div class="product-grid4">';
                        echo    '<div class="product-image4">';
                            echo '<a id="'.$row['bId'].'" href="product.php" onclick="return getBook(id);">';
                                echo  '<img class = "pic-1" src ="' .$row['bPicUrl'] .'">';
                                echo  '<img class = "pic-2" src ="' .$row['bPicUrl'] .'">';
                            echo '</a>';
                    echo '</div>';
                        echo '<div class="product-content"><br>';
                            echo '<h1 class="title" ><a id="'.$row['bId'].'" href="product.php" onclick="return getBook(id);"><strong><strong>'.$row['bName'] .'</strong></strong></a></h1>';
                                echo '<div class="price">';
                                    echo ''. $row['bPrice'] . '₪';
                                echo '</div>';
                            if(intval($row['bStock']) == 0){
                                echo '<button class="add-to-cart" id = "'.$row['bId'].'" onclick="getBookFromBtn(id);">Out of stock...<i class="fa fa-frown-o" aria-hidden="true"></i></button>';
                            }
                            else{
                                echo '<button class="add-to-cart" id = "'.$row['bId'].'" onclick="return addToCart(id);">ADD TO CART <i class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></i></button>';
                            }    
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }

            echo '</div>';
            echo '</div>';
    

            ?>

    </body>
</html>



<script type="text/javascript">

    //Create a cookie with the book ID we want to see, and move to the book page
    function getBook(id) {
        document.cookie = "book="+id+"; expires=Thu, 18 Dec 2025 12:00:00 UTC";
        return true;
    }

    function getBookFromBtn(id) {
        document.cookie = "book="+id+"; expires=Thu, 18 Dec 2025 12:00:00 UTC";
        alert("Oops ...\r\nthe book is out of stock.\r\nFeel free to take a look at the book page anyway.");
        window.location.href = "product.php";
    }
    

    //Create a cookie with the ID of the book we want to add to the cart
    function addToCart(id){

        alert("Product added to cart!");
        document.cookie = "add="+id+"; expires=Thu, 18 Dec 2025 12:00:00 UTC";
        location.reload();
    }
</script>


<?php

    /*Once it detects that there is a cookie with a book you want to add to the cart,
    PHP adds the product to the cart,
    deletes the cookie and refreshes the page*/
    if ((isset($_COOKIE['add']))){

        $sql = "SELECT * FROM `books` WHERE bId = '".$_COOKIE['add']."'";
        $bookDetails = mysqli_query($segev, $sql);
        $row = mysqli_fetch_array($bookDetails);

        $slash = 'css\\\Pictures\\';

        $insert = "INSERT into cart (cId, cName, cAuthor, cPrice, cPublisher, cPicUrl)
        VALUES ('".$row['bId']."','".$row['bName']."','".$row['bAuthor']."','".$row['bPrice']."','".$row['bPublisher']."','".$slash."\\".$row['bName'].".jpg')";
        mysqli_query($segev, $insert);
        mysqli_close($segev);
        echo '<script>document.cookie = "add=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";</script>';
        echo '<script>location.href = "homePage.php";</script>';
    }
?>
