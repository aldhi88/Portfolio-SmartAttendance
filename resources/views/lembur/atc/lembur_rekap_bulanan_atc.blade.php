@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@push('push-script')
<script>
    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: 25,dom: 'lrtip',
        order: [[0, 'asc']],
        columnDefs: [
            { className: 'text-left', targets: [2] },
            // { className: 'text-center text-muted', targets: [2] },
            // { className: 'px-0', targets: [0] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: {
            url: '{{ route("lembur.rekapBulananDT") }}',
            data: function (d) {
                const params = new URLSearchParams(window.location.search);
                const getParam = (key, selector) => {
                    return params.has(key)
                        ? params.get(key)
                        : $(selector).val();
                };
                d.month = getParam('month', '[name="month"]');
                d.year  = getParam('year', '[name="year"]');

            }
        },
        columns: [
            {
                data: null, name: 'created_at', orderable: false, searchable: false,
                render: function(data, type, row) {
                    const reportPrintIds = @json($dt['report_print_id']);
                    const orgId = row.id;

                    if (!reportPrintIds.includes(orgId)) {
                        return '';
                    }

                    const params = new URLSearchParams(window.location.search);
                    const getParam = (key, selector) => {
                        return params.has(key)
                            ? params.get(key)
                            : $(selector).val();
                    };
                    let month = getParam('month', '[name="month"]');
                    let year  = getParam('year', '[name="year"]');

                    let linkPrint = '';
                    let html = '';
                    let printPdfUrl = `/lembur/rekap-bulanan/print-pdf/${row.id}/${month}/${year}`;

                    if(orgId==1){
                        linkPrint = `<a class="dropdown-item" target="_blank" href="${printPdfUrl}/pn1">Rekap Bulanan</a>`;
                        linkPrint += `<a class="dropdown-item" target="_blank" href="${printPdfUrl}/pn2">Justifikasi Kelebihan Jam</a>`;
                    }
                    if(orgId==5){
                        linkPrint = `<a class="dropdown-item" target="_blank" href="${printPdfUrl}/pl1">Rekap Form Pengajuan</a>`;
                        linkPrint += `<a class="dropdown-item" target="_blank" href="${printPdfUrl}/pl2">Rekap Timesheet Pengemudi</a>`;
                    }
                    if(orgId==9){
                        linkPrint = `<a class="dropdown-item" target="_blank" href="${printPdfUrl}/ptcs">Mapping Lembur Bulanan</a>`;
                    }

                    if(linkPrint!=''){
                        html += `
                            <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
                                <div class="btn-group" role="group">
                                    <button id="btnGroupVerticalDrop1" type="button" class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Print <i class="mdi mdi-chevron-down"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1" style="">
                                    ${linkPrint}
                                    </div>
                                </div>
                            </div>
                        `;
                    }


                    return html;
                }
            },
            {
                data: null, name: 'DT_RowIndex', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'name', name: 'id', orderable: false, searchable:true },
            { data: 'data_lembur_employee_count', name: 'data_lembur_employee_count', orderable: true, searchable:false },

        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });

    $(document).on('change', '.check-data-all', function () {
        let isChecked = $(this).is(':checked');
        table.rows({ page: 'current' }).nodes().each(function (row) {
            $(row).find('.data-check').prop('checked', isChecked);
        });
        $('#btnDeleteSelected').prop('disabled', !isChecked);
    });

    $(document).on('change', '.data-check', function () {
        let total = $('.data-check:visible').length;
        let checked = $('.data-check:visible:checked').length;
        $('.check-data-all').prop('checked', total === checked);
        $('#btnDeleteSelected').prop('disabled', checked === 0);
    });

    // 3. Reset "select all" saat pindah halaman
    dtTable.on('draw.dt', function () {
        $('.check-data-all').prop('checked', false);
    });

    $(document).on('click', '#btnDeleteSelected', function () {
        let ids = $('.data-check:checked').map(function () {
            return $(this).val();
        }).get();

        if (ids.length === 0) {
            alert('Pilih minimal 1 data untuk dihapus.');
            return;
        }

        Livewire.dispatch('setDeleteMultipleId', { ids: ids });

        $('#modalConfirmDeleteMultiple')
            .find('#submitModalConfirmDeleteMultiple')
            .attr('wire:click', 'deleteMultiple()');
        $('#modalConfirmDeleteMultiple').modal('show');

    });
</script>
@endpush


