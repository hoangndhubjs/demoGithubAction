;+(function () {
    $(".card-file").mouseover(function(e) { $(e.currentTarget).addClass('shadow')}).mouseleave(function (e) { $(e.currentTarget).removeClass('shadow')});
    $('.bootstrap-selector').selectpicker();
    let currentFolderEl = $('.folders').find('.folder-item.active');
    let currentFolder = currentFolderEl.data('folder');
    let fileAreaEl = $('.files-area');
    let search = $('#file_search').val();
    $('.folder-item').click(function (e) {
        let currentItem = $(e.currentTarget);
        let selectedFolder = currentItem.data('folder');
        if (currentFolder !== selectedFolder) {
            currentFolder = selectedFolder;
            $('#working_dir').val('/'+selectedFolder);
            loadFiles(selectedFolder);
        }
    });
    fileAreaEl.on('click', '.navi-item .file-remove', function (e) {
        let button = $(e.currentTarget);
        let cardFile = button.closest('.card-file');
        let folder = button.data('folder');
        let file = button.data('name');
        let del_url = button.data('url');
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
                KTApp.block(cardFile);
                $.ajax({
                    method: 'DELETE',
                    url: del_url,
                    data: {
                        folder: folder,
                        file: file
                    }
                }).done(function(response) {
                    KTApp.unblock(cardFile);
                    if (response.success) {
                        Swal.fire(
                            __("deleted_title"),
                            __("record_deleted"),
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
                    loadFiles(folder);
                });
                return false;
            },
            allowOutsideClick: function () { return !Swal.isLoading() }
        });
        e.preventDefault();
    });
    if (currentFolderEl.length) {
        let currentFolder = currentFolderEl.data('folder');
        loadFiles(currentFolder);
    }
    function loadFiles(folder) {
        fileAreaEl.find('.card-body').html("");
        KTApp.block('.files-area');
        $.ajax({
            method: 'GET',
            url: window.list_file_url,
            data: {
                folder: folder,
                search: search
            }
        }).done(function (response, status, jqXHR) {
            KTApp.unblock('.files-area');
            if (jqXHR.status === 200) {
                fileAreaEl.find('.card-body').html(response);
            } else {
                toastr.error("cannot_load_files");
            }
        });
    }
    let dropzone = null;
    $('#upload-dropzone').dropzone({
        url: window.upload_file_url, // Set the url for your upload script location
        paramName: "upload[]", // The name that will be used to transfer the file
        parallelUploads: 5,
        timeout: 0,
        params: function () {
            let working_dir = $('#working_dir').val();
            let csrf = $('meta[name="csrf-token"]').attr('content');
            return { working_dir, _token: csrf };
        },
        acceptedFiles: window._valid_mime.join(','),
        init: function () {
            dropzone = this;
            this.on('success', function(file, response) {
                if (!(response == 'OK')) {
                    this.defaultOptions.error(file, response.join('\n'));
                }
            });
        }
    });
    $('#uploadForm').on('hidden.bs.modal', function (e) {
        if (dropzone) {
            if (dropzone.files.length > 0) {
                dropzone.removeAllFiles();
                loadFiles(currentFolder);
            }
        }
    });
    function typingTimeout() {
        var typingTimer;                //timer identifier
        var doneTypingInterval = 2000;  //time in ms, 5 second for example
        var $input = $('#file_search');

        $input.on('keyup', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });

        $input.on('keydown', function () {
            clearTimeout(typingTimer);
        });
    }
    function doneTyping() {
        search = $('#file_search').val();
        loadFiles(currentFolder);
    }
    typingTimeout();
})(jQuery);
