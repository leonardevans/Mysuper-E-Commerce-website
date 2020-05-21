<?php
require './db_connect.php';

if (isset($_POST['productID'])) {
    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $productID = $_POST['productID'];
        $description = $_POST['product_description'];
        $productName = $_POST['productName'];
        $store = $_POST['storeID'];
        $category = $_POST['categoryID'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];

        $sql = "SELECT * FROM product WHERE productName = '$productName' AND productID != $productID";
        $products = executer($sql);
        if (count($products) > 0) {
            exit('exist');
        } else {

            $sql = "UPDATE product SET productName='$productName',storeID='$store',categoryID='$category',price='$price',stock='$stock',`description`='$description' WHERE productID='$productID'";
            if (noResultQuery($sql) == 'done') {

                if (!empty($_FILES['image1']['name'])) {
                    uploadImage($productID, $_FILES['image1']['name'], $_FILES['image1']['tmp_name']);
                }
                if (!empty($_FILES['image2']['name'])) {
                    uploadImage($productID, $_FILES['image2']['name'], $_FILES['image2']['tmp_name']);
                }
                if (!empty($_FILES['image3']['name'])) {
                    uploadImage($productID, $_FILES['image3']['name'], $_FILES['image3']['tmp_name']);
                }
                exit('updated');
            } else {
                exit('failed');
            }
        }

    } catch (PDOException $e) {
        exit($e->getMessage());
    }

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