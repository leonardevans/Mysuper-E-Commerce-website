<?php
require './db_connect.php';
if (isset($_POST['key'])) {
    $key = $_POST['key'];
    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($key == 'fetch_orders') {
            $start = $_POST['start'];
            $limit = $_POST['limit'];
            $sql = "SELECT orders.orderID,customer.username,orders.orderTotal,orders.orderStatus,orders.orderDate FROM orders INNER JOIN customer ON orders.customerID=customer.customerID ORDER BY orderDate DESC LIMIT $start,$limit";
            $orders = executer($sql);
            if (count($orders) > 0) {
                $response = '';
                foreach ($orders as $order) {
                    $response .= '
                <tr>
                <td>' . $order['orderID'] . '</td>
                <td>' . $order['username'] . '</td>
                <td><b>Ksh. ' . number_format($order['orderTotal']) . '</b></td>
                <td><b>' . $order['orderStatus'] . '</b></td>
                <td>' . $order['orderDate'] . '</td>
                <td>
                <input type="button" value="View" class="btn btn-secondary view-btn" onclick="App.viewOrderDetails(`' . $order['orderID'] . '`)"/>
                </td>
                </tr>
                ';
                }
                exit($response);
            } else {
                exit('no_more');
            }
        } elseif ($key == 'view_order') {
            $orderID = $_POST['orderID'];
            $sql = "SELECT orders.orderID,orders.addressID,orders.orderItems,orders.orderDetails,customer.username,orders.orderTotal,orders.orderStatus,orders.orderDate FROM orders INNER JOIN customer ON orders.customerID=customer.customerID WHERE orderID='$orderID'";
            $orders = executer($sql);
            if (count($orders) > 0) {
                $order = $orders[0];
                $orderHeaders = '
            <tr>
                <td>' . $order['orderID'] . '</td>
                <td>' . $order['username'] . '</td>
                <td><b>Ksh. ' . number_format($order['orderTotal']) . '</b></td>
                <td ><b><span class="this-status">' . $order['orderStatus'] . '</span></b></td>
                <td>' . $order['orderDate'] . '</td>
                </tr>
            ';
                $details = json_decode($order['orderDetails'], true);
                $orderDetails = '
            <tr>
            <td>' . $details[0]['deliveryMethod'] . '</td>
            <td>' . $details[0]['paymentMethod'] . '</td>
            <td>Ksh. ' . number_format($details[0]['shoppingFee']) . '</td>
            <td>Ksh. ' . number_format($details[0]['shippingFee']) . '</td>
            </tr>
            ';
                $items = json_decode($order['orderItems'], true);
                $orderItems = '';
                foreach ($items as $item) {
                    $image = substr_replace($item['image'], '../..', 0, 2);
                    $orderItems .= '
            <tr>
            <td data-id="' . $item['id'] . '"><img src="' . $image . '" data-id="' . $item['id'] . '" /></td>
            <td data-id="' . $item['id'] . '">' . $item['name'] . '</td>
            <td data-id="' . $item['id'] . '">' . $item['store'] . '</td>
            <td data-id="' . $item['id'] . '">' . $item['location'] . '</td>
            <td data-id="' . $item['id'] . '">Ksh. ' . number_format($item['price']) . '</td>
            <td data-id="' . $item['id'] . '">' . $item['items'] . '</td>
            <td data-id="' . $item['id'] . '">Ksh. ' . number_format($item['subtotal']) . '</td>
            </tr>
            ';
                }
                $orderItems .= '<tr>
                                <td colspan="6" style="text-align:center"><b>Total</b></td>
                                <td><b>Ksh. ' . number_format($details[0]['cartTotal']) . '</b></td>
                            </tr>';
                $address = '';
                if ($order['addressID'] == 0) {
                    $address .= '<tr><td colspan="4">No address given.</td></tr>';
                } else {
                    $sql = "SELECT * FROM customeraddress WHERE addressID=" . $order['addressID'] . "";
                    $addresses = executer($sql);
                    if (count($addresses) > 0) {
                        $address .= '
                    <tr>
                    <td>+254' . $addresses[0]['contactPhone'] . '</td>
                    <td>' . $addresses[0]['city'] . '</td>
                    <td>' . $addresses[0]['street'] . '</td>
                    <td>' . $addresses[0]['house'] . '</td>
                    </tr>
                    ';
                    } else {
                        $address .= '<tr><td colspan="4">Address given does not exist.</td></tr>';
                    }
                }

                $response = array('orderHeaders' => $orderHeaders, 'orderDetails' => $orderDetails, 'orderItems' => $orderItems, 'address' => $address, 'status' => $order['orderStatus']);
                exit(json_encode($response));
            } else {
                exit('not_found');
            }
        } elseif ($key == 'update_order') {
            $orderID = $_POST['orderID'];
            $orderStatus = $_POST['status'];
            $sql = "UPDATE orders SET orderStatus='$orderStatus' WHERE orderID='$orderID'";
            noResultQuery($sql);
            exit('updated');
        }

    } catch (PDOException $e) {
        exit($e->getMessage());
    }
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