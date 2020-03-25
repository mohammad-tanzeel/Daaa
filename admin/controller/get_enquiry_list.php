<?php

session_start();
require '../../config/db.php';

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    $searchtext = $_GET['searchtext'];
    $start = $_GET['start'];
    $record_limit = $_GET['record_limit'];

    $sql = "SELECT * FROM s_enquiry ";
    if (!empty($searchtext)) {
        $sql .= " WHERE name LIKE '$searchtext%' OR email LIKE '$searchtext%'";
    }
    $sql .= " LIMIT $start, $record_limit";

    $db = Database::getInstance();
    $mysqli = $db->getConnection();
    
    $stmt = $mysqli->query($sql);
    $data = array();
    $count = 0;
    while ($row = $stmt->fetch_assoc()) {
        $data[] = $row;
        $count ++;
    }
    if ($count > 0) {
        echo json_encode($data);
    } else {
        echo "false";
    }
} else {
    header("Location: ../not_found.html");
}