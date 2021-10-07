jQuery(document).ready(function() {
    $.each(window._tables, function (id, table) {
        let url = table.url;
        let searchForm = this.search_from;
        let searchKeys = this.search_keys;
        let options = {
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        method: 'GET',
                        url: url,
                        beforeSend: function(jqXHR, settings){
                            if(searchForm) {
                                settings.url += "&"+$(searchForm).serialize();
                            }
                        },
                        map: function (raw) {
                            let dataRaw = raw.data;
                            let data = [];
                            $.each(dataRaw, function (index, columns) {
                                let formattedData = {};
                                $.each(columns, function (name, value) {
                                    formattedData[name] = window._htmlEscapeEntities(value);
                                });
                                data[index] = formattedData;
                            });
                            return data;
                        }
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
            },

            // layout definition
            layout: {
                scroll: false, // enable/disable datatable scroll both horizontal and
                footer: false, // display/hide footer
                customScrollbar: false,
                spinner: {
                    message: __('Đang tải dữ liệu')
                }
            },

            translate: {
                records: {
                    noRecords: __('Không có dữ liệu')
                }
            },

            // column sorting
            sortable: true,

            pagination: true,

            // columns definition
            columns: table.columns,
            search: {
                input: searchKeys,
            },
            rows: {
                autoHide: true
            }
        };
        options.extensions = {
            // boolean or object (extension options)
            checkbox: true,
        };
        let datatable = $('#'+id).KTDatatable(options);
        window._tables[id].datatable = datatable;
        if (searchForm) {
            $(this.search_from).submit(function (e) {
                datatable.reload();
                e.preventDefault();
                return false;
            });
        }
    });

    $('[data-plugin-daterangepicker]').daterangepicker({
        buttonClasses: ' btn',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary',
    }, function(start, end) {
        $('[data-plugin-daterangepicker] .form-control').val( start.format('YYYY-MM-DD') + ' / ' + end.format('YYYY-MM-DD'));
        $('[data-plugin-daterangepicker] #start_date').val(start.format('YYYY-MM-DD'));
        $('[data-plugin-daterangepicker] #end_date').val(end.format('YYYY-MM-DD'));
    });
});
