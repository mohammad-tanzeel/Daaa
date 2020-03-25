<?php

session_start();
require '../../config/db.php';

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    $name = $_GET['name'];
    $type_id = $_GET['type_id'];
    $status = $_GET['status'];
    $start = $_GET['start'];
    $record_limit = $_GET['record_limit'];
    $start = 0;
    $record_limit = 25;

    $sql = "SELECT p.id, p.name, p.image1, p.description, p.location, p.created_date, p.modified_date, p.status as p_status, p.description, pc.name as type_name "
            . "FROM projects p"
            . " LEFT JOIN project_category pc ON p.category_id = pc.id WHERE 1 ";
    if (!empty($name)) {
        $sql .= "AND wnc.name LIKE '$name%'";
    }
    if (!empty($type_id)) {
        $sql .= "AND wnc.type_id = '$type_id%'";
    }
    if ($status != "") {
        $sql .= "AND wnc.status = '$status%'";
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