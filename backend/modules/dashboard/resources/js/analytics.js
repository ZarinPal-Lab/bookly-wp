jQuery(function($) {

    let
        $table          = $('#bookly-analytics-table'),
        $dateFilter     = $('#bookly-filter-date'),
        $staffFilter    = $('#bookly-js-filter-staff'),
        $servicesFilter = $('#bookly-js-filter-services'),
        $printDialog    = $('#bookly-print-dialog'),
        $printButton    = $(':submit', $printDialog),
        $exportDialog   = $('#bookly-export-dialog'),
        $exportButton   = $(':submit', $exportDialog)
    ;

    /**
     * Staff drop-down.
     */
    $staffFilter.booklyDropdown({
        onChange: function (values, selected, all) {
            setTimeout(function () {
                dt.ajax.reload();
            }, 0);
        }
    });

    /**
     * Services drop-down.
     */
    $servicesFilter.booklyDropdown({
        onChange: function (values, selected, all) {
            dt.ajax.reload();
        }
    });

    /**
     * Init DataTables.
     */
    let dt = $table.DataTable({
        order:      [[ 0, 'desc' ]],
        info:       false,
        paging:     false,
        searching:  false,
        lengthChange: false,
        processing: true,
        responsive: true,
        ajax: {
            url : ajaxurl,
            type: 'POST',
            data: function () {
                let service_ids = $servicesFilter.booklyDropdown('getSelected'),
                    staff_ids   = $staffFilter.booklyDropdown('getSelectedAllState')
                        ? 'all_with_archived'
                        : $staffFilter.booklyDropdown('getSelected');
                return {
                    action      : 'bookly_pro_get_analytics',
                    csrf_token  : BooklyL10n.csrfToken,
                    date        : $dateFilter.data('date'),
                    staff_ids   : staff_ids,
                    service_ids : service_ids
                };
            },
            dataSrc: function (json) {
                let $ths = $table.find('tfoot th');
                $ths.eq(1).html(json.total.appointments.total);
                $ths.eq(2).html(json.total.appointments.approved);
                $ths.eq(3).html(json.total.appointments.pending);
                $ths.eq(4).html(json.total.appointments.rejected);
                $ths.eq(5).html(json.total.appointments.cancelled);
                $ths.eq(6).html(json.total.customers.total);
                $ths.eq(7).html(json.total.customers.new);
                $ths.eq(8).html(json.total.revenue.total_formatted);

                return json.data;
            }
        },
        columns: [
            { data: 'staff', render: $.fn.dataTable.render.text() },
            { data: 'service', render: $.fn.dataTable.render.text() },
            { data: 'appointments.total' },
            { data: 'appointments.approved' },
            { data: 'appointments.pending' },
            { data: 'appointments.rejected' },
            { data: 'appointments.cancelled' },
            { data: 'customers.total' },
            { data: 'customers.new' },
            { data: 'revenue.total_formatted' }
        ],
        dom: "<'row'<'col-sm-12'tr>><'row float-left mt-3'<'col-sm-12'p>>",
        language: {
            zeroRecords: BooklyAnalyticsL10n.zeroRecords,
            processing:  BooklyAnalyticsL10n.processing
        }
    });

    $dateFilter.on('apply.daterangepicker', function () { dt.ajax.reload(); });

    /**
     * Export.
     */
    $exportButton.on('click', function() {
        let columns = [];
        $('input:checked', $exportDialog).each(function () {
            columns.push(this.value);
        });
        let config = {
            autoPrint: false,
            fieldSeparator: $('#bookly-csv-delimiter', $exportDialog).val(),
            exportOptions: {
                columns: columns
            },
            filename: 'Analytics'
        };
        $.fn.dataTable.ext.buttons.csvHtml5.action.call({processing: function() {}}, null, dt, null, $.extend({}, $.fn.dataTable.ext.buttons.csvHtml5, config));
    });

    /**
     * Print.
     */
    $printButton.on('click', function () {
        let columns = [];
        $('input:checked', $printDialog).each(function () {
            columns.push(this.value);
        });
        let config = {
            title: '&nbsp;',
            exportOptions: {
                columns: columns
            },
            customize: function (win) {
                win.document.firstChild.style.backgroundColor = '#fff';
                win.document.body.id = 'bookly-tbs';
                $(win.document.body).find('table').removeClass('collapsed');
            }
        };
        $.fn.dataTable.ext.buttons.print.action(null, dt, null, $.extend({}, $.fn.dataTable.ext.buttons.print, config));
    });
});