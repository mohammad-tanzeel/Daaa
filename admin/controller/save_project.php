<?php

session_start();
require '../../config/db.php';
//echo $dirname = dirname(__FILE__);
//define("THUMBNAIL_UPLOAD_DIR", "../upload/thumb_image/");
define("PROJECT_UPLOAD_DIR", "../upload/project_image/");
//define("BASE_DIR", $_SERVER['DOCUMENT_ROOT'] . "/~suncros/admin/");

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {

    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $status = $_POST['status'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $project_id = $_POST['project_id'];
    $project_image_file_name = $_POST['project_image_file_name'];
    

    if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

        if (!empty($_FILES['image1']['name']) && $_FILES['image1']['name'] != '') {
            $projectImageFile = $_FILES['image1']['name'];
            $temp = explode('.', $projectImageFile);
            $project_file_ext = strtolower(end($temp));
            $project_image_file_name = "project_" . $name . "_" . date('dmYhis') . "." . $project_file_ext;
            //upload article image
//                $project_dir = "D:/xampp1/htdocs/daaa/admin/upload/project_image/";
            $uploadStatus1 = upload_image($project_image_file_name, PROJECT_UPLOAD_DIR, "image1");
            $upload[] = $project_image_file_name;
        } else {
            $uploadStatus1 = "";
        }

        if ($uploadStatus1 == "success" || $project_id!='') {
//                $content = $project_image_file_name1;
            if (isset($_FILES['photos'])) {
                $fileArray = $_FILES['photos'];
                $response = validateFiles($fileArray);
                if ($response['status'] == 'true') {
                    upload_gallery($fileArray);
                } else {
//                    echo    $error = $response['fileinvalid']['message'];
                    header("Location: ../add_project.php?id=$project_id&error=" . implode('\n', $response['fileinvalid']['message']));
                }
            }
            save_or_update_data($project_id, $name, $category_id, $status, $description, $project_image_file_name, $location);
        } else {
            set_sessiondata($name, $category_id, $video_link, $reviews_link, $status);
            header("Location: ../add_project.php?id=$project_id&error=" . implode('\n', $uploadStatus));
        }
    } else {
        header("Location: ../not_found.html");
    }
}

//function upload_gallery($fileArray){
//    
//        foreach ($fileArray['name'] as $name => $value)
//            {
//                    $filename = stripslashes($fileArray['name'][$name]);
//                    $image_name=time().$filename;
////                   echo "<img src='".$uploaddir.$image_name."' class='imgList'>";
//                   $newname=$uploaddir.$image_name;
//                   
//                   
//                   if (!empty($fileArray['name'][$name]) && $fileArray['name'][$name] != '') {
//				$galImageFile = $fileArray['name'][$name];
//				$temp = explode('.', $galImageFile1);
//                $gal_file_ext = strtolower(end($temp));
//                $gal_image_file_name = "project_" . $name . "_" . date('dmYhis') . "." . $gal_file_ext;
//                //upload article image
////                $project_dir = "D:/xampp1/htdocs/daaa/admin/upload/project_image/";
//                $uploadStatus1 = upload_image($gal_image_file_name, PROJECT_UPLOAD_DIR, "image1");
//                $upload[] = $project_image_file_name1;
//            } else {
//                $uploadStatus1 = "";
//            }
//    }
//}
function save_or_update_data($project_id, $name, $category_id, $status, $description, $project_image_file_name1, $location) {
    $db = Database::getInstance();
    $mysqli = $db->getConnection();

    if (empty($project_id)) {
        $sql = "INSERT INTO projects (name, category_id, image1, description, location, status) "
                . "VALUES ('$name', '$category_id', '$project_image_file_name1', '$description', '$location', '$status')";
//        echo $sql;
//        die;
        $mysqli->query($sql);
        $lastInsertId = $mysqli->insert_id;

        if ($lastInsertId > 1) {
            header("Location: ../project_list.php");
        } else {
            set_sessiondata($name, $category_id, $description, $location, $status);
            header("Location: ../add_project.php?error=Unable to save data, try again !");
        }
    } else {
        $sql = "UPDATE projects SET name = '$name', category_id = '$category_id', description='$description', location='$location'";

        if (isset($project_image_file_name1) && $project_image_file_name1 != '') {
            $sql .= ", image1 = '$project_image_file_name1', ";
            //unlink(BASE_DIR . "upload/thumb_image/" . $selectedData['image']);            
        }

        $sql .= "status = '$status', modified_date = NOW() WHERE id = '$project_id'";
//        die;
        $result = $mysqli->query($sql);

        if ($result == 1) {
            header("Location: ../project_list.php");
        } else {
            set_sessiondata($name, $category_id, $video_link, $reviews_link, $status);
            header("Location: ../add_project.php?id=$project_id&error=Unable to update data, try again !");
        }
    }
}

function set_sessiondata($name, $category_id, $description, $location, $status) {
    $_SESSION['name'] = $name;
    $_SESSION['type_id'] = $category_id;
    $_SESSION['description'] = $description;
    $_SESSION['location'] = $location;
    $_SESSION['status'] = $status;
}

function upload_image($file_name, $upload_dir, $image_name) {
//    echo dirname(__FILE__).'./';
//    die;
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
//        echo $upload_dir.$file_name;
//        die;
        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, $upload_dir . $file_name);
            return "success";
        } else {
            return $errors;
        }
    }
}

?>
