<?php

session_start();
require '../../config/db.php';

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    $name = $_GET['name'];
    $type_id = $_GET['type_id'];
    $status = $_GET['status'];
    $start = $_GET['start'];
    $record_limit = $_GET['record_limit'];

    $sql = "SELECT wnc.id, wnc.name, wnc.image, wnc.content, wnc.created_date, wnc.modified_date, wnc.status wnc_status, "
            . "wnt.name type_name FROM s_whats_new_content wnc"
            . " LEFT JOIN s_whats_new_type wnt ON wnc.type_id = wnt.id WHERE 1 ";
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