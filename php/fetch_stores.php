<?php
require './db_connect.php';
if (isset($_POST['key'])) {
    if ($_POST['key'] == 'table') {
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $query = "SELECT store.storeID,store.storeName,locations.locationID,locations.locationName FROM store INNER JOIN locations ON locations.locationID=store.locationID limit $start,$limit";
        $output = '';
        try {
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $statement = $connect->prepare($query);
            if ($statement->execute()) {
                $result = $statement->fetchAll();
                if (count($result) > 0) {
                    foreach ($result as $row) {
                        $output .= '
                <tr >
                    <td class="storeID" data-id="' . $row['storeName'] . '">' . $row['storeName'] . '</td>
                    <td class="storeID" data-id="' . $row['storeName'] . '">' . $row['locationName'] . '</td>
                </tr>';
                    }
                } else {
                    $output = "no_more";
                }
            }
            echo $output;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } elseif ($_POST['key'] == 'modal') {
        $output1 = '<div style="display: flex;justify-content: space-around"><table>
        <tr><th>Store</th><th>Location</th></tr>
        <tr><td>
                        <select name="" id="supermarket">
                        <option value="0">All</option>';
        $query1 = "SELECT storeID,storeName FROM store";
        $query2 = "SELECT * FROM locations";
        try {
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $statement1 = $connect->prepare($query1);
            if ($statement1->execute()) {
                $result1 = $statement1->fetchAll();
                if (count($result1) > 0) {
                    foreach ($result1 as $row1) {
                        $output1 .= '<option value="' . $row1['storeName'] . '">' . $row1['storeName'] . '</option>';
                    }
                } else {
                    echo "No stores available";
                }
            }
            $output1 .= '</select>
            </td><td><select name="" id="location">
            <option value="0">All</option>';
            $statement2 = $connect->prepare($query2);
            if ($statement2->execute()) {
                $result2 = $statement2->fetchAll();
                if (count($result2) > 0) {
                    foreach ($result2 as $row2) {
                        $output1 .= '<option value="' . $row2['locationName'] . '">' . $row2['locationName'] . '</option>';
                    }
                } else {
                    echo "No available stores";
                }
            }
            $output1 .= '</select></td></tr></table></div>';
            echo $output1;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }
}