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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link href='https://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/products.css">
    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
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
                <a class="page-links active">Products</a>
                <a href="../stores/" class="page-links">Stores</a>
                <a href="../categories/" class="page-links">Categories</a>
                <a href="../locations/" class="page-links">Locations</a>
                <a href="../orders/" class="page-links">Orders</a>
                <a href="../customers/" class="page-links">Customers</a>
            </div>
        </header>
        <aside>
            <h5>Dashboard</h5>
            <div class="dashboard-page-link dpl-active"><a><i class="fa fa-product-hunt"></i>Products</a><span
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
            <h5 class="contents-header">Products</h5>

            <?php
if (isset($_GET['pid'])) {
    if ($_GET['pid'] != "") {
        echo '<input type="hidden" value="' . $_GET['pid'] . '" id="get-product-id">';
    } else {
        echo '<input type="hidden" value="" id="get-product-id">';

    }
} else {
    echo '<input type="hidden" value="" id="get-product-id">';
}
?>


            <div class="contents-holder">
                <div class="list-products">
                    <input type="button" value="Add" style="float: right;" class="btn btn-success add-btn" />
                    <table class="table table-striped table-hover table-bordered products-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>store</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody class="products-tbody">


                        </tbody>
                    </table>
                </div>

                <div class="view-product">
                    <input type="button" value="Edit" class="btn btn-primary edit-btn edit-product-btn" />
                    <input type="button" style="float: right;" value="Cancel" class="btn btn-secondary cancel-btn" />
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>store</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody class="product-details">


                        </tbody>
                    </table>

                    <div class="show-product-images">

                    </div>
                    <div class="product-description">


                    </div>
                    <div class="product-rating">
                        <h4>Ratings</h4>
                        <span style="padding:10px"><i class="fa fa-heart"></i><span class="likes"></span></span>
                        <span style="padding:10px"><i class="fas fa-heart-broken"></i><span
                                class="dislikes"></span></span>
                        <span style="padding:10px"><i class="fa fa-eye"></i><span class="views"></span></span>

                    </div>
                    <div class="product-reviews">
                        <h4>Reviews</h4>
                        <div class="product-review">
                            <table class="table table-striped table-hover table-bordered reviews-table">

                                <thead>

                                    <tr>
                                        <th>Username</th>
                                        <th>Review</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody class="reviews-tbody">

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="add-product">
                    <input type="button" style="float: right;" value="Cancel" class="btn btn-secondary cancel-btn" />
                    <form action="" id="add-product-form" method="post" enctype="multipart/form-data">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Store</th>
                                    <th>Category</th>
                                    <th>Price(ksh)</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="form-group">
                                        <input type="text" name="productName" id="add-product-name"
                                            placeholder="  product name" class="form-control">
                                    </td>
                                    <td class="form-group">
                                        <select name="storeID" id="add-product-store" class="form-control">

                                        </select>
                                    </td>
                                    <td class="form-group">
                                        <select name="categoryID" id="add-product-category" class="form-control">

                                        </select>
                                    </td>
                                    <td class="form-group">
                                        <input type="text" name="price" id="add-product-price"
                                            placeholder="  product price" class="form-control">
                                    </td>
                                    <td class="form-group">
                                        <input type="text" name="stock" id="add-product-stock"
                                            placeholder="  product stock" class="form-control">
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                        <h4>Images</h4>
                        <div class="edit-product-images"></div>
                        <div class="images-error"></div>

                        <h4>Add images</h4>
                        <div class="add-product-images">
                            <div class="product-image">
                                <div class="form-group">
                                    <input type="file" name="image1" accept="image/*"
                                        onChange="App.validateImage(this,this.value)" id="add-product-image-1"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="product-image">
                                <div class="form-group">
                                    <input type="file" name="image2" accept="image/*"
                                        onChange="App.validateImage(this,this.value)" id="add-product-image-2"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="product-image">
                                <div class="form-group">
                                    <input type="file" name="image3" accept="image/*"
                                        onChange="App.validateImage(this,this.value)" id="add-product-image-3"
                                        class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="add-product-description">
                            <h4>Description</h4>
                            <div class="form-group">
                                <textarea name="p_description" id="add-product-description" cols="30" rows="10"
                                    placeholder="product description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="add-result"></div>

                        <div class="form-group">
                            <input type="submit" value="Add" id="add-product-btn" class="btn btn-success">
                            <input type="submit" value="Update" id="update-product-btn" class="btn btn-success"
                                style="display:none">
                        </div>
                    </form>
                </div>


            </div>


            <div class="demo-div"></div>
        </div>
    </div>
    <footer>
        <p>MySuper &copy;2019 - <?php echo date("Y");?>. All Rights Reserved.</p>
    </footer>
    </div>

    <script>
    CKEDITOR.replace("p_description");
    </script>
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="../js/main.js"></script>
    <script src="../js/products.js"></script>
</body>

</html>