<?php

/**
 * function for encrypt and decrypt user password
 *
 * @param string $action: can be 'encrypt' or 'decrypt'
 * @param string $string: string to encrypt or decrypt
 */
function encrypt_decrypt($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = '9716545921';
    $secret_iv = '1295456179';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

/**
 * function for user login
 *
 * @param string $action: can be 'encrypt' or 'decrypt'
 * @param string $string: string to encrypt or decrypt
 */
function _check_user_login($userName, $password) {
    $status = 0;
    if (isset($userName) && isset($password)) {

        $encryptPassword = encrypt_decrypt('encrypt', $password); // encrypt userpassword
        $db = Database::getInstance();
        $mysqli = $db->getConnection();

        $sql = sprintf("SELECT id FROM `s_user` WHERE user_name = '%s' AND password = '%s' AND status = %d", $mysqli->real_escape_string($userName), $mysqli->real_escape_string($encryptPassword), 1);
        $result = $mysqli->query($sql);

        $numRows = $result->num_rows;

        if (isset($numRows) && is_numeric($numRows) && $numRows > 0) {
            $res = $result->fetch_assoc();
            if (is_numeric($res['id']) && $res['id'] > 0) {
                //$status = 1;
                $_SESSION['is_admin'] = 1;
                $_SESSION['username'] = $userName;
            }
        }
    }

    return $status;
}

/**
 * function for check is admin user login or not
 */
function _is_admin() {
    $isAdmin = 0;
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
        $isAdmin = 1;
    }

    return $isAdmin;
}

function get_category_list() {
    $db = Database::getInstance();
    $mysqli = $db->getConnection();

    $sql = "SELECT id, name FROM project_category WHERE status = 1";
    $stmt = $mysqli->query($sql);

    $data = array();
    while ($row = $stmt->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}
