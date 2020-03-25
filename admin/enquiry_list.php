<?php session_start(); ?>
<?php require '../config/db.php' ?>
<?php require 'function.php' ?>


<!DOCTYPE html>
<html>
    <head>
        <title>Suncros | Enquiry List</title>
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
                        <h1>Enquiry List</h1>                        
                    </section>
                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <!-- left column -->                            
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <h3 class="box-title">Enquiry List</h3>
                                    </div><!-- /.box-header -->
                                    <!-- form start -->

                                    <div  style="float: right;padding: 5px;">
                                        <label>
                                            <input type="text" id="searchtext" class="form-control" placeholder="Enter name or email to search"/>                                            
                                        </label>
                                        <label>
                                            <button id="searchenquiry"><i class="fa fa-search"></i>Search</button>
                                        </label>
                                        <label style="margin-right: 100px;">
                                            <button id="reset"><i class="fa fa-refresh"></i></button>
                                        </label>
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
                                    </div>   
                                    <table width="100%" class="responsive data-table" cellspacing="0" id="enquiry_list_table">
                                        <thead>
                                            <tr class="table-head">
                                                <th width="6%">#</th>                
                                                <th width="15%">Name</th>
                                                <th width="10%">Email</th>
                                                <th width="8%">Phone</th>
                                                <th width="33%">Query</th>                                                
                                                <th width="5%">Status</th>
                                                <th width="8%">Created</th>                                                
                                                <th width="5%">Action</th>
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

            <script src="../../inspired-life/assets/user/js/validation/jquery.validate.js" type="text/javascript"></script>
            <script src="../../inspired-life/assets/user/js/validation/additional-methods.js" type="text/javascript"></script>
            <script src="../../inspired-life/assets/user/js/validation/jquery.maskedinput.js" type="text/javascript"></script>

            <script type="text/javascript">
                $(document).ready(function () {
                    rec_limit = $('.rec_limit').val();
                    enquiry_list();
                    $('#searchenquiry').click(function () {
                        start = 0;
                        enquiry_list();
                    });

                    $('#reset').click(function () {
                        $('#searchtext').val('');
                        start = 0;
                        enquiry_list();
                    });

                    //for paging to move right
                    $('.arrow-right').click(function () {
                        start = fetch_record + start;
                        enquiry_list();
                        $('#check_uncheck').prop('checked', false);
                        $('.open_push_notif_form_btn').hide();
                        $('.open_push_notif_form_btn_for_all').show();
                    });

                    //for paging to move left
                    $('.arrow-left').click(function () {
                        start = start - rec_limit;
                        enquiry_list()();
                        $('#check_uncheck').prop('checked', false);
                        $('.open_push_notif_form_btn').hide();
                        $('.open_push_notif_form_btn_for_all').show();

                    });

                    //to cange record limit value
                    $('.rec_limit').change(function () {
                        start = 0;
                        no_of_times_called = 0;
                        rec_limit = $(this).val();
                        $('.rec_limit').val(rec_limit);
                        enquiry_list();
                    });
                    //to cange record limit value
                    $('#searchtext').keypress(function (event) {
                        if (event.keyCode == 13) {
                            start = 0;
                            no_of_times_called = 0;
                            enquiry_list();
                        }
                    });

                });

                var rec_limit;
                var start = 0;
                var fetch_record;
                function enquiry_list() {
                    var searchtext = $('#searchtext').val().trim();
                    $.getJSON(
                            'controller/get_enquiry_list.php',
                            {
                                searchtext: searchtext,
                                start: start,
                                record_limit: rec_limit
                            },
                            function (data) {
                                fetch_record = data.length;
                                $('.start').text(start + 1);
                                $('.end').text(start + fetch_record);
                                totalRecord();
                                sta = addData(data);
                            });
                }

                //This method is used to count total record on the basis of textbox value
                function  totalRecord() {
                    var searchtext = $('#searchtext').val().trim();
                    $.ajax({
                        url: "controller/enquiry_total.php",
                        type: 'POST',
                        data: {
                            searchtext: searchtext
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



                //This method is called to add roulette users data 
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
                    $('table#enquiry_list_table').append("<tr></tr>");
                    $('table#enquiry_list_table tr:last').addClass('no-record-found');
                    $('table#enquiry_list_table tr:last').append("<td colspan='8'>No Record Found</td>");
                    $('.start').text(0);
                    $('.end').text(0);
                    //        $('.table-head').hide(1);
                }

                //This method is called to delete the rows
                function deleteRow() {
                    var rowCount = $('table#enquiry_list_table tr').length;
                    for (var i = 1; i < rowCount; i++) {
                        $('table#enquiry_list_table tr:last').remove();
                    }
                }

                //This method is called to add Buyers users data into the table
                function addRow(row, sno) {
                    $('table#enquiry_list_table').append("<tr>");
                    $('table#enquiry_list_table tr:last').append("<td>" + sno + "</td>");
                    $('table#enquiry_list_table tr:last').append("<td>" + row.name + "</td>");
                    $('table#enquiry_list_table tr:last').append("<td>" + row.email + "</td>");
                    $('table#enquiry_list_table tr:last').append("<td>" + row.phone + "</td>");
                    $('table#enquiry_list_table tr:last').append("<td>" + row.query + "</td>");                    
                    var status = row.status == 1 ? "Active" : "Inactive";
                    $('table#enquiry_list_table tr:last').append("<td>" + status + "</td>");
                    $('table#enquiry_list_table tr:last').append("<td>" + row.created + "</td>");                    
                    var actionLink = "<td>\n\
                            <a href='#' onclick=delete_enquiry(" + row.id + ") title='Delete doctor'><i class='fa fa-trash-o'></i></a>\n\
                        </td>";                    

                    $('table#enquiry_list_table tr:last').append(actionLink);
                }
               

                function delete_enquiry(id) {
                    if (confirm("Are you sure to delete the doctor permanently!")) {
                        $.ajax({
                            url: "controller/delete_enquiry.php",
                            data: {id: id},
                            type: 'POST',
                            success: function (data) {
                                if (data == 1) {
                                    alert("Enquiry deleted successfully !");
                                    enquiry_list();
                                } else {
                                    alert("Uanble to delete the enquiry !");
                                }
                            }
                        });
                    }
                }
            </script>
            <?php
        } else {
            header('Location: index.php');
        }
        ?>
    </body>
</html>
