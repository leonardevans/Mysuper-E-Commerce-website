<?php
require './db_connect.php';
if (isset($_POST['key'])) {
    $key = $_POST['key'];
    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($key == "fetch_totals") {
            $productsSql = "SELECT * FROM product";
            $products = count(executer($productsSql));
            $storesSql = "SELECT * FROM store";
            $stores = count(executer($storesSql));
            $categoriesSql = "SELECT * FROM category";
            $categories = count(executer($categoriesSql));
            $locationsSql = "SELECT * FROM locations";
            $locations = count(executer($locationsSql));
            $ordersSql = "SELECT * FROM orders";
            $orders = count(executer($ordersSql));
            $customersSql = "SELECT * FROM customer";
            $customers = count(executer($customersSql));
            
            $response = array('products' => $products, "stores"=>$stores,"categories"=>$categories,"locations"=>$locations,"orders"=>$orders,"customers"=>$customers);
            exit(json_encode($response));
        }elseif($key == 'latest_orders'){
            $sql = "SELECT orders.orderID,customer.username,orders.orderTotal,orders.orderStatus,orderDate FROM orders INNER JOIN customer ON orders.customerID = customer.customerID ORDER BY orderDate DESC limit 10";
            $orders = executer($sql);

            if(count($orders)>0){
                $response = '';
                foreach($orders as $order){
                    $response .= '
                    <tr>
                    <td data-id="'.$order['orderID'].'">'.$order['orderID'].'</td>
                    <td data-id="'.$order['orderID'].'">'.$order['username'].'</td>
                    <td data-id="'.$order['orderID'].'">Ksh. '.number_format( $order['orderTotal']).'</td>
                    <td data-id="'.$order['orderID'].'">'.$order['orderStatus'].'</td>
                    <td data-id="'.$order['orderID'].'">'.$order['orderDate'].'</td>
                    </tr>
                    ';
                }
                exit($response);
            }else{
                exit("No Orders Made");
            }

        }

    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}

function executer($sql)
{
    global $connect;
    $statement = $connect->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();
    return $result;
}
