<?php

session_start();
require '../../config/db.php';
//echo $dirname = dirname(__FILE__);
define("PROJECT_GALLERY_UPLOAD_DIR", "../upload/project_image/gallery/");

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {

    $name = $_POST['name'];
    $image_id = $_POST['image_id'];
    $project_id = $_POST['project_id'];
    $status = $_POST['status'];


    if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

        if (!empty($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
            $galImageFile = $_FILES['image']['name'];
            $temp = explode('.', $galImageFile);
            $gal_file_ext = strtolower(end($temp));
            $gal_image_file_name = "gallery_" . $name . "_" . date('dmYhis') . "." . $gal_file_ext;

            $uploadStatus = upload_image($gal_image_file_name, PROJECT_GALLERY_UPLOAD_DIR, "image");
        } else {
            $uploadStatus = "";
        }

        if ($uploadStatus == "success") {
            save_or_update_data($image_id, $name, $project_id, $status, $gal_image_file_name);
        } else {
            set_sessiondata($name, $category_id, $video_link, $reviews_link, $status);
            header("Location: ../add_gallery_image.php?id=$project_id&error=" . implode('\n', $uploadStatus));
        }
    } else {
        header("Location: ../not_found.html");
    }
}

function save_or_update_data($image_id, $name, $project_id, $status, $gal_image_file_name) {
    $db = Database::getInstance();
    $mysqli = $db->getConnection();

    if (empty($image_id)) {
       $sql = "INSERT INTO project_image (name, project_id, image, status) "
                . "VALUES ('$name', '$project_id', '$gal_image_file_name', '$status')";
        $mysqli->query($sql);
        $lastInsertId = $mysqli->insert_id;
//        echo $sql;
//        die;
        if ($lastInsertId > 1) {
            header("Location: ../project_gallery.php?project_id=" . $project_id);
        } else {
            set_sessiondata($name, $project_id, $status);
            header("Location: ../add_gallery_image.php?project_id=" . $project_id . "&error=Unable to save data, try again !");
        }
    } else {
        $sql = "UPDATE project_gallery SET name = '$name', project_id = '$project_id', ";

        $selectQuery = "SELECT image FROM project_gallery WHERE id = '$project_id'";
        $stmt = $mysqli->query($selectQuery);
        $selectedData = $stmt->fetch_array();

//        if (isset($thumb_image_file_name) && $thumb_image_file_name != '') {
//            $sql .= "image = '$thumb_image_file_name', ";
//            //unlink(BASE_DIR . "upload/thumb_image/" . $selectedData['image']);            
//        }
//        if (isset($content) && $content != '') {
//            $sql .= "content = '$content', ";
//            if (file_exists(BASE_DIR . "upload/articles_image/" . $selectedData['content'])) {
//                //unlink(BASE_DIR . "upload/articles_image/" . $selectedData['content']);
//            }
//        }
        $sql .= "status = '$status', modified_date = NOW() WHERE id = '$project_id'";

        $result = $mysqli->query($sql);

        if ($result == 1) {
            header("Location: ../project_gallery.php");
        } else {
            set_sessiondata($name, $category_id, $video_link, $reviews_link, $status);
            header("Location: ../add_project_gallery.php?id=$image_id&error=Unable to update data, try again !");
        }
    }
}

function set_sessiondata($name, $project_id, $status) {
    $_SESSION['name'] = $name;
    $_SESSION['project_id'] = $project_id;
    $_SESSION['status'] = $status;
}

function upload_image($file_name, $upload_dir, $image_name) {
    
    if (isset($_FILES[$image_name]) && $_FILES[$image_name]['name'] != '') {
        $errors = array();
        $fileName = $_FILES[$image_name]['name'];
        $temp = explode('.', $fileName);
        $file_ext = strtolower(end($temp));
//    $file_name = $_FILES['image']['name'];
//    $file_name = "_" . $whats_new_name . "_" . $lastInsertId .".". $file_ext;
        $file_size = $_FILES[$image_name]['size'];
        $file_tmp = $_FILES[$image_name]['tmp_name'];
        $file_type = $_FILES[$image_name]['type'];


        $expensions = array("jpeg", "jpg", "png");

        if (in_array($file_ext, $expensions) === false) {
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }

        if ($file_size > 3145728) {
            $errors[] = 'File size must be less than 3 MB';
        }
       
        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, $upload_dir . $file_name);
            return "success";
        } else {
            return $errors;
        }
    }
}

?>
