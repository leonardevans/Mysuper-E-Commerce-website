<?php
require './db_connect.php';
if (isset($_POST['key'])) {
    if ($_POST['key'] == "product_details") {
        $productID = $_POST['productID'];
        $customerID = $_POST['customerID'];
        $query = "SELECT  product.productID,product.productName,product.price,product.description,store.storeName,locations.locationName,category.categoryName FROM (((product INNER JOIN category ON product.categoryID = category.categoryID)INNER JOIN store ON product.storeID = store.storeID)INNER JOIN locations ON store.locationID = locations.locationID) WHERE product.productID=$productID 
    AND product.stock > 0";
        $output = array("productDetails" => '', 'description' => '', 'reviews' => '');
        try {
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $statement = $connect->prepare($query);
            if ($statement->execute()) {
                $products = $statement->fetchAll();
                if (count($products) > 0) {
                    foreach ($products as $result) {
                        //query to get images for this product
                        $query1 = "SELECT * FROM images WHERE productID=$productID";
                        $statement1 = $connect->prepare($query1);
                        $imagesArray = '[';
                        if ($statement1->execute()) {
                            $result1 = $statement1->fetchAll();
                            $image = $result1[0]['name'];
                            $totalImages = count($result1);
                            for ($x = 0; $x < $totalImages; $x++) {
                                $imagesArray .= '"' . $result1[$x]['name'] . '"';
                                if ($x != count($result1) - 1) {
                                    $imagesArray .= ',';
                                }

                            }
                            $imagesArray .= ']';
                        }
                        //end of block to get images

                        //get button class
                        $likeBtnClass = btnClass($customerID, $productID, "like");
                        $dislikeBtnClass = btnClass($customerID, $productID, "dislike");
                        //update views for this product
                        $query9 = "SELECT * FROM views WHERE productID='$productID' AND customerID='$customerID'";
                        $statement9 = $connect->prepare($query9);
                        if ($statement9->execute()) {
                            $result8 = $statement9->fetchAll();
                            if (count($result8) > 0) {

                                $query10 = "UPDATE views SET `viewTimes`=`viewTimes`+1  WHERE customerID=$customerID AND productID=$productID";
                                $addView = $connect->prepare($query10);
                                $addView->execute();
                            } else {
                                $query11 = "INSERT INTO views (productID,customerID,viewTimes) VALUES ($productID,$customerID,1)";
                                $addView1 = $connect->prepare($query11);
                                $addView1->execute();
                            }

                        }
                        //end of update views
                        //get the likes
                        $query2 = "SELECT COUNT(*) FROM rating WHERE productID=$productID AND reviewAction='like'";
                        $statement2 = $connect->prepare($query2);
                        $statement2->execute();
                        $likes = 0;
                        $likes += $statement2->fetchColumn();
                        //end of get likes block
                        //get the dislikes
                        $query3 = "SELECT COUNT(*) FROM rating WHERE productID=$productID AND reviewAction='dislike'";
                        $statement3 = $connect->prepare($query3);
                        $statement3->execute();
                        $dislikes = 0;
                        $dislikes += $statement3->fetchColumn();
                        //end of get dislikes block
                        //start get views block
                        $query4 = "SELECT SUM(viewTimes) FROM views WHERE productID=$productID";
                        $statement4 = $connect->prepare($query4);
                        $statement4->execute();
                        $views = 0;
                        $views += $statement4->fetchColumn();
                        //end of get views

                        $output['productDetails'] = '
                    <div class="image-container">
                    <i class="fa fa-chevron-left" onclick="changeIndex(`prev`)"></i>
                    <span class="total-images"><span class="index">1</span>/' . $totalImages . '</span>
                        <img src="' . $image . '" alt="' . $result["productName"] . '" class="product-img" id="product-images">
                        <i class="fa fa-chevron-right" onclick="changeIndex(`next`)"></i>
                    </div>

                    <div class="rating">
                    <div style="max-width:400px">
                        <span class="views-btn"><i class="fas fa-eye"></i> <span class="views">' . $views . '</span></span>
                        <span class="like-btn "><i class="fas fa-heart  ' . $likeBtnClass . '"></i> <span class="likes">' . $likes . '</span></span>

                        <span class="dislike-btn"><i class="fas fa-heart-broken ' . $dislikeBtnClass . '"></i> <span
                                class="dislikes">' . $dislikes . '</span></span>
                                </div>
                    </div>

                    <div class="description" id="view-desc">

                        <h4>' . $result['productName'] . '</h4>
                        <h3>ksh. ' . number_format($result['price']) . '</h3>
                        <h4>Store: <span class="store-name">' . $result['storeName'] . '</span></h4>
                        <h4>Location: <span class="location-name">' . $result['locationName'] . '</span></h4>

                    </div>
                    <button class="add-to-btn" id="add-btn" data-id="' . $result['productID'] . '"><i class="fas fa-shopping-cart"></i>Add to cart</button>
                ';
                        $output['description'] = '
                <h4>Description</h4>
                <input type="hidden" id="category" value="' . $result['categoryName'] . '">
                <p>' . $result['description'] . '</p><p style="display:none" id="imagesArray">' . $imagesArray . '</p>';

                        //get reviews
                        $query5 = "SELECT customer.username,reviews.review,reviews.reviewTime FROM reviews INNER JOIN customer ON customer.customerID=reviews.customerID WHERE productID=" . $result['productID'] . " ORDER BY reviewTime DESC";
                        $statement5 = $connect->prepare($query5);
                        if ($statement5->execute()) {
                            $result5 = $statement5->fetchAll();
                            $reviews = '';

                            if (count($result5) > 0) {
                                foreach ($result5 as $review) {
                                    $output['reviews'] .= '
                            <div class="review">
                            <h4>@' . $review['username'] . '</h4>
                            <h5>' . $review['reviewTime'] . '</h5>
                            <p>' . $review['review'] . '
                            </p>
                        </div>';
                                }
                            } else {
                                $output['reviews'] = '<div class="review"><p>No reviews for this product</p></div>';
                            }

                        }
                        //end of get reviews block
                    }
                    echo json_encode($output);

                } else {
                    echo $output = 'No_such_product';

                }
            }

        } catch (PDPException $e) {
            echo '<div class="errors">' . $e->getMessage() . '</div>';

        }

    } elseif ($_POST['key'] == "related") {
        $productID = $_POST['productID'];
        $query6 = "SELECT category.categoryName,locations.locationName,store.storeName FROM (((product INNER JOIN category ON product.categoryID = category.categoryID)INNER JOIN store ON product.storeID = store.storeID)INNER JOIN locations ON store.locationID = locations.locationID) WHERE productID=$productID";

        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement6 = $connect->prepare($query6);
        $statement6->execute();
        $result6 = $statement6->fetchAll();
        foreach ($result6 as $row6) {
            $location = $row6['locationName'];
            $store = $row6['storeName'];
            $category = $row6['categoryName'];
        }

        $query = "SELECT product.productID,product.productName,product.price,store.storeName,locations.locationName,category.categoryName FROM (((product INNER JOIN category ON product.categoryID = category.categoryID)INNER JOIN store ON product.storeID = store.storeID)INNER JOIN locations ON store.locationID = locations.locationID) WHERE (productID!='$productID' AND( locationName LIKE '%$location%' OR storeName LIKE '%$store%' OR categoryName LIKE '%$category%' )) AND product.stock > 0 ORDER BY RAND() limit 16";
        try {

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
                        <a href="./?pid=' . $row['productID'] . '" class="view-btn">View Item<i class="fas fa-eye"></i></a>
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
                    $output .= '<div class="errors">No related products</div>';

                }
            }
        } catch (PDOException $e) {
            $output .= '<div class="errors">' . $e->getMessage() . '</div>';

        }
        echo $output;

    } elseif ($_POST['key'] == "makeAReview") {
        $customerID = $_POST['customerID'];
        $productID = $_POST['productID'];
        $review = $_POST['review'];
        $query7 = "INSERT INTO reviews (customerID,productID,review) VALUES ('$customerID','$productID','$review')";

        try {
            $reviews2 = '';

            $statement7 = $connect->prepare($query7);
            if ($statement7->execute()) {
                $query8 = "SELECT customer.username,reviews.review,reviews.reviewTime FROM reviews INNER JOIN customer ON customer.customerID=reviews.customerID WHERE productID='" . $productID . "' ORDER BY reviewTime DESC";
                $statement8 = $connect->prepare($query8);
                if ($statement8->execute()) {

                    $result7 = $statement8->fetchAll();

                    if (count($result7) > 0) {
                        foreach ($result7 as $review1) {
                            $reviews2 .= '
                                                <div class="review">
                                                <h4>@' . $review1['username'] . '</h4>
                                                <h5>' . $review1['reviewTime'] . '</h5>
                                                <p>' . $review1['review'] . '
                                                </p>
                                            </div>';
                        }

                    } else {
                        $reviews2 .= '<div class="review"><p>No reviews for this product</p></div>';
                    }

                } else {
                    echo "error";
                }

            }
            echo $reviews2;

        } catch (PDOException $e) {
            echo '<div class="errors">' . $e->getMessage() . '</div>';

        }
    } elseif ($_POST['key'] == "recentlyViewed") {
        $jsonData = $_POST['jsonData'];
        $customerID = $jsonData['customerID'];
        $productID = $jsonData['productID'];
        $recentProductsIDs = $jsonData['recentProducts'];

        $productsQuery = '';
        $output = "";
        try {
            //get products from  views table in database
            if ($customerID != 1) {
                $query = "SELECT productID FROM views WHERE customerID='$customerID' AND productID!='$productID' ORDER BY lastViewTime limit 10";
                $statement = $connect->prepare($query);
                if ($statement->execute()) {
                    $result = $statement->fetchAll();
                    if (count($result) > 0) {
                        foreach ($result as $row) {
                            if (!in_array($row['productID'], $recentProductsIDs)) {
                                $recentProductsIDs[count($recentProductsIDs)] = $row['productID'];
                            }

                        }
                    }
                }
            }

            //end of getting products fro views table in database
            //get products from localstorage
            $arrayLength = count($recentProductsIDs);

            for ($x = 0; $x < $arrayLength; $x++) {
                if ($recentProductsIDs[$x] != $productID) {
                    $productsQuery = "SELECT product.productID,product.productName,product.price,store.storeName,locations.locationName,category.categoryName FROM (((product INNER JOIN category ON product.categoryID = category.categoryID)INNER JOIN store ON product.storeID = store.storeID)INNER JOIN locations ON store.locationID = locations.locationID) WHERE productID='$recentProductsIDs[$x]' AND product.stock > 0";
                    $output .= fetchRelated($productsQuery);
                }
            }

            //end of gettting products from local storage
            if (!empty($output)) {
                echo $output;
            } else {
                echo '<div class="errors">You have no recently viewed products</div>';
            }

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

function fetchRelated($productsQuery)
{
    global $connect;
    $output = '';
    $statement = $connect->prepare($productsQuery);
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
                        <a href="./?pid=' . $row['productID'] . '" class="view-btn">View Item<i class="fas fa-eye"></i></a>
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
        }

    }
    return $output;

}

function btnClass($customerID, $productID, $action)
{
    global $connect;
    if ($customerID != 1) {
        if ($action == "like") {
            $query = "SELECT * FROM rating WHERE productID='$productID' AND customerID='$customerID' AND reviewAction='$action'";
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            if (count($result) > 0) {
                return "actioned";
            } else {
                return "";
            }
        } elseif ($action == "dislike") {
            $query = "SELECT * FROM rating WHERE productID='$productID' AND customerID='$customerID' AND reviewAction='$action'";
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            if (count($result) > 0) {
                return "actioned";
            } else {
                return "";
            }

        }
    } else {
        return "";
    }
}