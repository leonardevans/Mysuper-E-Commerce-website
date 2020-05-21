<?php
require './db_connect.php';

if (isset($_POST['key'])) {
    $key = $_POST['key'];
    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($key == "fetch_categories") {
            $start = $_POST['start'];
            $limit = $_POST['limit'];
            $sql = "SELECT * FROM category  ORDER BY categoryName DESC limit $start,$limit";
            $categories = executer($sql);
            if (count($categories) > 0) {
                $response = '';
                foreach ($categories as $category) {
                    $response .= '
                    <tr>
                    <td>' . $category['categoryName'] . '</td>
                    <td id="category_' . $category['categoryID'] . '">
                    <input type="button" value="Edit" class="btn btn-primary edit-btn" onclick="App.editCategory(`' . $category['categoryID'] . '`,`edit_category`)" />
                     <input type="button" value="Delete" class="btn btn-danger delete-btn" onclick="App.deleteCategory(`' . $category['categoryID'] . '`)" />
                     </td>
                    </td>
                    </tr>
                    ';
                }
                exit($response);
            } else {
                exit("no_more");
            }

        } elseif ($key == 'delete_category') {
            $categoryID = $_POST['categoryID'];
            $sql = "SELECT * FROM product WHERE product.categoryID='$categoryID'";
            $categories = executer($sql);
            if (count($categories) > 0) {
                exit("foreign_key");
            } else {
                $sql = "DELETE FROM category WHERE category.categoryID='$categoryID'";
                if (noResultQuery($sql) == "done") {
                    exit("category_deleted");
                } else {
                    exit("failed");
                }
            }
        } elseif ($key == "edit_category") {
            $categoryName = $_POST['category'];
            $id = $_POST['id'];
            $query = "SELECT * FROM `category` WHERE category.categoryName='$categoryName' AND category.categoryID!='$id'";
            $categories = executer($query);
            if (count($categories) > 0) {
                exit("exist");
            } else {
                $sql = "UPDATE category SET categoryName = '$categoryName' WHERE categoryID='$id'";
                noResultQuery($sql);
                exit("updated");

            }

        } elseif ($key == "get_data") {
            $id = $_POST['id'];
            $query = "SELECT * FROM `category` WHERE category.categoryID='$id'";
            $categories = executer($query);
            exit($categories[0]['categoryName']);
        } elseif ($key == "add_category") {
            $categoryName = $_POST['category'];
            $query = "SELECT * FROM `category` WHERE category.categoryName='$categoryName'";
            $categories = executer($query);

            if (count($categories) > 0) {
                exit("exist");
            } else {
                $sql = "INSERT INTO category (categoryName) VALUE('$categoryName')";
                noResultQuery($sql);
                exit("added");

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