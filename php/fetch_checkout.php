<?php
require './db_connect.php';
if (isset($_POST['key'])) {
    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($_POST['key'] == 'get_addresses') {
            $customerID = $_POST['customerID'];
            $query = "SELECT * FROM customeraddress WHERE customerID='$customerID'";
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $addresses = '<h4>My Addresses</h4>';
            if (count($result) > 0) {
                foreach ($result as $row) {
                    $addresses .= '<p class="address-p"><input type="radio" name="address" class="address" onclick="CheckoutApp.setAddress(`' . $row['addressID'] . '`)"> Contact Phone: +254' . $row['contactPhone'] . ', City: ' . $row['city'] . ', Street: ' . $row['street'] . ',House: ' . $row['house'] . ' <span id="delete-address" data-id="' . $row['addressID'] . '">delete</span></p>';
                }
            } else {
                $addresses .= '<p class="address-p">You have no saved addresses</p>';
            }
            echo $addresses;
        } elseif ($_POST['key'] == 'save_address') {
            $customerID = $_POST['customerID'];
            $contactPhone = $_POST['contactPhone'];
            $city = $_POST['city'];
            $street = $_POST['street'];
            $house = $_POST['house'];
            $query = "INSERT INTO customeraddress (customerID,contactPhone,city,street,house) VALUES ('$customerID','$contactPhone','$city','$street','$house')";
            $statement = $connect->prepare($query);
            $statement->execute();
            echo 'address_added';
        } elseif ($_POST['key'] == 'delete_address') {
            $addressID = $_POST['addressID'];
            $query = "DELETE FROM customeraddress WHERE addressID='$addressID'";
            $statement = $connect->prepare($query);
            $statement->execute();
            echo 'address_deleted';
        } elseif ($_POST['key'] == 'get_address') {
            $addressID = $_POST['addressID'];
            $query = "SELECT * FROM customeraddress WHERE addressID='$addressID'";
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $address = '';
            if (count($result) > 0) {
                foreach ($result as $row) {
                    $address .= '<p class="address-p">Contact Phone: +254' . $row['contactPhone'] . ', City: ' . $row['city'] . ', Street: ' . $row['street'] . ',House: ' . $row['house'] . '</p>';
                }
            } else {
                $address .= '<p class="address-p">You have no saved addresses</p>';
            }
            echo $address;
        } elseif ($_POST['key'] == 'place_order') {
            $customerID = $_POST['customerID'];
            $orderItems = $_POST['orderItems'];
            $addressID = $_POST['address'];
            $orderTotal = $_POST['orderTotal'];
            $orderDetails = $_POST['orderDetails'];

            $query = "INSERT INTO orders (customerID,addressID,orderItems,orderDetails,orderTotal) VALUES ('$customerID','$addressID','$orderItems','$orderDetails','$orderTotal')";
            $statement = $connect->prepare($query);
            $statement->execute();
            echo 'order_placed';
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}