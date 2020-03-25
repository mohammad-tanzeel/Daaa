<?php

session_start();
require '../../config/db.php';

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    $name = $_POST['name'];
    $type_id = $_POST['type_id'];
    $status = $_POST['status'];

    $sql = "SELECT COUNT(p.id) as project_count FROM projects p"
            . " LEFT JOIN project_category pc ON pc.id = p.category_id WHERE 1 ";
    if (!empty($name)) {
        $sql .= "AND p.name LIKE '$name%'";
    }
    if (!empty($type_id)) {
        $sql .= "AND p.category_id = '$type_id%'";
    }
    if ($status != "") {
        $sql .= "AND p.status = '$status%'";
    }
    $db = Database::getInstance();
    $mysqli = $db->getConnection();
    
    $stmt = $mysqli->query($sql);
    $data = $stmt->fetch_assoc();
    echo $data['project_count'];
} else {
    header("Location: ../not_found.html");
}