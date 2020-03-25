<?php session_start(); ?>
<?php require '../config/db.php' ?>
<?php require 'function.php' ?>


<!DOCTYPE html>
<html>
    <head>
        <title>Project List | Suncros Admin</title>
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
            
            if (isset($_GET['project_id'])) {
                                        $id = $_GET['project_id'];

                                        $db = Database::getInstance();
                                        $mysqli = $db->getConnection();

                                         $sql = "SELECT p.id, p.name, p.image1 "
                                                . ", p.category_id, p.status FROM projects p WHERE p.id = '$id'";
                                        $stmt = $mysqli->query($sql);

                                        $result = $stmt->fetch_array();
                                        $project_name = $result['name'];
                                    }
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
                        <h1><?php echo $project_name; ?> Gallery</h1>                        
                    </section>
                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <!-- left column -->                            
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="box box-primary">                                    
                                    <!-- form start -->
                                    <div style="float: left; margin: 5px;">
                                        <button 
                                            onclick="openAddImageForm('<?php echo $_GET['project_id']; ?>');">Add Image</button>
                                    </div>
                                    <div  style="float: right;padding: 5px;">                                                                               
                                        <label>
                                            <label class="start"></label>&nbsp;-
                                            <label class="end"></label>&nbsp;of
                                            <label class="total"></label>
                                        </label>&nbsp;&nbsp;&nbsp;
                                        <button class="btn-active arrow-left"><i class="fa fa-angle-double-left"></i></button>
                                        <button class="btn-active arrow-right"><i class="fa fa-angle-double-right"></i></button>
                                        &nbsp;&nbsp;&nbsp;
                                        <select class="rec_limit" style="height: 25px;">                                    
                                            <?php
                                            $recordsPerPage = array(25, 50, 100);
                                            for ($i = 0; $i < count($recordsPerPage); $i++) {
                                                ?>
                                                <option value="<?php echo $recordsPerPage[$i]; ?>"><?php echo $recordsPerPage[$i]; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select> 
                                        <label>
                                            <button id="reset"><i class="fa fa-refresh"></i></button>
                                        </label>
                                    </div>   
            
        +
        <table width="100%" class="responsive data-table" cellspacing="0" id="project_list_table">
            <input type="hidden" name="project_id" id="project_id" value="<?php echo $_GET['project_id']; ?>" />
                                        <thead>
                                            <tr class="table-head">
                                                <th width="6%">#<br/>&nbsp;</th>                
                                                <th width="10%">
                                                    Name<br/>
                                                    <input type="text" id="name" class="" placeholder="Enter name to search"/>
                                                </th>
                                                                                                <th width="20%">Image<br/>&nbsp;</th>                                                
                                                <th width="5%">
                                                    Status<br/>
                                                    <select id="status" style="height: 25px;">                                    
                                                        <option value="">All</option>
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </th>
                                                <th width="12%">Created<br/>&nbsp;</th>
                                                <th width="12%">Modified<br/>&nbsp;</th>
                                                <th width="5%">Action<br/>&nbsp;</th>
                                            </tr>
                                        </thead>

                                    </table>   

                                </div><!-- /.box -->

                            </div><!--/.col (left) -->
                        </div>   <!-- /.row -->
                    </section><!-- /.content -->
                </aside><!-- /.right-side -->
            </div><!-- ./wrapper -->
            <!-- jQuery 2.0.2 -->
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <!--            <script src="src/js/jquery-2.1.1.js" type="text/javascript"></script>-->
            <!-- Bootstrap -->
            <script src="src/js/bootstrap.min.js" type="text/javascript"></script>
            <!-- AdminLTE App -->
            <script src="src/js/AdminLTE/app.js" type="text/javascript"></script>
            <!-- AdminLTE for demo purposes -->
            <script src="src/js/AdminLTE/demo.js" type="text/javascript"></script>

            <script src="src/js/validation/jquery.validate.js" type="text/javascript"></script>
            <script src="src/js/validation/additional-methods.js" type="text/javascript"></script>
            <script src="src//js/validation/jquery.maskedinput.js" type="text/javascript"></script>

            <script type="text/javascript">
                $(document).ready(function () {
                    rec_limit = $('.rec_limit').val();
                    project_list();
                    $('#name').keydown(function (event) {
                        if (event.keyCode == 13) {
                            start = 0;
                            project_list();
                        }
                    });

                    $('#reset').click(function () {
                        $('#type_id').val('');
                        $('#status').val('');
                        start = 0;
                        project_list();
                    });

                    $('#type_id').click(function () {
                        start = 0;
                        project_list();
                    });
                    $('#status').click(function () {
                        start = 0;
                        project_list();
                    });

                    //for paging to move right
                    $('.arrow-right').click(function () {
                        start = fetch_record + start;
                        project_list();
                        $('#check_uncheck').prop('checked', false);
                        $('.open_push_notif_form_btn').hide();
                        $('.open_push_notif_form_btn_for_all').show();
                    });

                    //for paging to move left
                    $('.arrow-left').click(function () {
                        start = start - rec_limit;
                        project_list()();
                        $('#check_uncheck').prop('checked', false);
                        $('.open_push_notif_form_btn').hide();
                        $('.open_push_notif_form_btn_for_all').show();

                    });

                    //to cange record limit value
                    $('.rec_limit').change(function () {
                        start = 0;
                        no_of_times_called = 0;
                        rec_limit = $(this).val();
                        project_list();
                    });

                });

                var rec_limit;
                var start = 0;
                var fetch_record;
                function project_list() {
                    var name = $('#name').val().trim();
                    var project_id = $('#project_id').val().trim();
                    var status = $('#status').val().trim();
                    $.getJSON(
                            'controller/get_project_gallery_list.php',
                            {
                                name: name,
                                project_id: project_id,
                                status: status,
                                start: start,
                                record_limit: rec_limit
                            },
                            function (data) {
//                                debugger;
                                fetch_record = data.length;
                                $('.start').text(start + 1);
                                $('.end').text(start + fetch_record);
                                totalRecord();
                                sta = addData(data);
                            });
                }

                //This method is used to count total record on the basis of textbox value
                function  totalRecord() {
                    var name = $('#name').val().trim();
                    var project_id = $('#project_id').val().trim();
                    var status = $('#status').val().trim();
                    $.ajax({
                        url: "controller/project_gallery_total.php",
                        type: 'POST',
                        data: {
                            name: name,
                            project_id: project_id,
                            status: status
                        },
                        success: function (data) {
                            if ($('.start').html() == 1 || $('.start').html() == 0) {
                                $('.arrow-left').removeClass('btn-active');
                                $('.arrow-left').addClass('btn-disable');
                                $('.arrow-left').attr('disabled', true);
                            } else {
                                $('.arrow-left').attr('disabled', false);
                                $('.arrow-left').removeClass('btn-disable');
                                $('.arrow-left').addClass('btn-active');
                            }

                            if ($('.end').html() == data || $('.end').html() == 0) {
                                $('.arrow-right').removeClass('btn-active');
                                $('.arrow-right').addClass('btn-disable');
                                $('.arrow-right').attr('disabled', true);
                            } else {
                                $('.arrow-right').removeClass('btn-disable');
                                $('.arrow-right').addClass('btn-active');
                                $('.arrow-right').attr('disabled', false);
                            }

                            $('.total').text(data);

                        }
                    });
                }



                //This method is called to add data 
                function addData(data) {
                    deleteRow();

                    if (data == false) {
                        emptyRow();
                    } else {
                        $('.table-head').show(1);
                    }
                    var j = start + 1;
                    for (i = 0; i < data.length; i++) {
                        addRow(data[i], j);
                        j++;
                    }
                    return true;
                }

                //This method is called to empty the table.
                function emptyRow() {
                    $('table#project_list_table').append("<tr></tr>");
                    $('table#project_list_table tr:last').addClass('no-record-found');
                    $('table#project_list_table tr:last').append("<td colspan='8'>No Record Found</td>");
                    $('.start').text(0);
                    $('.end').text(0);
                    //        $('.table-head').hide(1);
                }

                //This method is called to delete the rows
                function deleteRow() {
                    var rowCount = $('table#project_list_table tr').length;
                    for (var i = 1; i < rowCount; i++) {
                        $('table#project_list_table tr:last').remove();
                    }
                }

                //This method is called to add Buyers users data into the table
                function addRow(row, sno) {
                    $('table#project_list_table').append("<tr>");
                    $('table#project_list_table tr:last').append("<td>" + sno + "</td>");
                    $('table#project_list_table tr:last').append("<td>" + row.name + "</td>");
                    $('table#project_list_table tr:last').append("<td><img src=upload/project_image/gallery/" + row.image + " height=100 width=100 /></td>");
                    var status = row.status == 1 ? "Active" : "Inactive";
                    $('table#project_list_table tr:last').append("<td>" + status + "</td>");
                    $('table#project_list_table tr:last').append("<td>" + row.created_date + "</td>");
                    $('table#project_list_table tr:last').append("<td>" + row.modified_date + "</td>");
                    var actionLink = "<td>\n\
                        <a href='add_whats_new_content.php?id=" + row.id + "'  title='Edit whats new content'><i class='fa fa-pencil'></i></a> ";                        
                    if (row.wnc_status == 0) {
                        actionLink += "<a href='#' onclick=delete_data(" + row.id + ") title='Delete whats new cotent'><i class='fa fa-trash-o'></i></a> ";
                        
                    }
                    actionLink += "</td>";

                    $('table#project_list_table tr:last').append(actionLink);
                }                

                function delete_data(id) {
                    if (confirm("Are you sure to delete the whats new content permanently!")) {
                        $.ajax({
                            url: "controller/delete_whats_new_content.php",
                            data: {id: id},
                            type: 'POST',
                            success: function (data) {
                                if (data == 1) {
                                    alert("Data deleted successfully !");
                                    project_list();
                                } else {
                                    alert("Uanble to delete the data !");
                                }
                            }
                        });
                    }
                }
                function view_gallery(id)
                {
                    window.location.href = "project_gallery.php";
                }
                function openAddImageForm(pid) {
                    window.location.href = "add_gallery_image.php?project_id="+pid;
                }
            </script>
            <?php
        } else {
            header('Location: index.php');
        }
        ?>
    </body>
</html>
