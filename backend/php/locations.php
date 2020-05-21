<?php
require './db_connect.php';
if (isset($_POST['key'])) {
    $key = $_POST['key'];
    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($key == "fetch_locations") {
            $start = $_POST['start'];
            $limit = $_POST['limit'];
            $sql = "SELECT * FROM locations  ORDER BY locationName DESC limit $start,$limit";
            $locations = executer($sql);
            if (count($locations) > 0) {
                $response = '';
                foreach ($locations as $location) {
                    $response .= '
                    <tr>
                    <td>' . $location['locationName'] . '</td>
                    <td id="location_' . $location['locationID'] . '">
                    <input type="button" value="Edit" class="btn btn-primary edit-btn" onclick="App.editLocation(`' . $location['locationID'] . '`,`edit_location`)" />
                     <input type="button" value="Delete" class="btn btn-danger delete-btn" onclick="App.deleteLocation(`' . $location['locationID'] . '`)" /></td>
                    </td>
                    </tr>
                    ';
                }
                exit($response);
            } else {
                exit("no_more");
            }
        } elseif ($key == "add_location") {
            $locationName = $_POST['location_name'];
            $query = "SELECT * FROM `locations` WHERE locations.locationName='$locationName'";
            $locations = executer($query);

            if (count($locations) > 0) {
                exit("location_exist");
            } else {
                $sql = "INSERT INTO locations (locationName) VALUE('$locationName')";
                noResultQuery($sql);
                exit("location_added");

            }
        } elseif ($key == 'delete_location') {
            $locationID = $_POST['locationID'];
            $sql = "SELECT * FROM store WHERE store.locationID='$locationID'";
            $stores = executer($sql);
            if (count($stores) > 0) {
                exit("foreign_key");
            } else {
                $sql = "DELETE FROM locations WHERE locations.locationID='$locationID'";
                if (noResultQuery($sql) == "done") {
                    exit("location_deleted");
                } else {
                    exit("failed");
                }
            }
        } elseif ($key == "edit_location") {
            $locationName = $_POST['location_name'];
            $id = $_POST['id'];
            $query = "SELECT * FROM `locations` WHERE locations.locationName='$locationName' AND locationID!='$id'";
            $locations = executer($query);
            if (count($locations) > 0) {
                exit("location_exist");
            } else {
                $sql = "UPDATE locations SET locationName = '$locationName' WHERE locationID='$id'";
                noResultQuery($sql);
                exit("location_updated");

            }

        } elseif ($key == "get_data") {
            $id = $_POST['id'];
            $query = "SELECT * FROM `locations` WHERE locations.locationID='$id'";
            $locations = executer($query);
            exit($locations[0]['locationName']);
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