<?php 

ob_start();
session_start(); ?>
<?php require '../config/db.php' ?>
<?php require 'function.php' ?>

<!DOCTYPE html>
<html class="bg-black">
  <head>
    <meta charset="UTF-8">
    <title>Suncros Admin | Log in</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- bootstrap 3.0.2 -->
    <link href="src/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    <link href="src/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="src/css/AdminLTE.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
    <body class="bg-black">
      <?php
        if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
			//echo "tets==";
          header('location: project_list.php');
        }
        else {
          //echo $encryptPassword = encrypt_decrypt('encrypt', 'my_passwordMax');
          //echo "<br/>";
          //echo $encryptPassword = encrypt_decrypt('decrypt', $encryptPassword);
          //echo "<br/>";
          include 'login.php';
        }
      ?>

      <!-- jQuery 2.0.2 -->
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
      <!-- Bootstrap -->
      <script src="src/js/bootstrap.min.js" type="text/javascript"></script>
  </body>
</html>
