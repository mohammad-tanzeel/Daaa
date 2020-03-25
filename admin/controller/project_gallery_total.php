<?php

session_start();
require '../../config/db.php';

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    $name = $_POST['name'];
    $project_id = $_POST['project_id'];
    $status = $_POST['status'];

    $sql = "SELECT COUNT(pi.id) as image_count FROM project_image pi"
            . " LEFT JOIN projects pc ON pc.id = pi.project_id WHERE 1 ";
    if (!empty($name)) {
        $sql .= "AND p.name LIKE '$name%'";
    }
//    if (!empty($type_id)) {
//        $sql .= "AND p.category_id = '$type_id%'";
//    }
    if ($status != "") {
        $sql .= "AND p.status = '$status%'";
    }
//    echo $sql;
    
    $db = Database::getInstance();
    $mysqli = $db->getConnection();
    
    $stmt = $mysqli->query($sql);
    $data = $stmt->fetch_assoc();
    echo $data['image_count'];
} else {
    header("Location: ../not_found.html");
}