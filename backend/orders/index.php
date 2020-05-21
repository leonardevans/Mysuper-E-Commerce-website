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
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link href='https://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/orders.css">
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
                <a href="../home/" class="page-links">Dashboard</a>
                <a href="../products/" class="page-links">Products</a>
                <a href="../stores/" class="page-links">Stores</a>
                <a href="../categories/" class="page-links">Categories</a>
                <a href="../locations/" class="page-links">Locations</a>
                <a class="page-links active">Orders</a>
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
            <div class="dashboard-page-link dpl-active"><a><i class="fa fa-first-order"></i>Orders</a><span
                    class="total-orders">46</span></div>
            <div class="dashboard-page-link"><a href="../customers/"><i class="fa fa-users"></i>Customers</a><span
                    class="total-customers">346</span></div>
        </aside>
        <div class="contents">
            <h5 class="contents-header">Orders</h5>

            <?php
if (isset($_GET['oid'])) {
    if ($_GET['oid'] != "") {
        echo '<input type="hidden" value="' . $_GET['oid'] . '" id="get-order-id">';
    } else {
        echo '<input type="hidden" value="" id="get-order-id">';

    }
} else {
    echo '<input type="hidden" value="" id="get-order-id">';
}
?>
            <div class="contents-holder">
                <div class="list-orders">
                    <table class="table table-striped table-hover table-bordered orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Username</th>
                                <th>Order Total</th>
                                <th>Status</th>
                                <th>date</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody class="orders-tbody">
                        </tbody>
                    </table>
                </div>

                <div class="view-order">
                    <input type="button" style="float: right;" value="Cancel" class="btn btn-secondary cancel-btn" />
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Username</th>
                                <th>Order Total</th>
                                <th>Status</th>
                                <th>date</th>

                            </tr>
                        </thead>
                        <tbody class='order-headers'>


                        </tbody>
                    </table>
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Delivery Method</th>
                                <th>Payment Method</th>
                                <th>Shopping Fee</th>
                                <th>Shipping Fee</th>
                            </tr>
                        </thead>
                        <tbody class="order-details">


                        </tbody>
                    </table>
                    <div class="address">
                        <h4>Address</h4>
                        <table class="table table-striped table-hover table-bordered">
                            <thead>

                                <tr>
                                    <th>Contact Phone</th>
                                    <th>City</th>
                                    <th>Street</th>
                                    <th>House</th>
                                </tr>
                            </thead>
                            <tbody class='order-address'>


                            </tbody>
                        </table>
                    </div>
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <!-- <tr>
                            <th colspan="7">Order Items</th>
                        </tr> -->
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Store</th>
                                <th>Location</th>
                                <th>Price</th>
                                <th>Items</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class='order-items'>


                        </tbody>
                    </table>
                    <form action="" id="update-order-form">
                        <div class="update-result"></div>
                        <div class="form-group">
                            <label for="order-statuses">Order status</label>
                            <select name="order-statuses" id="order-statuses" class="form-control">
                                <option value="pending">pending</option>
                                <option value="received">received</option>
                                <option value="processing">processing</option>
                                <option value="ready">ready</option>
                                <option value="shipping">shipping</option>
                                <option value="complete">complete</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="button" value="Update" class="btn btn-success" id="update-order">
                        </div>
                    </form>
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
    <script src="../js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="../js/main.js"></script>
    <script src="../js/orders.js"></script>
</body>

</html>