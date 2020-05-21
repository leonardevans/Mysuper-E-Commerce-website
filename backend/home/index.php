<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MySuper | Admin</title>
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link href='https://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/home.css">
</head>

<body>
    <div class="container-div">
        <header>
            <div class="nav-top">
                <div class="brand">
                    <h4 class="logo">My<span style="color:#fff;">Super</span></h4>
                </div>
                <div class="admin-logged">
                    <?php
if (isset($_SESSION['mysuper_admin_username'])) {
    $admin = $_SESSION['mysuper_admin_username'];
    echo '<span>Welcome Admin: <span class="username">' . $admin . '</span></span>
                    <span class="logout-btn" onclick="MainApp.logout()"> Logout</span>';
} else {
    header("location:../");
}
?>

                </div>
            </div>
            <div class="nav-bottom">
                <span><i class="fa fa-cog"></i></span>
                <a class="page-links active">Dashboard</a>
                <a href="../products/" class="page-links">Products</a>
                <a href="../stores/" class="page-links">Stores</a>
                <a href="../categories/" class="page-links">Categories</a>
                <a href="../locations/" class="page-links">Locations</a>
                <a href="../orders/" class="page-links">Orders</a>
                <a href="../customers/" class="page-links">Customers</a>
            </div>
        </header>
        <aside>
            <h5>Dashboard</h5>
            <div class="dashboard-page-link"><a href="../products/"><i class="fa fa-product-hunt"></i>Products</a><span
                    class="total-products">300</span></div>
            <div class="dashboard-page-link"><a href="../stores/"><i class="fa fa-building"></i>Stores</a><span
                    class="total-stores">30</span></div>
            <div class="dashboard-page-link"><a href="../categories/"><i class="fa fa-list-alt"></i>Categories</a><span
                    class="total-categories">60</span></div>
            <div class="dashboard-page-link"><a href="../locations/"><i class="fa fa-map-marker"></i>Locations</a><span
                    class="total-locations">12</span></div>
            <div class="dashboard-page-link"><a href="../orders/"><i class="fa fa-first-order"></i>Orders</a><span
                    class="total-orders">46</span></div>
            <div class="dashboard-page-link"><a href="../customers/"><i class="fa fa-users"></i>Customers</a><span
                    class="total-customers">346</span></div>
        </aside>
        <div class="contents">
            <h5 class="contents-header">My Super Store Overview</h5>
            <div class="contents-holder">

                <div class="dashboard-overview">
                    <div class="overview">
                        <h2><b><i class="fa fa-product-hunt"></i><span class="total-products">323</span></b></h2>
                        <h3>Products</h3>
                    </div>
                    <div class="overview">
                        <h2><b><i class="fa fa-building"></i><span class="total-stores">23</span></b></h2>
                        <h3>Stores</h3>
                    </div>
                    <div class="overview">
                        <h2><b><i class="fa fa-list-alt"></i><span class="total-categories">53</span></b></h2>
                        <h3>Categories</h3>
                    </div>
                    <div class="overview">
                        <h2><b><i class="fa fa-map-marker"></i><span class="total-locations">14</span></b></h2>
                        <h3>Locations</h3>
                    </div>
                    <div class="overview">
                        <h2><b><i class="fa fa-first-order"></i><span class="total-orders">245</span></b></h2>
                        <h3>Orders</h3>
                    </div>
                    <div class="overview">
                        <h2><b><i class="fa fa-users"></i><span class="total-customers">279</span></b></h2>
                        <h3>Customers</h3>
                    </div>
                </div>
                <div class="latest-orders">
                    <h5 class="l-orders-header">Latest orders</h5>
                    <div class="l-orders-div">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Username</th>
                                    <th>Order Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody class="latest-orders-tbody">

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="demo-div"></div>
            </div>
        </div>
        <footer>
            <p>MySuper &copy;2019 - <?php echo date("Y");?>. All Rights Reserved.</p>
        </footer>
    </div>

    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/home.js"></script>
</body>

</html>