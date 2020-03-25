<?php

session_start();
require '../../config/db.php';

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    $searchtext = $_POST['searchtext'];

    $sql = "SELECT COUNT(id) as enquiry_count FROM s_enquiry ";
    if (!empty($searchtext)) {
        $sql .= " WHERE name LIKE '$searchtext%' OR email LIKE '$searchtext%'";
    }
    
    $db = Database::getInstance();
    $mysqli = $db->getConnection();
    
    $stmt = $mysqli->query($sql);
    $data = $stmt->fetch_assoc();
    echo $data['enquiry_count'];
} else {
    header("Location: ../not_found.html");
}