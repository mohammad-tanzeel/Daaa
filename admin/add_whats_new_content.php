<?php session_start(); ?>
<?php
require '../config/db.php';
require 'function.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Add Whats New Content | Suncros Admin</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="src/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="src/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="src/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="src/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="src/css/custom.css" rel="stylesheet" type="text/css"/>


    </head>
    <body class="skin-blue">
        <?php
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
            ?>
            <header class="header">
                <?php include 'header.php'; ?>
            </header>
            <div class="wrapper row-offcanvas row-offcanvas-left">
                <!-- Left side column. contains the logo and sidebar -->
                <aside class="left-side sidebar-offcanvas">
                    <?php include 'sidebar.php'; ?>
                </aside>

                <!-- Right side column. Contains the navbar and content of the page -->
                <aside class="right-side">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <h1>Add Whats New Content</h1>                        
                    </section>
                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-6">
                                <!-- general form elements -->
                                <div class="box box-primary">                                    
                                    <div>
                                        <label class="error">
                                            <?php
                                            if (isset($_GET['error'])) {
                                                print_r($_GET['error']);
                                            }
                                            ?>
                                        </label>
                                        <label class="success" id="successmessage"></label>
                                    </div>
                                    <!-- form start --> 
                                    <?php
                                    $result = array();
                                    if (isset($_GET['id'])) {
                                        $id = $_GET['id'];

                                        $db = Database::getInstance();
                                        $mysqli = $db->getConnection();

                                        $sql = "SELECT wnc.id, wnc.name, wnc.image, wnc.content,"
                                                . " wnc.type_id, wnc.status FROM s_whats_new_content wnc WHERE wnc.id = '$id'";
                                        $stmt = $mysqli->query($sql);
                                        
                                        $result = $stmt->fetch_array();                                        
                                    }
                                    ?>
                                    <form action="controller/save_whats_new.php" enctype="multipart/form-data" method="post" id="contentForm">                                        
                                        <input type="hidden" name="whats_new_id" value="<?php
                                        if (count($result) > 0) {
                                            echo $result['id'];
                                        }
                                        ?>" />
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" value="<?php
                                                if (isset($_SESSION['name'])) {
                                                    echo $_SESSION['name'];
                                                } else if (count($result) > 0) {
                                                    echo $result['name'];
                                                }
                                                ?>" name="name" class="inputField form-control required" id="first_name" placeholder="Enter Name" />
                                            </div>

                                            <div class="form-group">
                                                <label for="type_id">Whats New Type</label>
                                                <select name="type_id" id="type_id" class="form-control required">
                                                    <option value="">Choose...</option>
                                                    <?php
                                                    foreach (get_type_list() as $type) {
                                                        ?>
                                                        <option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="image">Thumb Image</label>
                                                <input type="file"  name="image" id="image" class="form-control" />
                                                <?php 
                                                
                                                ?>
                                            </div>

                                            <div class="form-group" id="article_image_div">
                                                <label for="article_image">Article Image</label>
                                                <input type="file" name="article_image" class="inputField form-control" id="article_image" placeholder="Enter Specialist Name" />
                                            </div>

                                            <div class="form-group" id="video_link_div">
                                                <label for="video_link">Video Link</label>
                                                <input type="text" value="<?php
                                                if (isset($_SESSION['video_link'])) {
                                                    echo $_SESSION['video_link'];
                                                } else if (count($result) > 0 && $result['type_id'] == 2) {
                                                    echo $result['content'];
                                                }
                                                ?>" name="video_link" class="inputField form-control required youtubeurl" id="video_link" placeholder="Enter Video link" />
                                            </div>

                                            <div class="form-group" id="reviews_link_div">
                                                <label for="reviews_link">Reviews Link</label>
                                                <input type="text" value="<?php
                                                if (isset($_SESSION['reviews_link'])) {
                                                    echo $_SESSION['reviews_link'];
                                                } else if (count($result) > 0 && $result['type_id'] == 3) {
                                                    echo $result['content'];
                                                }
                                                ?>" name="reviews_link" class="inputField form-control required validurl" id="reviews_link" placeholder="Enter review link" />
                                            </div>

                                            <div class="form-group">                                                
                                                <label for="status">Status</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>

                                            </div>


                                        </div><!-- /.box-body -->

                                        <input type="hidden" value="7" name="id" />
                                        <input type="hidden" value="7" name="type" />

                                        <div class="box-footer">
                                            <input type="submit" value="Submit" id="save_city" class="submitBtns btn btn-primary" />
                                            <input type="reset" value="Clear" id="clear" class="submitBtns btn btn-primary" />
                                        </div>
                                    </form>
                                </div><!-- /.box -->

                            </div><!--/.col (left) -->                            
                        </div>   <!-- /.row -->
                    </section><!-- /.content -->
                </aside><!-- /.right-side -->
            </div><!-- ./wrapper -->

            <!-- jQuery 2.0.2 -->
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
            <!-- Bootstrap -->
    <!--            <script src="src/js/jquery-2.1.1.js" type="text/javascript"></script>-->
            <script src="src/js/bootstrap.min.js" type="text/javascript"></script>
            <!-- AdminLTE App -->
            <script src="src/js/AdminLTE/app.js" type="text/javascript"></script>
            <!-- AdminLTE for demo purposes -->
            <script src="src/js/AdminLTE/demo.js" type="text/javascript"></script>

            <script src="src/js/validation/jquery.validate.js" type="text/javascript"></script>
            <script src="src/js/validation/additional-methods.js" type="text/javascript"></script>
            <script src="src/js/validation/jquery.maskedinput.js" type="text/javascript"></script>


            <script type="text/javascript">
                $(document).ready(function () {
                    $('#contentForm').validate();

                    $('#type_id').change(function () {
                        typIdChangeCondition()
                    });
                    typIdChangeCondition();
                });
                function typIdChangeCondition() {
                    if ($('#type_id').val() == 1) {
                        hideAndRemoveValidationOnVideoLink();
                        hideAndRemoveValidationOnReviewsLink();
                        showAndApplyValidationOnArticleImage();
                    } else if ($('#type_id').val() == 2) {
                        hideAndRemoveValidationOnArticleImage();
                        hideAndRemoveValidationOnReviewsLink();
                        showAndApplyValidationOnVideoLink();
                    } else if ($('#type_id').val() == 3) {
                        hideAndRemoveValidationOnVideoLink();
                        hideAndRemoveValidationOnArticleImage();
                        showAndApplyValidationOnReviewsLink();
                    } else {
                        showAndApplyValidationOnArticleImage();
                        showAndApplyValidationOnVideoLink();
                        showAndApplyValidationOnReviewsLink();
                    }
                }
                function hideAndRemoveValidationOnVideoLink() {
                    $('#video_link_div').hide();
                    $('#video_link').val('');
                    $('#video_link').removeClass('required');
                    $('#video_link').removeClass('youtubeurl');
                }
                function hideAndRemoveValidationOnReviewsLink() {
                    $('#reviews_link_div').hide();
                    $('#reviews_link').val('');
                    $('#reviews_link').removeClass('required');
                    $('#reviews_link').removeClass('validurl');
                }
                function hideAndRemoveValidationOnArticleImage() {
                    $('#article_image_div').hide();
                    $('#article_image').val('');                    
                }
                function showAndApplyValidationOnVideoLink() {
                    $('#video_link_div').show();
                    $('#video_link').addClass('required');
                    $('#video_link').addClass('youtubeurl');
                }
                function showAndApplyValidationOnReviewsLink() {
                    $('#reviews_link_div').show();
                    $('#reviews_link').addClass('required');
                    $('#reviews_link').addClass('validurl');
                }
                function showAndApplyValidationOnArticleImage() {
                    $('#article_image_div').show();                    
                }
            </script> 
            <?php if (isset($_SESSION['status'])) {
                ?>
                <script>
                    $('#status').val(<?php echo $_SESSION['status']; ?>);
                </script>
                <?php
            } else if (count($result) > 0) {
                ?>
                <script>
                    $('#status').val(<?php echo $result['status']; ?>);
                </script>
                <?php
            }
            if (isset($_SESSION['type_id'])) {
                ?>
                <script>
                    $('#type_id').val(<?php echo $_SESSION['type_id']; ?>);
                </script>
                <?php
            } else if (count($result) > 0) {
                ?>
                <script>                    
                    $('#type_id').val(<?php echo $result['type_id']; ?>);
                </script>
                <?php
            }
           
//            $("img[name=image-swap]").attr("src",$(this).val());
            unset($_SESSION['name']);
            unset($_SESSION['video_link']);
            unset($_SESSION['reviews_link']);
            unset($_SESSION['type_id']);
            unset($_SESSION['status']);            
            ?>
            <?php
        } else {
            header('Location: index.php');
        }
        ?>
    </body>
</html>

