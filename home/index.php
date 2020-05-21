<?php session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
 <link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500&display=swap" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Indie+Flower|Oswald:300,400,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css" />
    <link rel="stylesheet" href="../css/dropdown.css">
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/products.css">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="../css/scrollBtn.css">
    <link rel="stylesheet" href="../css/checkout.css">
    <link rel="stylesheet" href="../css/my_orders.css">

    <script src="../js/jquery.js"></script>

</head>

<body>
    <span id="toTop" style="display: none;"></span>
    <header>
        <nav id="nav-bar">
            <a href="../" style="text-decoration: none;">
                <h2 class="logo">My<span style="color:#fff;">Super</span></h2>
            </a>

            <?php
if (isset($_SESSION['mysuper_username']) || isset($_COOKIE['mysuper_username'])) {
    if (!isset($_SESSION['mysuper_username']) && isset($_COOKIE['mysuper_username']) && isset($_COOKIE['mysuper_customerID'])) {
        $_SESSION['mysuper_username'] = $_COOKIE['mysuper_username'];
        $_SESSION['mysuper_customerID'] = $_COOKIE['mysuper_customerID'];
    }
    $customerID = $_SESSION['mysuper_customerID'];
    $username = $_SESSION['mysuper_username'];
    ?>
            <div class="dropdown">
                <input type="hidden" id="customerID" value="<?php echo $customerID ?>">
                <i class="fas fa-user dropbtn"></i>
                <div id="dropdown-content" class="dropdown-content">
                    <a href="#" id="show_username"><?php echo $username ?>
                    </a>
                    <a href="#" class="my-orders-btn">My Orders</a>

                    <a href="#" id="logout-btn">Logout</a>
                </div>
            </div>
            <?php } else {?>
            <div class="dropdown">
                <input type="hidden" id="customerID" value="">
                <i class="fas fa-bars dropbtn"></i>
                <div id="dropdown-content" class="dropdown-content">
                    <a href="../login/">Login</a>

                    <a href="../register/">Sign Up</a>
                </div>
            </div>
            <?php }?>
        </nav>
        <?php
if (isset($_GET['store'])) {
    $storeName = $_GET['store'];
    echo '<input type="hidden" id="storeName" value="' . $storeName . '">';
} else {
    echo '<input type="hidden" id="storeName" value="">';
}
if (isset($_GET['location'])) {
    $locationName = $_GET['location'];
    echo '<input type="hidden" id="locationName" value="' . $locationName . '">';
} else {
    echo '<input type="hidden" id="locationName" value="">';
}

?>

        <div class="search_cart">
            <button type="button" id="show_search"><i class="fas fa-search"></i></button>
            <div class="search_inputs">

                <form action="" id="search_form">
                    <input type="search" name="search" id="search">
                    <button type="submit" id="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="cart-items"><i class="fas fa-shopping-cart   cart-btn"></i> <span class="total-items">0</span>
            </div>
        </div>
    </header>

    <div class="categories">
        <div class="category-list">
        </div>
        <div class="category-dropdown">
            <span class="category-dropbtn">Categories <i class="fas fa-caret-down"></i></span>
            <div class="cat-dropdown-content">
            </div>
        </div>
    </div>
    <!-- Products -->
    <section>
        <div class="section-title">
            <h3 class="product-category">All products</h3>
        </div>
        <div class="products">

        </div>
    </section>

    <!--End of products-->

    <!--Footer-->
    <footer id="footer">
        <section class="top-bar">
            <a href="../contact/">Contact us</a>
        </section>
        <section class="middle-bar">
            <a href="https://facebook.com/evans.dranoel" target="_black"><i class="fa fa-facebook"></i></a>
            <a href="https://twitter.com" target="_black"><i class="fa fa-twitter"></i></a>
            <a href="https://github.com/leonardevans" target="_black"><i class="fa fa-github"></i></a>
        </section>
        <section class="bottom-bar">
            <p>MySuper &copy;2019 - <?php echo date("Y");?>. All Rights Reserved.</p>
        </section>
    </footer>
    <!--End of footer-->

    <!-- cart code-->
    <?php
require '../cart/cart.php';
?>
    <!-- end of cart code-->

    <!--checkout code -->
    <?php
require '../checkout/checkout.php';
?>
    <!--end of checkout code -->

    <!--modal code-->
    <div class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close-btn">&times;</span>
                <br>
            </div>

            <div class="modal-body">
            </div>
            <div class="modal-footer">

                <button type=button class="cancel-btn btn">Cancel</button>
                <a href="../login/" class="sign-in-btn btn">Sign In</a>
            </div>

        </div>
    </div>
    <!--end of modal code-->


    <!--orders code -->
    <?php
require '../my_orders/my_orders.php';
?>
    <!--end of orders code -->

    <span id="scrollTop"><i class="fas fa-chevron-up"></i></span>

    <script src="../js/main.js"></script>
    <script src="../js/home.js"></script>
    <script src="../js/search.js"></script>
    <script src="../js/app.js"></script>
    <script src="../js/checkout.js"></script>
    <script src="../js/my_orders.js"></script>
    <script src="../js/scrollBtn.js"></script>
</body>

</html>