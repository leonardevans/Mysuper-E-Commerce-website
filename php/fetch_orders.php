<?php
require './db_connect.php';

if (isset($_POST['key'])) {
    $key = $_POST['key'];
    try {
        if ($key == 'get_orders') {
            $customerID = $_POST['customerID'];
            $query = "SELECT * FROM orders WHERE customerID='$customerID' ORDER BY orderDate DESC";
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $orders = '';
            if (count($result) > 0) {
                foreach ($result as $row) {
                    $orderItems = json_decode($row['orderItems'], true);
                    $orderDetails = json_decode($row['orderDetails'], true);
                    $orders .= '<div class="order">
                    <table>
                    <tr>
                        <th>Order ID</th>
                        <th>Delivery Method</th>
                        <th>Payment Method</th>
                        <th>Shopping Fee</th>
                        <th>Shipping Fee</th>
                        <th>Order Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Options</th>
                    </tr>
                    <tr>
                    <td>' . $row['orderID'] . '</td>
                    <td>' . $orderDetails[0]['deliveryMethod'] . '</td>
                    <td>' . $orderDetails[0]['paymentMethod'] . '</td>
                    <td>' . $orderDetails[0]['shoppingFee'] . '</td>
                    <td>' . $orderDetails[0]['shippingFee'] . '</td>
                    <td>Ksh. ' . number_format($row['orderTotal'], 2) . '</td>
                    <td><h3>' . $row['orderStatus'] . '</h3></td>
                    <td>' . $row['orderDate'] . '</td>
                    <td><span class="view-more open-details">View Details</span></td>
                    </tr>
                    </table>
                    <div class="more-details">
                    ' . getAddress($row['addressID'], $customerID) . '
                    <table>
                    <caption>Order Items</caption>
                    <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Store</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Items</th>
                    <th>Subtotal</th>
                    </tr>
                    <tr>';
                    foreach ($orderItems as $orderItem) {
                        $orders .= '<td><a class="order-img-link" href="../product/?pid=' . $orderItem['id'] . '"><img src="' . $orderItem['image'] . '" width="40px" height="40px"></a></td>
                    <td>' . $orderItem['name'] . '</td>
                    <td>' . $orderItem['store'] . '</td>
                    <td>' . $orderItem['location'] . '</td>
                    <td>Ksh. ' . number_format($orderItem['price'], 2) . '</td>
                    <td>' . $orderItem['items'] . '</td>
                    <td>Ksh. ' . number_format($orderItem['subtotal'], 2) . '</td>
                    </tr>';
                    }
                    $orders .= '<tr>
                    <td colspan="6">Items Total</td>
                    <td>Ksh. ' . number_format($orderDetails[0]['cartTotal'], 2) . '</td>
                    </tr>
                    </table>
                    </div>
                    </div>
                    ';
                }

            } else {
                $orders .= '<div class="order">You have no orders</div>';
            }
            echo $orders;

        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getAddress($addressID, $customerID)
{
    global $connect;
    $query = "SELECT * FROM customeraddress WHERE customerID='$customerID' AND addressID='$addressID'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $address = '<div class="order-address"><h4>Address</h4><p>';
    if (count($result) > 0) {
        foreach ($result as $row) {
            $address .= 'Contact Phone: +254' . $row['contactPhone'] . ', City: ' . $row['city'] . ', Street: ' . $row['street'] . ',House: ' . $row['house'] . '';
        }
    } else {
        $address .= 'No address given.';
    }
    $address .= '</p></div>';
    return $address;
}