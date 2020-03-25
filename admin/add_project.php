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
                        <h1>Add Project</h1>                        
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

                                         $sql = "SELECT p.id, p.name, p.image1 "
                                                . ", p.description, p.location,  p.category_id, p.status FROM projects p WHERE p.id = '$id'";
                                        $stmt = $mysqli->query($sql);

                                        $result = $stmt->fetch_array();
                                    }
                                    ?>
                                    <form action="controller/save_project.php" enctype="multipart/form-data" method="post" id="contentForm">                                        
                                        <input type="hidden" id="project_id" name="project_id" value="<?php
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
                                                <label for="category_id">Category</label>
                                                <select name="category_id" id="category_id" class="form-control required">
                                                    <option value="">Choose...</option>
                                                    <?php
                                                    
                                                    foreach (get_category_list() as $type) {
                                                        $selected = '';
                                                        if($result['category_id']==$type['id'])
                                                            $selected = 'selected';
                                                        ?>
                                                        <option <?php echo $selected; ?> value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group" id="project_image_div">
                                                <label for="project_image">Project Image</label>
                                                <?php 
                                                $display = '';
                                                if (count($result) > 0 && $result['image1']!='') {
                                                    $display = 'style = "display:none"';
                                                }
                                                ?>
                                                <div id="showFile" <?php echo $display;?> ><input type="file" name="image1" class="inputField form-control" id="image1" placeholder="" /></div>
                                                <?php 
                                                if (count($result) > 0 && $result['image1']!='') {
                                                   echo "<div id='showImage'><img id='display_image' src='upload/project_image/".$result['image1']."' width=50 height=50 />";
                                                   echo "<a href='#' onClick='deleteImage();'  title='Delete Image'><i class='fa fa-trash-o'></i></a></div>";
                                                   echo "<input type='hidden' name='project_image_file_name' value=".$result['image1']." />"; 
                                                }
                                                ?>
                                                 
                                            </div>

                                            <div class="form-group">
                                                <label for="location">Description</label>
                                                <textarea  name="description" class="inputField form-control required" id="description" placeholder="Enter Description">
                                                    <?php
                                                if (isset($_SESSION['description'])) {
                                                    echo $_SESSION['description'];
                                                } else if (count($result) > 0) {
                                                    echo $result['description'];
                                                }
                                                ?>
                                                </textarea>
                                             </div>
                                            
                                            <div class="form-group">
                                                <label for="location">Location</label>
                                                <input type="text" value="<?php
                                                if (isset($_SESSION['location'])) {
                                                    echo $_SESSION['location'];
                                                } else if (count($result) > 0) {
                                                    echo $result['location'];
                                                }
                                                ?>" name="location" class="inputField form-control required" id="location" placeholder="Enter Location" />
                                            </div>
                                            
                                            <div class="form-group">                                                
                                                <label for="status">Status</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>

                                            </div>

                                            
                                        </div><!-- /.box-body -->

<!--                                        <input type="hidden" value="7" name="id" />
                                        <input type="hidden" value="7" name="type" />-->

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
                    $('#contentForm').validate({
                        rules:{
                            image1:{
                                required: true,  
                            }
                        }
                    });

//                    $('#type_id').change(function () {
//                        typIdChangeCondition()
//                    });
//                    typIdChangeCondition();
                });
                function deleteImage(){
                    project_id = $('#project_id').val();
                    $.ajax({
                        url: "controller/delete_project_image.php",
                        type: 'POST',
                        data: {
                            project_id: project_id
                        },
                        success: function (data) {
                            if(data=='success'){
                                $('#showFile').show();
                                $('#showImage').hide();
                            }
                        }
                    });
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

