jQuery(document).ready(function($){

    //Import data
    jQuery('#btn-import').click(function () {
        jQuery('#importFile').trigger("reset");
        jQuery('#importFile').modal('show');
    });

    jQuery('#btn-cancel-import').click(function () {
        jQuery('#importFile').modal('hide')
    });

    //Import data from upload file
    $("#btn-doimport").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var type = "POST";
        var ajaxurl = 'excel/import';
        var file_data = $('#fileupload').prop('files')[0];
        var formData = new FormData();
        formData.append('fileupload', file_data);
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function () {
                location.href = '?sort=id&direction=desc';
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    //Export data
    jQuery('#btn-export-excel').click(function () {
        jQuery('#btn-export').val("exportxls");
        jQuery('#btn-export').text("Download Excel");
        jQuery('#confirmExport').trigger("reset");
        jQuery('#confirmExport').modal('show');
    });

    jQuery('#btn-export-csv').click(function () {
        jQuery('#btn-export').val("exportcsv");
        jQuery('#btn-export').text("Download CSV");
        jQuery('#confirmExport').trigger("reset");
        jQuery('#confirmExport').modal('show');
    });

    $('body').on('click', '#btn-export', function () {
        var getexport = jQuery('#btn-export').val();
        if (getexport == 'exportxls') {
            window.location = '/excel/exportxlsx';
        } else {
            window.location = '/excel/exportcsv';
        }
        jQuery('#confirmExport').modal('hide')
    });

    jQuery('#btn-cancel-export').click(function () {
        jQuery('#confirmExport').modal('hide')
    });

    // Open a modal for new account
    jQuery('#btn-add').click(function () {
        jQuery('#btn-save').val("add");
        jQuery('#accountForm').trigger("reset");
        jQuery('#formModal').modal('show');
    });

    // Edit data
    $('body').on('click', '.btnEdit', function () {
        var account_id = $(this).attr('data-id');

        jQuery('#btn-save').val("update");
        jQuery('#btn-save').text("Update Data");
        jQuery('#formModalLabel').text("Update Account");
        jQuery('#accountForm').trigger("reset");

        $.get('accountinfo/' + account_id +'/edit', function (data) {
            jQuery('#formModal').modal('show');
            jQuery('#account_id').val(data.id);
            jQuery('#username').val(data.username);
            jQuery('#name').val(data.name);
            jQuery('#birthday').val(data.birthday);
            jQuery('#email').val(data.email);
            jQuery('#note').val(data.note);
            $('#formModal').find(':radio[name=genderRadio][value='+ data.gender +']').prop('checked', true);
        })
    });
    //Bulk delete all process
    $('body').on('click', '.btn-bulk-delete', function () {
        var post_arr = [];
        $('#account-list input[type=checkbox]').each(function() {
            if (jQuery(this).is(":checked")) {
                var id = this.id;
                var splitid = id.split('_');
                var postid = splitid[1];
                post_arr.push(postid);
            }
        });
        jQuery('.bulkmessage').text('Do you want to delete ' + post_arr.length + ' accounts?');
        jQuery('#bulkToDeleteTotal').val(post_arr.length);
        console.log(post_arr);
        console.log(jQuery('#bulkToDeleteTotal').val());

        if (post_arr.length <= 0) {
            jQuery('.bulkmessage').text('You select none to delete');
            jQuery('#btn-dobulkDelete').remove();
        }
        jQuery('#confirmBulkDelete').modal('show');
    });

    $("#btn-dobulkDelete").click(function (e) {
        var i = 0;
        var totaldelete = jQuery('#bulkToDeleteTotal').val();
        jQuery('#confirmBulkDelete').modal('hide');
        $('#account-list input[type=checkbox]').each(function() {
            if (jQuery(this).is(":checked")) {
                var id = this.id;
                var splitid = id.split('_');
                var account_id = splitid[1];
                i++;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();
                var type = "DELETE";
                var ajaxurl = 'accountinfo/' + account_id;
                var formData = {
                    account_id: account_id,
                }
                $.ajax({
                    type: type,
                    url: ajaxurl,
                    data: formData,
                    dataType: 'json',
                    success: function () {
                        jQuery('#account' + account_id).remove();
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            }
        });
    });

    $('body').on('click', '.btn-cancel-bulk-delete', function () {
        jQuery('#confirmBulkDelete').modal('hide')
    });

    // Delete data for one
    $('body').on('click', '.btnDelete', function () {
        var account_id = $(this).attr('data-id');
        jQuery('#btn-delete').val("delete");
        jQuery('#accountForm').trigger("reset");
        jQuery('#confirmDeleteModal').modal('show');
        $.get('accountinfo/' + account_id , function (data) {
            jQuery('#accountForm #account_id').val(data.id);
            jQuery('#accountForm #username').val(data.username);
            jQuery('#accountForm #name').val(data.name);
        })
    });

    //Click to delete
    $("#btn-delete").click(function (e) {
        var account_id = jQuery('#accountForm #account_id').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var type = "DELETE";
        var ajaxurl = 'accountinfo/' + account_id;
        var formData = {
            account_id: jQuery('#account_id').val(),
        }
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function () {
                jQuery('#accountForm').trigger("reset");
                jQuery('#formModal').modal('hide')
                jQuery('#account'+ account_id).remove();
                jQuery('#confirmDeleteModal').modal('hide')
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    jQuery('#btn-cancel').click(function () {
        jQuery('#confirmDeleteModal').modal('hide')
    });

    //Do edit or create operations
    jQuery.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^\w+$/i.test(value);
    }, "Letters, numbers, and underscores only please");

    $("#btn-save").click(function (e) {
        $("#accountForm").validate({
            rules: {
                username: {
                    required: true,
                    alphanumeric: true
                },
                name: "required",
                birthday: {
                    required: true,
                    dateISO: true
                },
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                username: {
                    required: "請輸入帳號",
                    alphanumeric: "帳號只有英文小寫與數字，不能包含特殊字元"
                },
                name: "請輸入姓名",
                birthday: "生日格式必需是 1999-01-01",
                email: "請輸入可用的信箱"
            }
        });

        if ($("#accountForm").valid()) {
            var state = jQuery('#btn-save').val();
            var account_id = jQuery('#account_id').val();

            if (state == 'add') {
                var type = "POST";
                var ajaxurl = 'accountinfo';
            } else {
                var type = "PUT";
                var ajaxurl = 'accountinfo/' + account_id;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            var formData = {
                account_id: jQuery('#account_id').val(),
                username: jQuery('#username').val(),
                name: jQuery('#name').val(),
                gender: $("input[name='genderRadio']:checked").val(),
                birthday: jQuery('#birthday').val(),
                email: jQuery('#email').val(),
                note: jQuery('#note').val(),
            };
            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    if (data.gender == 0) {
                        var genderCh = '女';
                    } else {
                        var genderCh = '男';
                    }
                    var dateSplit = (data.birthday).split('-');
                    var birthdayCh = dateSplit[0] + '年' + dateSplit[1] + '月' + dateSplit[2] + '日';

                    var html = '<tr id="account' + data.id + '"><td></td><td data-th="ID">' + data.id + '</td>';
                    html += '<td data-th="UserName">' + data.username + '</td><td data-th="Name">' + data.name + '</td><td data-th="Gender">' + genderCh + '</td>';
                    html += '<td data-th="Birthday">' + birthdayCh + '</td><td data-th="Email">' + data.email + '</td><td data-th="Note">' + data.note + '</td>';
                    html += '<td data-th="Action"><button data-id="' + data.id + '" class="btn btn-primary btnEdit">Edit</button>&nbsp;';
                    html += '<button data-id="' + data.id + '" class="btn btn-danger btnDelete">Delete</button></td></tr>';
                    if (state == "add") {
                        jQuery('#account-list').append(html);
                    } else {
                        jQuery("#account" + account_id).replaceWith(html);
                    }
                    jQuery('#accountForm').trigger("reset");
                    jQuery('#formModal').modal('hide')
                },
                error: function (data) {
                    console.log(data);
                }
            });
        };
    });
});
