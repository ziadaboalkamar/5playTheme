

$(document).ready(function () {

    $('#appSelect').select2();
    var status = {
        0: {title: 'Not active', class: 'badge rounded-pill text-bg-danger'},
        1: {title: 'Active', class: 'badge rounded-pill text-bg-primary'},
    };
    jQuery('#appsTable').DataTable({
        "lengthMenu": [[10, 25, 50, 100, 500], [10, 25, 50, 100, "500"]],
        "processing": true,
        "serverSide": true,
        "ajax":{
            url: appsData.ajax_url,
            type: "get",
            dataType : "json",
            contentType: "application/json; charset=utf-8",
            error: function(){
                jQuery("#appsTable").append('<tbody class="grid-error"><tr><th colspan="2">error.</th></tr></tbody>');
            }
        },

        "autoWidth": false,
        "columnDefs": [{"defaultContent": "-","targets": "_all"}],

        "columns": [
            {data: 'record_select', orderable: false},
            {data: 'id', orderable: true},
            {data: 'name',  orderable: true},
            {data: 'package_name', orderable: true},
            {data: 'api_app_id', orderable: true},
            {data: 'posts', orderable: true},
            {data: 'status', orderable: true},
            {data: 'action'},

        ],
        // Buttons with Dropdown

        "initComplete": function(settings, json) {
            $('.select2').select2();

        }


    });

    // code to execute when the DOM is ready
    var dataTable = $('#appsTable');

    // check if the data table is ready
    if (dataTable.length) {
        $('.select2').select2();

    }
    jQuery('#websiteTable').DataTable({
        "lengthMenu": [[10, 25, 50, 100, 500], [10, 25, 50, 100, "500"]],
        "processing": true,
        "searching": false, // Disable searching
        "serverSide": true,
        "ajax":{
            url: appsData.ajax_url,
            type: "get",
            dataType : "json",
            contentType: "application/json; charset=utf-8",
            error: function(){
                jQuery("#websiteTable").append('<tbody class="grid-error"><tr><th colspan="2">error.</th></tr></tbody>');
            }
        },

        "autoWidth": false,

        "columns": [
            {data: 'id',  orderable: true},
            {data: 'name',  orderable: true},
            {data: 'url'},
            {data: 'status', orderable: true},

        ],
        "columnDefs": [{
            // Slider Status
            targets: 3,
            render: function (data, type, full, meta) {
                var $status = full['status'];

                return (
                    '<span class="' +
                    status[$status].class +
                    '" text-capitalized>' +
                    status[$status].title +
                    '</span>'
                );
            }
        },{"defaultContent": "-","targets": "_all"}



        ],

    });


    jQuery('#categoryTable').DataTable({
        "lengthMenu": [[10, 25, 50, 100, 500], [10, 25, 50, 100, "500"]],
        "processing": true,
        "searching": false, // Disable searching
        "serverSide": true,
        "ajax":{
            url: appsData.ajax_url,
            type: "get",
            dataType : "json",
            contentType: "application/json; charset=utf-8",
            error: function(){
                jQuery("#categoryTable").append('<tbody class="grid-error"><tr><th colspan="2">error.</th></tr></tbody>');
            }
        },

        "autoWidth": false,

        "columns": [
            {data: 'id',  orderable: true},
            {data: 'name',  orderable: true},
            {data: 'status', orderable: true},

        ],
        "columnDefs": [{
            // Slider Status
            targets: 2,
            render: function (data, type, full, meta) {
                var $status = full['status'];

                return (
                    '<span class="' +
                    status[$status].class +
                    '" text-capitalized>' +
                    status[$status].title +
                    '</span>'
                );
            }
        },{"defaultContent": "-","targets": "_all"}



        ],

    });


    // Add a click event listener to the submit button
    $(appsData.submit_id).click(function(e) {
        e.preventDefault(); // Prevent the form from submitting

        // Display a confirmation message using SweetAlert
        swal.fire({
            title: 'Are you sure?',
            text: 'You will empty all plugin tables !',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'No, cancel it'
        }).then(function (e) {

            if (e.isConfirmed) {
                // If the user clicks "OK", submit the form
                $('#hidden-submit').click();
            }



        });
    });

    // Add a click event listener to the submit button
    $("#bulk_action").click(function(e) {
        // Display a confirmation message using SweetAlert
        swal.fire({
            title: 'Are you sure?',
            text: 'You will disable all this Apps !',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'No, cancel it'
        }).then(function (e) {
            if (e.isConfirmed) {
       var ids =$ ('#record-ids').val();

                var type = "post";

                $.ajax({
                    type: type,
                    url: appsData5.bulk_disable_app,
                    data: {
                        "record_ids": ids,
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success == false){
                            swal.fire("sorry!", res.msg, "error");
                        }else{
                            swal.fire("Done", res.msg, "success");
// Reload the page after 0.5 seconds
                            setTimeout(function() {
                                location.reload();
                            }, 700);
                        }
                    },
                    error: function (data) {
                        toastr.error("we have some error!");
                    }
                });
            }



        });
    });
    var postTable = $('#postTable').DataTable({
        "searching": false, // Disable searching
        "lengthChange": false

    });

    // Bind change event to dropdown list
    $('#appSelect').on('change', function() {
        var appId = $(this).val();
        if (appId) {
            // Make Ajax request to retrieve posts
            $.ajax({
                url: appsData.ajax_url,
                type: 'GET',
                data: { 'app_id': appId },
                dataType: 'json',
                success: function(posts) {
                    var data = posts.aaData;
                    // Clear the DataTable
                    postTable.clear().draw();

                    // Add posts to the DataTable
                    data.forEach(function(data) {

                        postTable.row.add([data.id,data.post_title,data.lang_code,data.action]).draw();
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        } else {
            // Clear the DataTable if no app is selected
            postTable.clear().draw();
        }
    });
});
$(document).on('ajaxComplete', function() {
    $('.select2').select2();
    $(".country").selectBoxIt();
});
function connect_the_key(id,status){
    var type = "GET";

    $.ajax({
        type: type,
        url: appsData.ajax_url,
        data: {
            "id": id,
            "status": status,
        },
        dataType: 'json',
        success: function (res) {

            if (status == 1){
                $('#status_button_'+id+'').attr('onclick', 'connect_the_key('+id+',0)');
                $('#status_button_'+id+'').attr('class', 'btn btn-danger');
                $('#status_button_'+id+'').text("UnChecked");
                $('#status_button_'+id+'').attr('id', 'status_button_danger_'+id+'');
                toastr.success(res.msg);

            }else{
                $('#status_button_danger_'+id+'').attr('onclick', 'connect_the_key('+id+',1)');
                $('#status_button_danger_'+id+'').attr('class', 'btn btn-primary');
                $('#status_button_danger_'+id+'').text("Checked");
                $('#status_button_danger_'+id+'').attr('id', 'status_button_'+id+'');
                toastr.success(res.msg);

            }

        },
        error: function (data) {
            toastr.error("we have some error!");
        }
    });
}



function connect_the_post(id){
    var post = $('#post_id_'+id+'').val();
    console.log(post);

    var type = "GET";
    $.ajax({
        type: type,
        url: appsData2.post_app_url,
        data: {
            "id": id,
            "post_id": post,
        },
        dataType: 'json',
        success: function (res) {

            toastr.success(res.msg);
            $('.status-'+id+'').text("Joined")
        },
        error: function (data) {
            toastr.error("we have some error!");
        }
    });
}
function process_app(){
    var type = "GET";
    $(".loader").css("display","block");
    $.ajax({
        type: type,
        url: appsData3.process_app_url,
        dataType: 'json',
        success: function (res) {
            $(".loader").css("display","none");
            toastr.success(res.msg);
            setTimeout(function() {
                location.reload();
            }, 700);
        },
        error: function (data) {
            $(".loader").css("display","none");
            toastr.error("we have some error!");
        }
    });
}

function change_color(){
    var type = "GET";
    var table =  $('#exampleColorInput').val();
    var dw_button =  $('#DownloadColorInput').val();
    var  hover_line=  $('#hoverColorInput').val();
    var content_place =  $('#content_place').val();
    $.ajax({
        type: type,
        url: appsData.ajax_url,
        data: {
            "table": table,
            "dw_button": dw_button,
            "hover_line": hover_line,
            "content_place":content_place,
        },
        dataType: 'json',
        success: function (res) {

            toastr.success(res.msg);

        },
        error: function (data) {
            toastr.error("we have some error!");
        }
    });
}
function disable_app(id,status){
    if (status == 1){
        status = "disabled"
    }else{
        status="enabled"
    }

    var type = "GET";

    $.ajax({
        type: type,
        url: appsData4.disable_app_url,
        data: {
            "id": id,
            "status":status
        },
        dataType: 'json',
        success: function (res) {

            if (status != "disabled" ){
                var con = 1;
                $('#disabled_button_'+id+'').attr('onclick', 'disable_app('+id+','+con+')');
                $('#disabled_button_'+id+'').text("Disable");

                toastr.success(res.msg);
                $('.status-'+id+'').text("enabled");
            }else if(status == "disabled"){
                console.log(status)

                var dis = 0;
                $('#disabled_button_'+id+'').attr('onclick', 'disable_app('+id+','+dis+')');
                $('#disabled_button_'+id+'').text("Enable");
                toastr.success(res.msg);
                $('.status-'+id+'').text("disabled")
            }


        },
        error: function (data) {
            toastr.error("we have some error!");
        }
    });
}

function delete_post(id){
    var type = "GET";
    var row = $(this).closest('tr');
    var table = $('#postTable').DataTable();
    var rowIndex = table.row(row).index();
    $.ajax({
        type: type,
        url: appsData2.ajax_url,
        data: {
            "id": id,
        },
        dataType: 'json',
        success: function (res) {
            toastr.success(res.msg);
            var tdElements = document.querySelectorAll('td');
            tdElements.forEach(function(tdElement) {
                tdElement.addEventListener('click', function(event) {
                    // Get the clicked <td> element
                    var clickedTdElement = event.target;

                    // Get the parent <tr> element
                    var trElement = clickedTdElement.parentNode.parentNode;

                    // Remove the parent <tr> element
                    trElement.remove();
                });
            });
        },
        error: function (data) {
            toastr.error("we have some error!");
        }
    });
}

//Select_all
$(function(){
//select all functionality
    $(document).on('change', '.record__select', function () {
        getSelectedRecords();
    });

// used to select all records
    $(document).on('change', '#record__select-all', function () {

        $('.record__select').prop('checked', this.checked);
        getSelectedRecords();
    });
    function getSelectedRecords() {
        var recordIds = [];

        $.each($(".record__select:checked"), function () {
            recordIds.push($(this).val());
        });
        $('#record-ids').val(JSON.stringify(recordIds));
        $('#record-app-ids').val(JSON.stringify(recordIds));
        $('#record-ids-status').val(JSON.stringify(recordIds));
        $('#record-ids-proxy').val(JSON.stringify(recordIds));
        $('#record-ids-schedule_id').val(JSON.stringify(recordIds));

        recordIds.length > 0
            ? $('#bulk-delete').attr('disabled', false)
            : $('#bulk-delete').attr('disabled', true);

        recordIds.length > 0
            ? $('#Bulk_collection').attr('disabled', false)
            : $('#Bulk_collection').attr('disabled', true);

    }
});



