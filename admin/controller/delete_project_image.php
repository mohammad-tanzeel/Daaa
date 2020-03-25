<?php

session_start();
require '../../config/db.php';
$response = 0;
if(isset($_POST['project_id']) && $_POST['project_id']!=''){
    $project_id  = $_POST['project_id'];
    $db = Database::getInstance();
    $mysqli = $db->getConnection();
    $sql = "UPDATE projects SET image1 = '' where id='$project_id'";
    $result = $mysqli->query($sql);
        if ($result == 1) {
            $response = 'success';
        }        
}
echo $response;
