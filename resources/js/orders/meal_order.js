$('#create_meal_order').on('show.bs.modal', function (e) {
    window._loading("#create_meal_order .modal-body");
    let button = $(e.relatedTarget);
    let id = button.data('id');
    let url = $('#create_meal_order').data('form-url');
    $.ajax({
        url: url,
        data: {
            id: id
        }
    })
        .done(function (response) {
            window._loading("#create_meal_order .modal-body", false);
            $("#create_meal_order .modal-body").html(response);
        })
        .fail(function (jqXHR, status){
            window._loading("#create_meal_order .modal-body", false);
            toastr.error(__("order_form_fetch_error"));
            window._display_alert("#create_meal_order .modal-body", __("order_form_fetch_error"));
        });
});
$('#create_meal_order').on('hidden.bs.modal', function (e) {
    $("#create_meal_order .modal-body").html("");
});
var deleteOrder = function (id) {
    Swal.fire({
        title: __("are_you_sure_delete_it"),
        text: __("you_wont_able_revert_it"),
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: __("yes"),
        cancelButtonText: __("no"),
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: function (data) {
            Swal.showLoading();
            $.ajax({
                method: 'DELETE',
                url: window.meal_order_remove_url,
                data: { id: id }
            }).done(function(response) {
                if (response.success) {
                    Swal.fire(
                        __("deleted_title"),
                        response.data ?? __("record_deleted"),
                        "success",
                        false,
                        false
                    );
                } else {
                    Swal.fire(
                        __("error_title"),
                        response.error ?? __("error"),
                        "error",
                        false,
                        false
                    );
                }
                setTimeout(function () {window.location.reload()}, 400);
            });
            return false;
        },
        allowOutsideClick: function () { return !Swal.isLoading() }
    });
}

$('#add_new_dishes').on('show.bs.modal', function (e) {
    window._loading("#add_new_dishes .modal-body");
    let button = $(e.relatedTarget);
    // let id = button.data('id');
    let url = $('#add_new_dishes').data('form-url');
    $.ajax({
        url: url,
        // data: {
        //     id: id
        // }
    })
        .done(function (response) {
            window._loading("#add_new_dishes .modal-body", false);
            $("#add_new_dishes .modal-body").html(response);
        })
        .fail(function (jqXHR, status){
            window._loading("#add_new_dishes .modal-body", false);
            toastr.error(__("order_form_fetch_error"));
            window._display_alert("#add_new_dishes .modal-body", __("order_form_fetch_error"));
        });
});
$('#add_new_dishes').on('hidden.bs.modal', function (e) {
    $("#add_new_dishes .modal-body").html("");
});

var deleteMenu = function (id) {
    Swal.fire({
        title: __("are_you_sure_delete_it"),
        text: __("you_wont_able_revert_it"),
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: __("yes"),
        cancelButtonText: __("no"),
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: function (data) {
            Swal.showLoading();
            $.ajax({
                method: 'DELETE',
                url: window.menu_dishes_remove_url,
                data: { id: id }
            }).done(function(response) {
                if (response.success) {
                    Swal.fire(
                        __("deleted_title"),
                        response.data ?? __("record_deleted"),
                        "success",
                        false,
                        false
                    );
                } else {
                    Swal.fire(
                        __("error_title"),
                        response.error ?? __("error"),
                        "error",
                        false,
                        false
                    );
                }
                setTimeout(function () {window._tables.menu_dishis.datatable.reload()}, 400);
                setTimeout(function () {Swal.close()}, 1500);
            });
            return false;
        },
        allowOutsideClick: function () { return !Swal.isLoading() }
    });
}

var confirmExportExcel = function () {
    Swal.fire({
        title: __("Xác nhận chốt đơn"),
        text: __("Bạn chắc chắn muốn chốt đơn đặt cơm?"),
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: __("yes"),
        cancelButtonText: __("no"),
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: function (data) {
            Swal.showLoading();
            Swal.fire(
                __(""),
                "Xác nhận thành công",
                "success",
                false,
                false
            );
            setTimeout(function () {
                window.open(window.export_order_url)
            }, 1000);
            setTimeout(function () {
                window.location.reload()
            }, 1500);
            return false;
        },
        allowOutsideClick: function () { return !Swal.isLoading() }
    });
}