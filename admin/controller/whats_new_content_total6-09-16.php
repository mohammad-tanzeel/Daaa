<?php

session_start();
require '../../config/db.php';

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    $name = $_POST['name'];
    $type_id = $_POST['type_id'];
    $status = $_POST['status'];

    $sql = "SELECT COUNT(wnc.id) as doctor_count FROM s_whats_new_content wnc"
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
    $db = Database::getInstance();
    $mysqli = $db->getConnection();
    
    $stmt = $mysqli->query($sql);
    $data = $stmt->fetch_assoc();
    echo $data['doctor_count'];
} else {
    header("Location: ../not_found.html");
}