<?php
require './db_connect.php';
if (isset($_POST['key'])) {
    $key = $_POST['key'];
    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($key == 'fetch_stores') {
            $start = $_POST['start'];
            $limit = $_POST['limit'];
            $sql = "SELECT store.storeID,store.storeName,locations.locationName FROM store INNER JOIN locations on store.locationID=locations.locationID  ORDER BY storeName DESC limit $start,$limit";
            $stores = executer($sql);
            if (count($stores) > 0) {
                $response = '';
                foreach ($stores as $store) {
                    $response .= '
                    <tr>
                    <td>' . $store['storeName'] . '</td>
                    <td>' . $store['locationName'] . '</td>
                    <td id="store_' . $store['storeID'] . '">
                    <input type="button" value="Edit" class="btn btn-primary edit-btn" onclick="App.editStore(`' . $store['storeID'] . '`,`edit_store`)" />
                     <input type="button" value="Delete" class="btn btn-danger delete-btn" onclick="App.deleteStore(`' . $store['storeID'] . '`)" />
                     </td>
                    </tr>
                    ';
                }
                exit($response);
            } else {
                exit('no_more');
            }
        } elseif ($key == 'delete_store') {
            $storeID = $_POST['id'];
            $sql = "SELECT * FROM product WHERE product.storeID='$storeID'";
            $stores = executer($sql);
            if (count($stores) > 0) {
                exit("foreign_key");
            } else {
                $sql = "DELETE FROM store WHERE store.storeID='$storeID'";
                executer($sql);
                exit('deleted');
            }
        } elseif ($key == 'get_locations') {
            $sql = "SELECT * FROM locations ORDER BY locationName";
            $locations = executer($sql);
            $response = '';
            foreach ($locations as $location) {
                $response .= '
                <option value="' . $location['locationID'] . '">' . $location['locationName'] . '</option>
                ';
            }
            exit($response);
        } elseif ($key == 'add_store') {
            $location = $_POST['location'];
            $storeName = $_POST['store'];
            $sql = "SELECT * FROM store WHERE store.storeName='$storeName'";
            $stores = executer($sql);
            if (count($stores) > 0) {
                exit('exist');
            } else {
                $sql = "INSERT INTO store (storeName,locationID) VALUES('$storeName','$location')";
                noResultQuery($sql);
                exit('added');
            }
        } elseif ($key == 'get_data') {
            $storeID = $_POST['id'];
            $sql = "SELECT * FROM store  WHERE storeID='$storeID'";
            $store = executer($sql);
            $storeName = $store[0]['storeName'];
            $sql = "SELECT * FROM locations";
            $locations = executer($sql);
            $locationOptions = '';
            foreach ($locations as $location) {
                if ($location['locationID'] == $store[0]['locationID']) {
                    $locationOptions .= '
                    <option value="' . $location['locationID'] . '"  selected>' . $location['locationName'] . '</option>
                    ';
                } else {
                    $locationOptions .= '
                    <option value="' . $location['locationID'] . '">' . $location['locationName'] . '</option>
                    ';
                }
            }

            $response = array('store_name' => $storeName, 'locations' => $locationOptions);
            exit(json_encode($response));
        } elseif ($key == 'edit_store') {
            $storeID = $_POST['id'];
            $location = $_POST['location'];
            $storeName = $_POST['store'];
            $sql = "SELECT * FROM store WHERE store.storeName='$storeName' AND store.storeID!='$storeID'";
            $stores = executer($sql);
            if (count($stores) > 0) {
                exit('exist');
            } else {
                $sql = "UPDATE store SET storeName='$storeName',locationID='$location' WHERE storeID='$storeID'";
                noResultQuery($sql);
                exit('updated');
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