<?php

session_start();
require '../../config/db.php';

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    $id = $_POST['id'];

    $sql = "DELETE FROM s_enquiry WHERE id = $id";
    
    $db = Database::getInstance();
    $mysqli = $db->getConnection();
    
    $result = $mysqli->query($sql);
    echo $result;
} else {
    header("Location: ../not_found.html");
}

