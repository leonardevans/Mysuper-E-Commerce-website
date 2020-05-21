<?php
require './db_connect.php';
if (isset($_POST['key'])) {
    if ($_POST['key'] == "loaded" || $_POST['key'] == "specific") {
        $query = '';
        if ($_POST['key'] == "loaded") {
            $query = "SELECT product.productID,product.productName,product.price,store.storeName,locations.locationName,category.categoryName FROM (((product INNER JOIN category ON product.categoryID = category.categoryID)INNER JOIN store ON product.storeID = store.storeID)INNER JOIN locations ON store.locationID = locations.locationID) WHERE product.stock >0 ORDER BY RAND() limit 16";
        } elseif ($_POST['key'] == "specific") {
            $storeName = $_POST['storeName'];
            $locationName = $_POST['locationName'];
            if ($storeName != "0" && $locationName != "0") {
                $query = "SELECT product.productID,product.productName,product.price,store.storeName,locations.locationName,category.categoryName FROM (((product INNER JOIN category ON product.categoryID = category.categoryID)INNER JOIN store ON product.storeID = store.storeID)INNER JOIN locations ON store.locationID = locations.locationID) WHERE locationName LIKE '%$locationName%' AND storeName LIKE '%$storeName%' AND stock > 0 ORDER BY RAND() limit 16";
            } elseif ($storeName == "0" && $locationName != "0") {
                $query = "SELECT product.productID,product.productName,product.price,store.storeName,locations.locationName,category.categoryName FROM (((product INNER JOIN category ON product.categoryID = category.categoryID)INNER JOIN store ON product.storeID = store.storeID)INNER JOIN locations ON store.locationID = locations.locationID) WHERE locationName LIKE '%$locationName%' AND stock > 0 ORDER BY RAND() limit 16";
            } elseif ($storeName != "0" && $locationName == "0") {
                $query = "SELECT product.productID,product.productName,product.price,store.storeName,locations.locationName,category.categoryName FROM (((product INNER JOIN category ON product.categoryID = category.categoryID)INNER JOIN store ON product.storeID = store.storeID)INNER JOIN locations ON store.locationID = locations.locationID) WHERE storeName LIKE '%$storeName%' AND stock > 0 ORDER BY RAND() limit 16";
            }

        }
        try {
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $statement = $connect->prepare($query);
            $output = "";
            if ($statement->execute()) {
                $result = $statement->fetchAll();
                if (count($result) > 0) {
                    foreach ($result as $row) {
                        $query1 = "SELECT * FROM images WHERE productID=" . $row['productID'] . "";
                        $statement1 = $connect->prepare($query1);
                        if ($statement1->execute()) {
                            $result1 = $statement1->fetchAll();
                            $image = $result1[0]['name'];

                        }
                        $output .= '
                                <!-- product -->
                                <div class="product">
                    <div class="image-container">
                        <img src="' . $image . '" alt="' . $row['productName'] . '" class="product-img">
                        <a href="../product/?pid=' . $row['productID'] . '" class="view-btn">View Item<i class="fas fa-eye"></i></a>
                        <button class="add-btn" data-id=' . $row['productID'] . '><i class="fas fa-shopping-cart"></i>Add to cart</button>
                    </div>
                    <div class="description">
                        <a href="../product/?pid=' . $row['productID'] . '">
                            <h4>' . $row['productName'] . '</h4>
                            <h3>Ksh. ' . number_format($row['price']) . '</h3>
                            <h4>Store: <span class="store-name">' . $row['storeName'] . '</span></h4>
                            <h4>Location: <span class="store-name">' . $row['locationName'] . '</span></h4>
                        </a>
                    </div>
                </div>
                <!-- End of product -->
                                ';

                    }
                } else {
                    if ($_POST['key'] == "specific") {
                        if ($storeName == "0") {
                            $storeName = "";
                        }
                        if ($locationName == "0") {
                            $locationName = "";
                        }

                        $output .= '<div class="errors">No products available in ' . $storeName . ' ' . $locationName . ' </div>';

                    } else {
                        $output .= '<div class="errors">No products available</div>';
                    }

                }
            }
        } catch (PDOException $e) {
            $output .= $output .= '<div class="errors">' . $e->getMessage() . '</div>';

        }
        echo $output;
    } elseif ($_POST['key'] == "search") {
        try {
            $search_value = $_POST['search_value'];
            $query = "SELECT  product.productID,product.productName,product.price,store.storeName,locations.locationName,category.categoryName FROM (((product INNER JOIN category ON product.categoryID = category.categoryID)INNER JOIN store ON product.storeID = store.storeID)INNER JOIN locations ON store.locationID = locations.locationID) WHERE (productName LIKE '%$search_value%' OR storeName LIKE '%$search_value%' OR categoryName LIKE '%$search_value%' OR locationName LIKE '%$search_value%') AND stock > 0  ORDER BY RAND() limit 16";
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $statement = $connect->prepare($query);
            $output = "";
            if ($statement->execute()) {
                $result = $statement->fetchAll();
                if (count($result) > 0) {
                    foreach ($result as $row) {
                        $query1 = "SELECT * FROM images WHERE productID=" . $row['productID'] . "";
                        $statement1 = $connect->prepare($query1);
                        if ($statement1->execute()) {
                            $result1 = $statement1->fetchAll();
                            $image = $result1[0]['name'];

                        }
                        $output .= '
                    <!-- product -->
                <div class="product">
                    <div class="image-container">
                        <img src="' . $image . '" alt="' . $row['productName'] . '" class="product-img">
                        <a href="../product/?pid=' . $row['productID'] . '" class="view-btn">View Item<i class="fas fa-eye"></i></a>
                        <button class="add-btn" data-id=' . $row['productID'] . '><i class="fas fa-shopping-cart"></i>Add to cart</button>
                    </div>
                    <div class="description">
                        <a href="../product/?pid=' . $row['productID'] . '">
                            <h4>' . $row['productName'] . '</h4>
                            <h3>Ksh. ' . number_format($row['price']) . '</h3>
                            <h4>Store: <span class="store-name">' . $row['storeName'] . '</span></h4>
                            <h4>Location: <span class="store-name">' . $row['locationName'] . '</span></h4>
                        </a>
                    </div>
                </div>
                <!-- End of product -->
                                ';
                    }
                } else {
                    $output .= '<div class="errors">No search results</div>';
                }
            }

        } catch (PDOException $e) {
            $output .= '<div class="errors">' . $e->getMessage() . '</div>';

        }
        echo $output;
    } elseif ($_POST['key'] == "categories") {
        $query = "SELECT * FROM category ORDER BY categoryName";
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $connect->prepare($query);
        $output = '<span>Categories &#8594 </span>';
        if ($statement->execute()) {
            $result = $statement->fetchAll();
            if (count($result) > 0) {
                foreach ($result as $row) {
                    $output .= '
                                <a href="#" class="category" data-id="' . $row['categoryID'] . '">' . $row['categoryName'] . '</a>|
                                ';
                }
            } else {
                $output .= 'No categories';
            }
        }
        echo $output;
    } elseif ($_POST['key'] == "category") {
        $categoryID = $_POST['categoryID'];
        $query = "SELECT  product.productID,product.productName,product.price,store.storeName,locations.locationName,category.categoryName FROM (((product INNER JOIN category ON product.categoryID = category.categoryID)INNER JOIN store ON product.storeID = store.storeID)INNER JOIN locations ON store.locationID = locations.locationID) WHERE (product.categoryID LIKE '%$categoryID%') AND stock > 0  ORDER BY RAND() limit 16";
        $output = "";

        try {
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $statement = $connect->prepare($query);

            if ($statement->execute()) {
                $result = $statement->fetchAll();
                if (count($result) > 0) {
                    foreach ($result as $row) {
                        $query1 = "SELECT * FROM images WHERE productID=" . $row['productID'] . "";
                        $statement1 = $connect->prepare($query1);
                        if ($statement1->execute()) {
                            $result1 = $statement1->fetchAll();
                            $image = $result1[0]['name'];

                        }
                        $output .= '
                    <!-- product -->
                <div class="product">
                    <div class="image-container">
                        <img src="' . $image . '" alt="' . $row['productName'] . '" class="product-img">
                        <a href="../product/?pid=' . $row['productID'] . '" class="view-btn">View Item<i class="fas fa-eye"></i></a>
                        <button class="add-btn" data-id=' . $row['productID'] . '><i class="fas fa-shopping-cart"></i>Add to cart</button>
                    </div>
                    <div class="description">
                        <a href="../product/?pid=' . $row['productID'] . '">
                            <h4>' . $row['productName'] . '</h4>
                            <h3>Ksh. ' . number_format($row['price']) . '</h3>
                            <h4>Store: <span class="store-name">' . $row['storeName'] . '</span></h4>
                            <h4>Location: <span class="store-name">' . $row['locationName'] . '</span></h4>
                        </a>
                    </div>
                </div>
                <!-- End of product -->
                                ';
                    }
                } else {
                    $output .= '<div class="errors">No  products in this category</div>';
                }
            }

        } catch (PDOException $e) {
            $output .= '<div class="errors">' . $e->getMessage() . '</div>';

        }
        echo $output;
    }

}