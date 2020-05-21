<?php
require './db_connect.php';
if (isset($_POST['key'])) {
    if ($_POST['key'] == "get_product") {
        $productID = $_POST['productID'];
        $items = $_POST['items'];
        $result = getProductDetails($productID, $items);
        if($result == 'no_such_product'){
            echo 'no_such_product';
        }else{
            echo json_encode($result);
        }
        

    }
    if ($_POST['key'] == "save_cart") {
        $cart = $_POST['cart'];
        $customerID = $_POST['customerID'];
        saveCart($customerID, $cart);
    }
    if ($_POST['key'] == "get_cart") {
        $customerID = $_POST['customerID'];
        echo getCart($customerID);
    }

   
}

function getProductDetails($productID, $items)
{
    global $connect;
    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT product.productID,product.productName,product.price,product.stock,store.storeName,locations.locationName,category.categoryName FROM (((product INNER JOIN category ON product.categoryID = category.categoryID)INNER JOIN store ON product.storeID = store.storeID)INNER JOIN locations ON store.locationID = locations.locationID) WHERE productID='$productID' AND product.stock > 0";
        $statement = $connect->prepare($query);
        if ($statement->execute()) {
            $result = $statement->fetchAll();
            if (count($result) > 0) {
                foreach ($result as $row) {
                    if($items>$row['stock']){
                        $items =$row['stock'];
                    }
                    $subtotal = $row['price'] * $items;
                    $htmlOutput = '


                <p><span class="remove-item" ><i class="fa fa-window-close" data-id="' . $row['productID'] . '"></i></span></p>
                
                <div class="cart-item-inner-div">
                    <div>
                        <img onclick="Cart.viewProduct(`' . $row['productID'] . '`)" src="' . getImage($productID) . '" alt="' . $row['productName'] . '"></div>
                    <div>
                        <h4>' . $row['productName'] . '</h4>
                        <h5>ksh. ' . number_format($row['price']) . '</h5>
                        <h5>Store: <span class="c-item-store">' . $row['storeName'] . '</span></h5>
                        <h5>Location: <span class="c-item-location">' . $row['locationName'] . '</span></h5>
                    </div>
                    <div>
                        <i class="fas fa-chevron-up" data-id="' . $row['productID'] . '" data-stock="'.$row['stock'].'"></i>
                        <p class="item-amount">1</p>
                        <i class="fas fa-chevron-down" data-id="' . $row['productID'] . '" data-stock="'.$row['stock'].'"></i>
                    </div>
                </div>
                <h4>Sub Total : Ksh. <span class="cart-sub-total" data-id="' . $row['productID'] . '">' . $subtotal . '</span></h4>
                ';
                    $productDetails = array("id" => $productID, "name" => $row['productName'], "image" => getImage($productID), "store" => $row['storeName'], "location" => $row['locationName'], "price" => $row['price'], 'items' => $items, 'subtotal' => $subtotal);
                    return array('html' => $htmlOutput, "details" => $productDetails);

                }
            }else{
                return 'no_such_product';
            }
        }
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function getImage($productID)
{
    global $connect;
    $query = "SELECT * FROM images WHERE productID=" . $productID . "";
    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $connect->prepare($query);
        if ($statement->execute()) {
            $result = $statement->fetchAll();
            $image = $result[0]['name'];

        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $image;
}

function saveCart($customerID, $cart)
{
    global $connect;
    $query = "SELECT * FROM carts WHERE customerID='$customerID'";

    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        if (count($result) > 0) {
            $cartId= $result[0]['id'];
            $query1 = "UPDATE carts SET cart='$cart' WHERE customerID='$customerID'";
        } else {
            $query1 = "INSERT INTO carts (customerID,cart) VALUES ('$customerID','$cart')";
        }
        $statement1 = $connect->prepare($query1);
        $statement1->execute();

    } catch (PDOException $e) {
        echo $e->getMessage();
    }

}

function getCart($customerID)
{
    global $connect;
    $query = "SELECT * FROM carts WHERE customerID='$customerID'";
    $cart = '';
    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();

        if (count($result) > 0) {
            foreach ($result as $row) {
                $cart = $row['cart'];
            }
        } else {
            $cart = '[]';
            saveCart($customerID, $cart);
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $cart;
}
