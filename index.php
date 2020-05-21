<?php session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Navigation</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css" />
    <link rel="stylesheet" href="./css/dataTables.bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
   <link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500&display=swap" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css?family=Indie+Flower|Oswald:300,400,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/welcome.css" />
    <link rel="stylesheet" href="./css/dropdown.css" />
    <link rel="stylesheet" href="./css/scrollBtn.css" />
    <link rel="stylesheet" href="./css/scrollBtn.css" />
</head>

<body>
    <span id="toTop" style="display: none;"></span>
    <nav id="nav-bar">
        <a href="./" style="text-decoration: none;">
            <h2 class="logo">My<span style="color:#fff;">Super</span></h2>
        </a>
        <?php
if (isset($_SESSION['mysuper_username']) || isset($_COOKIE['mysuper_username'])) {
    if (!isset($_SESSION['mysuper_username']) && isset($_COOKIE['mysuper_username'])) {
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
                <!-- <a href="#">My profile</a> -->

                <a href="#" id="logout-btn">Logout</a>
            </div>
        </div>
        <?php } else {?>
        <div class="dropdown">
            <input type="hidden" id="customerID" value="">
            <i class="fas fa-bars dropbtn"></i>
            <div id="dropdown-content" class="dropdown-content">
                <a href="./login/">Login</a>

                <a href="./register/">Sign Up</a>
            </div>
        </div>
        <?php }?>
    </nav>
    <header id="welcome">
        <div class="ban">
            <h1 class="ban-title">My Super Store</h1>
            <button class="ban-btn">SHOP NOW</button>
        </div>
    </header>
    <section class="supermarkets">
        <div>
            <h4>Available stores</h4>
            <table class="table table-striped table-hover table-bordered list-supermarkets">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody id="list-supermarkets-tbody"></tbody>
            </table>
        </div>
    </section>
    <!--Footer-->
    <footer id="footer">
        <section class="top-bar">
            <a href="./contact/">Contact us</a>
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
    <div class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close-btn">&times;</span>
                <h4>Select where to shop</h4>
            </div>
            <form action="" id="modal-form">
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <a href="./home/" class="skip btn">Skip</a>
                    <button type="submit" class="go-shop btn">Go shop</button>
                </div>
            </form>
        </div>
    </div>
    <span id="scrollTop"><i class="fas fa-chevron-up"></i></span>

    <script src="./js/jquery.js"></script>

    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="./js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="./js/welcome.js"></script>
    <script src="./js/scrollBtn.js"></script>
</body>

</html>