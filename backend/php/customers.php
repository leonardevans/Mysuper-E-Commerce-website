<?php
require './db_connect.php';
if (isset($_POST['key'])) {
    $key = $_POST['key'];
    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($key == "fetch_customers") {
            $start = $_POST['start'];
            $limit = $_POST['limit'];
            $sql = "SELECT username,email,dateJoined FROM customer WHERE customerID !=1 limit $start,$limit";
            $customers = executer($sql);
            if (count($customers) > 0) {
                $response = '';
                foreach ($customers as $customer) {
                    $response .= '
                    <tr>
                    <td>' . $customer['username'] . '</td>
                    <td>' . $customer['email'] . '</td>
                    <td>' . $customer['dateJoined'] . '</td>
                    </tr>
                    ';
                }
                exit($response);
            } else {
                exit("no_more");
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