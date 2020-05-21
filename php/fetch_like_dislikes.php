<?php
require './db_connect.php';

if (isset($_POST['key'])) {
    try {
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $customerID = $_POST['customerID'];
        $productID = $_POST['productID'];
        $reviewAction = '';
        if ($_POST["key"] == "like") {
            if (userLiked($customerID, $productID)) {
                $reviewAction = "unlike";
                deleteReviewAction($customerID, $productID);
                $return = array("likes" => getLikes($productID), "dislikes" => getDislikes($productID), "action" => $reviewAction);
                echo json_encode($return);
            } else {
                $reviewAction = 'like';
                deleteReviewAction($customerID, $productID);
                setAction($customerID, $productID, $reviewAction);
                $return = array("likes" => getLikes($productID), "dislikes" => getDislikes($productID), "action" => $reviewAction);
                echo json_encode($return);
            }
        } elseif ($_POST["key"] == "dislike") {
            if (userDisliked($customerID, $productID)) {
                $reviewAction = "undislike";
                deleteReviewAction($customerID, $productID);
                $return = array("likes" => getLikes($productID), "dislikes" => getDislikes($productID), "action" => $reviewAction);
                echo json_encode($return);
            } else {
                $reviewAction = "dislike";
                deleteReviewAction($customerID, $productID);
                setAction($customerID, $productID, $reviewAction);
                $return = array("likes" => getLikes($productID), "dislikes" => getDislikes($productID), "action" => $reviewAction);
                echo json_encode($return);
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function setAction($customerID, $productID, $action)
{
    global $connect;

    $query = "INSERT INTO rating (customerID, productID, reviewAction) VALUES ($customerID,$productID, '$action')";

    $statement = $connect->prepare($query);
    $statement->execute();
}

function getLikes($productID)
{
    global $connect;
    $query = "SELECT * FROM rating WHERE productID='$productID' AND reviewAction='like'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    return count($result);
}

function getDislikes($productID)
{
    global $connect;
    $query = "SELECT * FROM rating WHERE productID='$productID' AND reviewAction='dislike'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    return count($result);
}

function deleteReviewAction($customerID, $productID)
{
    global $connect;
    $query = "DELETE FROM rating WHERE customerID='$customerID' AND productID='$productID'";
    $statement = $connect->prepare($query);
    $statement->execute();

}

function userLiked($customerID, $productID)
{
    global $connect;
    $query = "SELECT reviewAction FROM rating WHERE customerID='$customerID' AND productID='$productID' AND reviewAction='like'";
    $statement = $connect->prepare($query);
    if ($statement->execute()) {
        $result = $statement->fetchAll();
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

}

function userDisliked($customerID, $productID)
{
    global $connect;
    $query = "SELECT reviewAction FROM rating WHERE customerID='$customerID' AND productID='$productID' AND reviewAction='dislike'";
    $statement = $connect->prepare($query);
    if ($statement->execute()) {
        $result = $statement->fetchAll();
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

}