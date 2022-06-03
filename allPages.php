<html lang="en">
    <head>
        <!-- Books by genre DropBar JS -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        $(document).ready(function () {
        $('.navbar-light .dmenu').hover(function () {
        $(this).find('.sm-menu').first().stop(true, true).slideDown(150);
        }, function () {
        $(this).find('.sm-menu').first().stop(true, true).slideUp(105)
        });
        });

        ;
        function disableBack() {
            let x = document.cookie
            if(!(x.includes("type=user") || x.includes("type=Manager"))){
            window.history.forward();}}
        setTimeout("disableBack()", 0);
        window.onunload = function () { null };
        
        </script>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="css/homePage.css?v=1">
        <link rel="stylesheet" href="css/Design.css?v=2">

        
    <?php

        //Connection to server
        $segev=mysqli_connect("localhost","root","","singelcon");
        if (mysqli_connect_errno())
        {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        // Check if user logged in 
        if ((!isset($_COOKIE['type']))){
            echo '<script>alert("Unable to log in, you need to log in first")</script>
            <script> window.location.href = "login.php"</script>';
        }


            /* Tests the type of user,
           and defines a variable that determines whether to display things or not */
            if($_COOKIE['type']=='User'){
                $hide = "hide";
            }
            else{
                $hide = "";
            }

            /*Checking of the selected page in NAVBAR and its highlighting.*/
            $active = $_SERVER['REQUEST_URI'];
            $active_home = $active_about = $active_ganre = $active_addbook = $active_cart = $active_addUser ='none';
            switch($active){
                case '/PHPVisualStudioCode/project/cart.php':
                    $active_cart = "active";
                    break;
                case '/PHPVisualStudioCode/project/about.php':
                    $active_about = "active";
                    break;
                case '/PHPVisualStudioCode/project/AddBook.php':
                    $active_addbook = "active";
                    break;
                case '/PHPVisualStudioCode/project/homePage.php':
                    if($_COOKIE['genre'] == 'all'){
                        $active_home = "active";
                    }
                    else{
                        $active_ganre = "active";
                    }
                    break;
                case '/PHPVisualStudioCode/project/addUser.php':
                    $active_addUser = "active";
                    break;
                default:
                    $active_home = "active";
            }

            /*If there are items in the cart, update the circle in the number of items. If not, the circle will not appear.*/
            $countString = 'SELECT COUNT(*) as num from `cart`';
            $sqlCount = mysqli_query($segev, $countString);
            $count = mysqli_fetch_assoc($sqlCount);
            if($count['num']==0){
                $circleActive = "hide";
            }
            else{
                $circleActive = "";
            }

    ?>
    
    </head>

    <body>
        

        
        <!-- NavBar (Every page) -->
        <nav class="navbar navbar-expand-sm   navbar-light bg-light" >
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a title="Home Page, All Books" class="nav-link <?php echo $active_home ?>" href="homePage.php" id="all" onclick="return genre(id);">Home Page <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown dmenu">
                <a class="nav-link dropdown-toggle <?php echo $active_ganre ?>" href="#" id="navbardrop" data-toggle="dropdown">
                    Books by genre
                </a>
                <div class="dropdown-menu sm-menu">
                <a title="Drama" class="dropdown-item" href="homePage.php" id="Drama" onclick="return genre(id);">Drama</a>
                <a title="Psychology" class="dropdown-item" href="homePage.php" id="Psychology" onclick="return genre(id);">Psychology</a>
                <a title="Science-Fiction" class="dropdown-item" href="homePage.php" id="Science-Fiction" onclick="return genre(id);">Science Fiction</a>
                <a title="Action" class="dropdown-item" href="homePage.php" id="Action" onclick="return genre(id);">Action</a>
                </div>
            </li>
                <li class="nav-item">
                <a title="About" class="nav-link <?php echo $active_about ?>" href="about.php">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_cart ?>" href="cart.php" title="To The Cart">Cart 
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                <span class='badge badge-warning <?php  echo $circleActive ?>' id='lblCartCount'> <?php echo $count['num'] ?> </span>
                </a>
            </li>
            <li class="nav-item"  >
                <a class="nav-link <?php echo $hide ?> <?php echo $active_addbook ?>" href="AddBook.php" title="Add a new book" >Add a book <i class="fa fa-plus" aria-hidden="true"></i> </a>
            </li>
            <li class="nav-item"  >
                <a class="nav-link <?php echo $hide ?> <?php echo $active_addUser ?>" href="addUser.php" title="Add a new user" >Add User <i class="fa fa-plus" aria-hidden="true"></i> </a>
            </li>
            <li class="nav-item"  >
                <a class="nav-link <?php echo $hide ?>" href="ordersReportPDF.php" title="Download Orders report" >Orders Report <i class="fa fa-file" aria-hidden="true"></i> </a>
            </li>
            </ul>
            <div class="social-part">
            <a class="helloUser" href="userOptions.php" title="User Options" >hello <?php echo $_COOKIE['username']; ?></a>
                <a class="fa fa-facebook" href="https://www.facebook.com/segev.grotas/" aria-hidden="true" title="facebook"></a>
                <a class="fa fa-envelope" href = "mailto: segev_gr@walla.co.il" aria-hidden="true" title="Send me an email"></a>
                <a class="fa fa-instagram" href="https://www.instagram.com/segev_gr/" aria-hidden="true" title="instagram"></a>
                <a class="fa fa-sign-out" href="login.php" aria-hidden="true" title="log out" name = "logout" onclick="return logout();" ></a>
            </div>
            </div>
        </nav>
        <!-- End of Dropbar -->


        <!-- Start Landing Section -->
        <div class="landing">
        <div class="intro-text">
            <h1><strong>Segev's bookstore</strong></h1><br>
            <h2><strong>The place where it all started</strong></h2>
        </div>
        </div>
        <!-- End Landing Section -->
    </body>     
    
</html>

<script type="text/javascript">

//Deleting the user connection cookie
function logout()
{
    alert("loging out...");
    document.cookie = "type=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
    document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
}

//Set a genre cookie
function genre(id)
{
    document.cookie = "genre="+id+"; expires=Thu, 18 Dec 2025 12:00:00 UTC";
    return true;
}

</script>


