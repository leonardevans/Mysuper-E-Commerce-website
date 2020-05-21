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
    <link rel="stylesheet" href="../css/categories.css">

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
                <a class="page-links active">Categories</a>
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
            <div class="dashboard-page-link dpl-active"><a><i class="fa fa-list-alt"></i>Categories</a><span
                    class="total-categories">60</span></div>
            <div class="dashboard-page-link"><a href="../locations/"><i class="fa fa-map-marker"></i>Locations</a><span
                    class="total-locations">12</span></div>
            <div class="dashboard-page-link"><a href="../orders/"><i class="fa fa-first-order"></i>Orders</a><span
                    class="total-orders">46</span></div>
            <div class="dashboard-page-link"><a href="../customers/"><i class="fa fa-users"></i>Customers</a><span
                    class="total-customers">346</span></div>
        </aside>
        <div class="contents">
            <h5 class="contents-header">Categories</h5>
            <div class="contents-holder">

                <div class="list-categories">
                    <input type="button" style="float: right;" value="Add New" class="btn btn-success" id="add-btn" />
                    <table class="table table-striped table-hover table-bordered categories-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody class="categories-tbody">

                        </tbody>
                    </table>
                </div>
                <div class="add-category">
                    <input type="button" style="float: right;" value="Cancel" class="btn btn-secondary cancel-btn" />
                    <div class="add-result"></div>
                    <form action="" id="addForm">
                        <div class="form-group">
                            <input type="text" name="category" id="category-name" placeholder="CATEGORY NAME"
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <input type="button" value="Add" id="add-category" class="btn btn-success">
                            <input type="button" value="Update" id="update-category" class="btn btn-success"
                                style="display:none">
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

    <script src="../js/categories.js"></script>
</body>

</html>