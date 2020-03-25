<?php

session_start();
require '../../config/db.php';
define("THUMBNAIL_UPLOAD_DIR", "../upload/thumb_image/");
define("ARTICLE_UPLOAD_DIR", "../upload/articles_image/");
define("ARTICLES", 1);
define("VIDEOS", 2);
define("REVIEWS", 3);
define("BASE_DIR", $_SERVER['DOCUMENT_ROOT'] . "/~suncros/admin/");

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    $name = $_POST['name'];
    $type_id = $_POST['type_id'];
    $status = $_POST['status'];
    $whats_new_id = $_POST['whats_new_id'];

    $video_link = $_POST['video_link'];
    $reviews_link = $_POST['reviews_link'];
    if ($type_id == ARTICLES) {
        $content = "";
    } else if ($type_id == VIDEOS) {
        $content = $video_link;
    } else if ($type_id == REVIEWS) {
        $content = $reviews_link;
    }

    if (!empty($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
		$thumbImageFile = $_FILES['image']['name'];
		$temp = explode('.', $thumbImageFile);
        $thumb_file_ext = strtolower(end($temp));
        $thumb_image_file_name = "thumb_" . $name . "_" . date('dmYhis') . "." . $thumb_file_ext;
        //upload thumb image
        $uploadStatus = upload_image($thumb_image_file_name, THUMBNAIL_UPLOAD_DIR, "image");
    } else {
        $uploadStatus = "";
    }
    if ($uploadStatus == "success" || $uploadStatus == "") {
        if ($type_id == 1) {
            if (!empty($_FILES['article_image']['name']) && $_FILES['image']['name'] != '') {
				$articleImageFile = $_FILES['article_image']['name'];
				$temp = explode('.', $articleImageFile);
                $article_file_ext = strtolower(end($temp));
                $article_image_file_name = "article_" . $name . "_" . date('dmYhis') . "." . $article_file_ext;
                //upload article image
                $uploadStatus = upload_image($article_image_file_name, ARTICLE_UPLOAD_DIR, "article_image");
            } else {
                $uploadStatus = "";
            }

            if ($uploadStatus == "success" || $uploadStatus == "") {
                $content = $article_image_file_name;
                save_or_update_data($whats_new_id, $name, $type_id, $video_link, $reviews_link, $status, $content, $thumb_image_file_name);
            } else {
                set_sessiondata($name, $type_id, $video_link, $reviews_link, $status);
                header("Location: ../add_whats_new_content.php?id=$whats_new_id&error=" . implode('\n', $uploadStatus));
            }
        } else {
            save_or_update_data($whats_new_id, $name, $type_id, $video_link, $reviews_link, $status, $content, $thumb_image_file_name);
        }
    } else {
        set_sessiondata($name, $type_id, $video_link, $reviews_link, $status);
        header("Location: ../add_whats_new_content.php?id=$whats_new_id&error=" . implode('\n', $uploadStatus));
    }
} else {
    header("Location: ../not_found.html");
}

function save_or_update_data($whats_new_id, $name, $type_id, $video_link, $reviews_link, $status, $content, $thumb_image_file_name) {
    $db = Database::getInstance();
    $mysqli = $db->getConnection();

    if (empty($whats_new_id)) {
        $sql = "INSERT INTO s_whats_new_content (name, type_id, image, content, status) "
                . "VALUES ('$name', '$type_id', '$thumb_image_file_name', '$content', '$status')";
        $mysqli->query($sql);
        $lastInsertId = $mysqli->insert_id;

        if ($lastInsertId > 1) {
            header("Location: ../whats_new_content_list.php");
        } else {
            set_sessiondata($name, $type_id, $video_link, $reviews_link, $status);
            header("Location: ../add_whats_new_content.php?error=Unable to save data, try again !");
        }
    } else {
        $sql = "UPDATE s_whats_new_content SET name = '$name', type_id = '$type_id', ";
        
        $selectQuery = "SELECT image, content FROM s_whats_new_content WHERE id = '$whats_new_id'";
        $stmt = $mysqli->query($selectQuery);        
        $selectedData = $stmt->fetch_array();
        
        if (isset($thumb_image_file_name) && $thumb_image_file_name != '') {
            $sql .= "image = '$thumb_image_file_name', ";
            //unlink(BASE_DIR . "upload/thumb_image/" . $selectedData['image']);            
        }
        if (isset($content) && $content != '') {
            $sql .= "content = '$content', ";
            if (file_exists(BASE_DIR . "upload/articles_image/" . $selectedData['content'])) {
                //unlink(BASE_DIR . "upload/articles_image/" . $selectedData['content']);
            }
        }
        $sql .= "status = '$status', modified_date = NOW() WHERE id = '$whats_new_id'";
        
        $result = $mysqli->query($sql);

        if ($result == 1) {
            header("Location: ../whats_new_content_list.php");
        } else {
            set_sessiondata($name, $type_id, $video_link, $reviews_link, $status);
            header("Location: ../add_whats_new_content.php?id=$whats_new_id&error=Unable to update data, try again !");
        }
    }
}

function set_sessiondata($name, $type_id, $video_link, $reviews_link, $status) {
    $_SESSION['name'] = $name;
    $_SESSION['type_id'] = $type_id;
    $_SESSION['video_link'] = $video_link;
    $_SESSION['reviews_link'] = $reviews_link;
    $_SESSION['status'] = $status;
}

function upload_image($file_name, $upload_dir, $image_name) {
    if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {

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
