<html>
    <head>

        <!--Button css-->
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

        <?php
            require 'allPages.php';
            
            //Connection to server
            $segev=mysqli_connect("localhost","root","","singelcon");
            if (mysqli_connect_errno())
            {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }


            $sql = 'SELECT * FROM `cart`';
            $result = mysqli_query($segev, $sql);

            $countString = 'SELECT COUNT(*) as num from `cart`';
            $sqlCount = mysqli_query($segev, $countString);
            $count = mysqli_fetch_assoc($sqlCount);

            $sumString = 'SELECT SUM(cPrice) as total from `cart`';
            $sqlSum = mysqli_query($segev, $sumString);
            $sum = mysqli_fetch_assoc($sqlSum);

            if($count['num']==0){
                $sumText = "Your cart is empty<br><a href='homePage.php'>go grab yourself something</a>";
                $hideCoupon = "hide";
            }
            else{
                $sumTotal = $sum['total'];
                $sumText = "Total: ".$sumTotal."₪";

                if(isset($_COOKIE['coupon'])){
                    $discount = ($sum['total'] * 20)/100;
                    $sumTotal = $sum['total'] - $discount;
                    $sumText = "You used the Segev coupon<br>Total: ".$sumTotal."₪";
                }
                
                $hideCoupon = "show";
            }

            $orderNumber = rand();
            while(true){
                $checkOrderNum = mysqli_query($segev,"SELECT orderNum FROM orders WHERE orderNum = '$orderNumber';");
                if(mysqli_num_rows($checkOrderNum) == 0){
                    break;
                }
                $orderNumber = rand();
            }

        ?>

        <title>cart</title>
        
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/cart.css">
    </head>

    <body>
        <br> <br>

        <?php

            echo '<div class="checkout-summary">';
                echo '<div class="checkout-summary-title">';
                    echo '<h2><strong>Your Order</strong></h2>';
                    echo '<h3><strong>'.$count['num'].' Items</strong></h3>';
                echo '</div>';

                while($row = mysqli_fetch_array($result)){

                        echo '<div class="checkout-summary-item">';
                            echo '<img src="'.$row['cPicUrl'].'">';
                            echo '<div class="item-name"><br><br><br>';
                                echo '<h2><strong>'.$row['cName'].'</strong></h2>';
                                echo '<h4><span class="title"><strong>Author: </strong></span>'.$row['cAuthor'].'</h4>';
                                echo '<h5><span class="title"><strong>Publisher: </strong></span>'.$row['cPublisher'].'</h5>';
                            echo ' </div>';
                            echo '<div class="item-price"><br><br><br><br>';
                                echo '<h3 class="price"><strong>'.$row['cPrice'].'₪</strong></h3><br><br><br>';
                                echo '<a id = "'.$row['cBookId'].'" class = "remove" onclick="return cRemove(id);" href="">Remove <i class="fa fa-trash" aria-hidden="true"></i></a>';
                            echo '</div>';
                        echo '</div>';
                    }

                    echo '<div class="total '.$hideCoupon.'"><br>';
                        echo '<h4><strong>Do you have a coupon code?</strong></h4><br>';
                        echo '<form>';
                            echo '<input class="coupon" type="text" id="coupon" name="coupon" placeholder = "insert here"><br><br>';
                        echo '</form><br>';
                        echo '<button id="addCoupon" name="couponBtn" class="couponBtn" onclick = "checkCoupon()";><strong>add coupon</strong></button>';
                        echo '<button id="removeCoupon" name="couponBtn" class="couponBtn" onclick = "removeCoupon()";><strong>remove coupon</strong></button>';
                    echo '</div>';


                    echo '<div class="total"><br>';
                            echo '<h2><strong>'.$sumText.'</strong></h2><br>';
                            echo '<button name="checkout" class="btn btn-info btn-block '.$hideCoupon.'" onclick = "checkout()";>Checkout</button><br>';
                    echo '</div>';
            echo '</div>';

        ?>
            
    </body>
</html>

<!-- Create a cookie with the book number we want to remove-->

<script type="text/javascript">

    function cRemove(id){
        alert("Product removed from cart");
        document.cookie = "remove="+id+"; expires=Thu, 18 Dec 2025 12:00:00 UTC";
        return true;
    }

    function checkout(){
        document.cookie = "order=; expires=Thu, 18 Dec 2025 12:00:00 UTC";
        location.href = "cart.php";
    }

    /*Checks the coupon the user has entered,
    and if correct creates a cookie that updates that there is a discount*/
    function checkCoupon(){
        var x = document.getElementById("coupon").value;
        if(x==null){
            alert("You need to enter a code");
        }
        else if(x=="segev"){
            if(document.cookie.indexOf("coupon=") == -1){
                alert("A Valid coupon,\r\nyou received a 20% discount");
                document.cookie = "coupon=1; expires=Thu, 18 Dec 2025 12:00:00 UTC";
                location.href = "cart.php";
            }
            else{
                alert("Coupon already in use");
            }
        }
        else{
            alert("Coupon does not exist");
        }
    }

    //Checks if there is a coupon discount, and if so removes it
    function removeCoupon(){
        if(document.cookie.indexOf("coupon=") != -1){
            document.cookie = "coupon=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
            alert("The discount has been removed");
            location.href = "cart.php";
        }
        else{
            alert("There is no active coupon at this time");
        }

    }

</script>

<?php

    /*Once there is a cookie with the book we want to remove,
    a PHP function is activated which removes it and refreshes the page */
    if ((isset($_COOKIE['remove']))){

        $removeString = "DELETE FROM cart WHERE cBookId = '".$_COOKIE['remove']."'";
        $sqlRemove = mysqli_query($segev, $removeString);
        echo '<script>document.cookie = "remove=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";</script>';
        echo '<script>location.href = "cart.php";</script>';
    }

    if((isset($_COOKIE['order']))){
        $addOredersString = 'SELECT * FROM `cart`';
        $addOreders = mysqli_query($segev, $addOredersString);

        //Check whether the order can be made taking stock
        $checkStockString = "SELECT cId as 'id', COUNT(CId) as 'count' from cart GROUP by cId";
        $checkStock = mysqli_query($segev, $checkStockString);
        $doADD = 'true';
        while($rows = mysqli_fetch_array($checkStock)){
            $currentStockString = "SELECT bName as 'name', bStock as 'stock' from books Where bId = ".$rows['id']."";
            $currentStock = mysqli_query($segev, $currentStockString);
            $currentStock = mysqli_fetch_array($currentStock);
            if(intval($currentStock['stock']) < intval($rows['count'])){
                $doADD = 'false';
                echo '<script>alert("Error!\r\nYou ordered more \"'.$currentStock["name"].'\" then we have.\r\nplease check our stock and order by that.");</script>';
                break;
            }
        }

        if($doADD == 'true'){
            //Add all from Cart to Orders And a deduction from the existing stock
            while($rows = mysqli_fetch_array($addOreders)){
                $insertOrders = "INSERT INTO orders (oUser, oBookId, oDate, orderNum, oBookName )
                Values('".$_COOKIE['username']."','".$rows['cId']."','".date('Y-m-d')."','".$orderNumber."','".$rows['cName']."')";
                $sqlAddOrders = mysqli_query($segev, $insertOrders);

                $currentStockString = "SELECT bStock as 'stock' from books Where bId = ".$rows['cId']."";
                $currentStock = mysqli_query($segev, $currentStockString);
                $currentStock = mysqli_fetch_array($currentStock); 
                $currentStock = intval($currentStock['stock']);
                $currentStock = $currentStock-1;
                $minusFromStockString = "UPDATE books SET bStock = '".$currentStock."' WHERE bId = ".$rows['cId']."";
                $minusFromStock = mysqli_query($segev, $minusFromStockString);
            }

            //Clear Cart
            $clearCartString = "DELETE FROM cart";
            $clearCart = mysqli_query($segev, $clearCartString);
            echo '<script>alert("Your order has been launched!");</script>';
            echo '<script>document.cookie = "order=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";</script>';
            echo '<script>location.href = "homePage.php";</script>';
        }

        else{
            echo '<script>document.cookie = "order=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";</script>';
            echo '<script>location.href = "cart.php";</script>';
        }
    }
?>