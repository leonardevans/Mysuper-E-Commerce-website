<?php
require './db_connect.php';
if (isset($_POST['key'])) {
    $key = $_POST['key'];
    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($key == 'fetch_products') {
            $start = $_POST['start'];
            $limit = $_POST['limit'];
            $sql = "SELECT product.productID,product.productName,product.price,product.stock,store.storeName,category.categoryName FROM ((product INNER JOIN store ON product.storeID=store.storeID) INNER JOIN category ON product.categoryID=category.categoryID) ORDER BY productName LIMIT $start,$limit";
            $products = executer($sql);
            if (count($products) > 0) {
                $response = '';
                foreach ($products as $product) {
                    $sql = "SELECT * FROM images WHERE productID=" . $product['productID'] . "";
                    $images = executer($sql);
                    $image = substr_replace($images[0]['name'], '../..', 0, 2);

                    $response .= '
                    <tr>
                    <td id="product_' . $product['productID'] . '"><img src="' . $image . '" alt="image"></td>
                    <td>' . $product['productName'] . '</td>
                    <td>' . $product['storeName'] . '</td>
                    <td>' . $product['categoryName'] . '</td>
                    <td>Ksh. ' . number_format($product['price']) . '</td>
                    <td>' . $product['stock'] . '</td>
                    <td>
                    <input type="button" value="View" class="btn btn-secondary view-btn" onclick="App.viewProductDetails(`' . $product['productID'] . '`)"/>
                    <input type="button" value="Edit" class="btn btn-primary edit-btn" onclick="App.getProductDetails(`' . $product['productID'] . '`)" />
                    <input type="button" value="Delete" class="btn btn-danger delete-btn" onclick="App.deleteProduct(`' . $product['productID'] . '`)" />
                    </td>
                    </tr>
                    ';
                }
                exit($response);
            } else {
                exit('no_more');
            }
        } elseif ($key == 'view_product') {
            $productID = $_POST['id'];
            $sql = "SELECT product.productID,product.productName,product.description,product.date,product.price,product.stock,store.storeName,category.categoryName FROM ((product INNER JOIN store ON product.storeID=store.storeID) INNER JOIN category ON product.categoryID=category.categoryID) WHERE productID='$productID'";
            $products = executer($sql);
            if (count($products) > 0) {
                $product = $products[0];
                $productDetails = '
            <tr>
            <td>' . $product['productName'] . '</td>
            <td>' . $product['storeName'] . '</td>
            <td>' . $product['categoryName'] . '</td>
            <td>Ksh. ' . number_format($product['price']) . '</td>
            <td>' . $product['stock'] . '</td>
            <td>' . $product['date'] . '</td>
            </tr>
            ';
                //get product images
                $sql = "SELECT * FROM images WHERE productID=" . $product['productID'] . "";
                $images = executer($sql);
                $productImages = '';
                if (count($images) > 0) {
                    foreach ($images as $image) {
                        $productImage = substr_replace($image['name'], '../..', 0, 2);
                        $productImages .= '
                    <div class="product-image">
                            <img src="' . $productImage . '" alt="image">
                        </div>
                    ';
                    }
                } else {
                    $productImages .= '<p class="text-danger">No images for this product</p>';
                }
                // product description
                $description = '<h4>Description</h4>' . $product['description'];
                //product rating
                $sql = "SELECT * FROM rating WHERE productID='$productID' AND reviewAction='like'";
                $ratings = executer($sql);
                $likes = count($ratings);
                $sql = "SELECT * FROM rating WHERE productID='$productID' AND reviewAction='dislike'";
                $ratings = executer($sql);
                $dislikes = count($ratings);

                //get views
                $query4 = "SELECT SUM(viewTimes) FROM views WHERE productID=$productID";
                $statement4 = $connect->prepare($query4);
                $statement4->execute();
                $views = 0;
                $views += $statement4->fetchColumn();

                $response = array('productDetails' => $productDetails, 'likes' => $likes, 'dislikes' => $dislikes, 'description' => $description, 'images' => $productImages, 'views' => $views);
                exit(json_encode($response));

            } else {
                exit('not_found');
            }
        } elseif ($key == 'fetch_details') {
            $productID = $_POST['id'];
            $sql = "SELECT * FROM product WHERE productID='$productID'";
            $products = executer($sql);
            $product = $products[0];
            $productDetails = array('name' => $product['productName'], 'price' => $product['price'], 'stock' => $product['stock'], 'description' => $product['description']);
            $sql = "SELECT * FROM category";
            $categories = executer($sql);
            $productCategories = '';
            foreach ($categories as $category) {
                if ($category['categoryID'] == $product['categoryID']) {
                    $productCategories .= '
                    <option value="' . $category['categoryID'] . '" selected>' . $category['categoryName'] . '</option>
                    ';
                } else {
                    $productCategories .= '
                    <option value="' . $category['categoryID'] . '">' . $category['categoryName'] . '</option>
                    ';

                }
            }
            $sql = "SELECT * FROM store";
            $stores = executer($sql);
            $productStores = '';
            foreach ($stores as $store) {
                if ($store['storeID'] == $product['storeID']) {
                    $productStores .= '
                    <option value="' . $store['storeID'] . '" selected>' . $store['storeName'] . '</option>
                    ';
                } else {
                    $productStores .= '
                    <option value="' . $store['storeID'] . '">' . $store['storeName'] . '</option>
                    ';

                }
            }

            $sql = "SELECT * FROM images WHERE productID='$productID'";
            $images = executer($sql);
            $productImages = '';
            foreach ($images as $image) {
                $productImage = substr_replace($image['name'], '../..', 0, 2);

                $productImages .= '
                <div class="product-image" id="image_' . $image['id'] . '">
                                <img src="' . $productImage . '" alt="">
                                <input type="button" value="Delete" class="btn btn-danger delete-btn" onclick="App.deleteImage(`' . $productID . '`,`' . $image['id'] . '`)"/>
                            </div>
                ';
            }

            $response = array('details' => $productDetails, 'categories' => $productCategories, 'images' => $productImages, 'stores' => $productStores);

            exit(json_encode($response));

        } elseif ($key == 'update_product') {} elseif ($key == 'delete_product') {
            $productID = $_POST['id'];
            $sql = "DELETE  FROM images WHERE productID='$productID'";
            if (noResultQuery($sql) == 'done') {
                $sql = "DELETE FROM product WHERE productID='$productID'";
                noResultQuery($sql);
                exit('deleted');
            }
        } elseif ($key == 'get_reviews') {
            $productID = $_POST['id'];
            $start = $_POST['start'];
            $limit = $_POST['limit'];
            $sql = "SELECT reviews.review,customer.username,reviews.reviewTime FROM reviews INNER JOIN customer ON reviews.customerID=customer.customerID WHERE productID='$productID' ORDER BY reviewTime DESC LIMIT $start,$limit";
            $reviews = executer($sql);
            $productReviews = '';
            if (count($reviews) > 0) {
                foreach ($reviews as $review) {
                    $productReviews .= '
                    <tr>
                                        <td>
                                            ' . $review['username'] . '</td>
                                            <td>' . $review['review'] . '</td>
                                            <td>' . $review['reviewTime'] . '</td>

                                    </tr>
                    ';
                }
                exit($productReviews);
            } else {
                exit('no_more');
            }
        } elseif ($key == 'get_stores_categories') {
            $productID = $_POST['id'];
            $sql = "SELECT * FROM category ORDER BY categoryName";
            $categories = executer($sql);
            $categoriesReturn = '';
            foreach ($categories as $category) {
                $categoriesReturn .= '<option value="' . $category['categoryID'] . '">' . $category['categoryName'] . '</option>';
            }
            $sql = "SELECT * FROM store ORDER BY storeName";
            $stores = executer($sql);
            $storesReturn = '';
            foreach ($stores as $store) {
                $storesReturn .= '<option value="' . $store['storeID'] . '">' . $store['storeName'] . '</option>';
            }

            $response = array('stores' => $storesReturn, 'categories' => $categoriesReturn);
            exit(json_encode($response));
        } elseif ($key == 'delete_image') {
            $productID = $_POST['productID'];
            $imageID = $_POST['imageID'];
            $sql = "SELECT * FROM images WHERE productID='$productID'";
            $images = executer($sql);
            if (count($images) > 1) {
                $sql = "DELETE FROM images WHERE id='$imageID'";
                if (noResultQuery($sql) == 'done') {
                    exit('deleted');
                } else {
                    exit('failed');
                }
            } else {
                exit('reached_min');
            }
        }
    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}

try {
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!empty($_FILES['image1']['name']) || !empty($_FILES['image2']['name']) || !empty($_FILES['image3']['name'])) {

        $description = $_POST['product_description'];
        $productName = $_POST['productName'];
        $store = $_POST['storeID'];
        $category = $_POST['categoryID'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];

        $sql = "SELECT * FROM product WHERE productName = '$productName'";
        $products = executer($sql);
        if (count($products) > 0) {
            exit('exist');
        }

        $sql = "INSERT INTO product (productName,storeID,categoryID,price,stock,`description`) VALUES ('$productName','$store','$category','$price','$stock','$description')";
        $productID = '';
        if (noResultQuery($sql) == 'done') {
            $productID = $connect->lastInsertId();
            if (!empty($_FILES['image1']['name'])) {
                uploadImage($productID, $_FILES['image1']['name'], $_FILES['image1']['tmp_name']);
            }
            if (!empty($_FILES['image2']['name'])) {
                uploadImage($productID, $_FILES['image2']['name'], $_FILES['image2']['tmp_name']);
            }
            if (!empty($_FILES['image3']['name'])) {
                uploadImage($productID, $_FILES['image3']['name'], $_FILES['image3']['tmp_name']);
            }
        }
        $response = array('result' => 'added', 'productID' => $productID);
        exit(json_encode($response));
    }
} catch (PDOException $e) {
    exit($e->getMessage());
}

function uploadImage($productID, $image, $tmp)
{
    global $connect;
    $test = explode(".", $image);
    $extension = end($test);
    $name = rand(100, 9990) . '.' . $extension;
    $location = '../../images/' . $name;
    move_uploaded_file($tmp, $location);
    $finalName = '../images/' . $name;
    $sql = "INSERT INTO images (`name`,`productID`) VALUES('$finalName','$productID')";
    noResultQuery($sql);
}

function executer($sql)
{
    global $connect;
    $statement = $connect->prepare($sql);
    if ($statement->execute()) {
        $result = $statement->fetchAll();
        return $result;
    }
}

function noResultQuery($sql)
{
    global $connect;
    $statement = $connect->prepare($sql);
    if ($statement->execute()) {
        return "done";
    } else {
        return "failed";
    }
}