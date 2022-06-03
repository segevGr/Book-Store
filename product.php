<html lang="en">
    <head>

        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" href="css/product.css">

        <?php

            require 'allPages.php';
            $bookId = $_COOKIE['book'];

            //Connection to server
            $segev=mysqli_connect("localhost","root","","singelcon");
            if (mysqli_connect_errno())
            {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }

            //Saving the details of the specific book
            $sql = "SELECT * FROM `books` WHERE bId = '$bookId'";
            $result = mysqli_query($segev, $sql);
            $row = mysqli_fetch_array($result);

            if(intval($row['bStock']) == 0){
                $hideBuy = "hide";
                $stockText = "Oops<br>The book seems out of stock";
            }
            else{
                $hideBuy = "show";
                $stockText = "There are ".$row['bStock'] ." copies left";
            }

        
        ?>

        <title>Product</title>

    </head>


    <body>

    <!--Product details -->
    <div class="container">
        	<div class="row">
                <?php

                    //Displays the image
                    echo '<div class="col-xs-4 item-photo">';
                        echo '<img class="bookImage" src ="' .$row['bPicUrl'] .'">';
                    echo '</div>';

                    //Div of all product details
                    echo '<div class="col-xs-5" style="border:0px solid gray" >';

                        //Seller information and product title
                        echo '<h1 class="bookName"><strong>'.$row['bName'] .'</strong></h1>';

                        //Remove Button
                        echo '<button class="removeBtn '.$hide.'" id = "'.$row['bId'].'" onclick="return removeFromDB(id);" >Remove <i class="fa fa-trash" aria-hidden="true"></i></button>';
                        
                        //Author & Ganre
                        echo '<h2 <a href="#" class="bookAuthor">'.$row['bAuthor'] .'</a><br>
                             <a href="homePage.php" id="'.$row['bGenre'].'" onclick="return genre(id);">'.$row['bGenre'].'</a></h2> <br>';
                        
                        //Edit Button
                        echo '<button class="editBtn '.$hide.'" id = "'.$row['bId'].'" onclick="return editBook(id);" >Edit <i class="fa fa-edit" aria-hidden="true"></i></button>';     
            
                        //Price
                        echo '<h1 style="margin-top:0px;">'.$row['bPrice'] .'₪</h1> <br>';
            
                        //Product specific details
                        echo '<div class="section" style="padding-bottom:5px;">';
                            echo'<h3 class="title-attr"><small>Publisher: '.$row['bPublisher'] .'</small></h3> <br> ';                  
                            echo '<div dir="rtl">';
                            if($row['bStock']>20){
                                echo '<h3 class="attr2" id = "stock">There are '.$row['bStock'] .' copies left</h3>';
                            }
                            else{
                                echo '<h3 class="attr2" id = "stock" style="color: red"><strong>'.$stockText.'</strong></h3>';
                            }
                            echo '</div>';
                        echo '</div>   <br>';
            
                        //buy buttons
                        echo '<div class="section" style="padding-bottom:20px;">';
                            echo '<button class="btn '.$hideBuy.'" id = "'.$row['bId'].'" style ="font-size: 20px" onclick="return addToCart(id);" >Add to cart<i style="margin-left:5px" class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></i></button>';
                        echo '</div>';
                        
                        //description About the Book and the Author
                        echo '<div>';
                            echo '<br><h2>Description:</h2><br>';
                            echo '<h4 dir="rtl">'.$row['bDescription'] .'</h4> <br> <br>';
                        echo '</div>';
                    echo '</div>';
                               
                ?>                            
            </div>
        </div>        
    </body>
</html>


<script>

    //Create a cookie with the ID of the book we want to add to the cart
    function addToCart(id){
        alert("Product added to cart!");
        document.cookie = "add="+id+"; expires=Thu, 18 Dec 2025 12:00:00 UTC";
        location.href = "product.php";
    }

    //Set a genre cookie
    function genre(id){
        document.cookie = "genre="+id+"; expires=Thu, 18 Dec 2025 12:00:00 UTC";
        return true;
    }

    /* Create a cookie with the book ID we want to remove from the site.
    The removal function is located in HomePage.php */
    function removeFromDB(id){
        alert("Item removed from site");
        document.cookie = "remove="+id+"; expires=Thu, 18 Dec 2025 12:00:00 UTC";
        location.href = "product.php";
    }

    //Edit Book Details
    function editBook(id){
        let edit = prompt("Please select what you want to change:\r\nStock-s, Price-p");
        if(edit=='s' || edit=='S'){
            let choose = prompt("Insert the current stock:");
            if (choose == null || choose == ""){
                alert("Operation canceled");
            }
            else if(isNaN(choose)){
                alert("Stock must be a number!");
            }
            else{
                document.cookie = "editBook="+id+"; expires=Thu, 18 Dec 2025 12:00:00 UTC";
                document.cookie = "editThing=bStock; expires=Thu, 18 Dec 2025 12:00:00 UTC";
                document.cookie = "editChange="+choose+"; expires=Thu, 18 Dec 2025 12:00:00 UTC";
                location.href = "product.php";
            }
        }
        else if (edit =='p' || edit=='P'){
            let choose = prompt("Insert the current price:");
            if (choose == null || choose == ""){
                alert("Operation canceled");
            }
            else if(isNaN(choose)){
                alert("price must be a number!");
            }
            else{
                document.cookie = "editBook="+id+"; expires=Thu, 18 Dec 2025 12:00:00 UTC";
                document.cookie = "editThing=bPrice; expires=Thu, 18 Dec 2025 12:00:00 UTC";
                document.cookie = "editChange="+choose+"; expires=Thu, 18 Dec 2025 12:00:00 UTC";
                location.href = "product.php";
            }
        }
        else{
            alert("Operation canceled");
        }
    }

</script>

<?php

    /*Once it detects that there is a cookie with a book you want to add to the cart,
    PHP adds the product to the cart,
    deletes the cookie and refreshes the page*/
    if ((isset($_COOKIE['add']))){
        $slash = 'css\\\Pictures\\';

        $insert = "INSERT into cart (cId, cName, cAuthor, cPrice, cPublisher, cPicUrl)
        VALUES ('".$row['bId']."','".$row['bName']."','".$row['bAuthor']."','".$row['bPrice']."','".$row['bPublisher']."','".$slash."\\".$row['bName'].".jpg')";
        mysqli_query($segev, $insert);
        mysqli_close($segev);
        echo '<script>document.cookie = "add=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";</script>';
        echo '<script>location.href = "homePage.php";</script>';
    }

    /*Once it detects that there is a cookie with a book you want to remove from the site,
    PHP removes the product from the books DB,
    deletes the cookie and refreshes the page*/
    if ((isset($_COOKIE['remove']))){

        $remove = "DELETE FROM books WHERE bId = ".$row['bId']."";
        mysqli_query($segev, $remove);
        mysqli_close($segev);
        echo '<script>document.cookie = "remove=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";</script>';
        echo '<script>location.href = "homePage.php";</script>';
    }

    if(isset($_COOKIE['editBook'])){
        $change = $_COOKIE['editThing'];
        $edit = $_COOKIE['editChange'];
        $book = $_COOKIE['editBook'];
        $sqlChange ="UPDATE `books` 
        SET $change = '$edit'
        WHERE bId = '$book' ";

        mysqli_query($segev, $sqlChange);
        mysqli_close($segev);
        echo '<script>document.cookie = "editBook=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";</script>';
        echo '<script>document.cookie = "editThing=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";</script>';
        echo '<script>document.cookie = "editChange=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";</script>';
        echo '<script>location.href = "product.php";</script>';

    }

?>

