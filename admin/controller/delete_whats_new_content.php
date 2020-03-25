<?php

session_start();
require '../../config/db.php';
define("BASE_DIR", $_SERVER['DOCUMENT_ROOT'] . "/suncros/admin/");

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    $id = $_POST['id'];
    $db = Database::getInstance();
    $mysqli = $db->getConnection();

    $stmt = $mysqli->query("SELECT image, content, type_id FROM s_whats_new_content WHERE id = '$id'");
    $selectedData = $stmt->fetch_array();

    $sql = "DELETE FROM s_whats_new_content WHERE id = $id";
    $result = $mysqli->query($sql);
    if ($result == 1) {
        //unlink(BASE_DIR . "upload/thumb_image/" . $selectedData['image']);
        if ($selectedData['type_id'] == 1) {
            //unlink(BASE_DIR . "upload/articles_image/" . $selectedData['content']);
        }
    }
    echo $result;
} else {
    header("Location: ../not_found.html");
}

