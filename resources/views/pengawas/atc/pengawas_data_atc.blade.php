@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@push('push-script')
<script>

    var dtTableMember = $('#myTableMember').DataTable({
        processing: true,serverSide: true,pageLength: 25,dom: 'lrtip',
        order: [[1, 'asc']],
        columnDefs: [
            { className: 'text-left text-nowrap', targets: [2] },
            // { className: 'px-0 text-nowrap', targets: [1] },
            { className: 'text-center text-nowrap', targets: ['_all'] },
        ],
        ajax: '{{ route("pengawas.indexDTMember") }}?pengawas={{ $dt["pengawas_selected_id"] }}',
        columns: [
            { data: null, name: 'id', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    el = '<input class="data-check-member" type="checkbox" value="'+data.id+'">';
                    return el;
                }
            },
            { data: null, name: 'id', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'name', name: 'name', orderable: false, searchable:true },
            { data: 'master_organizations.name', name: 'master_organization_id', orderable: false, searchable:true,
                render: function (data, type, row, meta) {
                    return row.master_organizations ? row.master_organizations.name : '-';
                }
            },
            { data: 'master_positions.name', name: 'master_position_id', orderable: false, searchable:true,
                render: function (data, type, row, meta) {
                    return row.master_positions ? row.master_positions.name : '-';
                }
             },
            { data: 'master_locations.name', name: 'master_location_id', orderable: false, searchable:true,
                render: function (data, type, row, meta) {
                    return row.master_locations ? row.master_locations.name : '-';
                }
             },
            { data: 'master_functions.name', name: 'master_function_id', orderable: false, searchable:true,
                render: function (data, type, row, meta) {
                    return row.master_functions ? row.master_functions.name : '-';
                }
             },

        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });

    var dtTableEmployee = $('#myTableEmployee').DataTable({
        processing: true,serverSide: true,pageLength: 25,dom: 'lrtip',
        order: [[1, 'asc']],
        columnDefs: [
            { className: 'text-left text-nowrap', targets: [2] },
            // { className: 'px-0 text-nowrap', targets: [1] },
            { className: 'text-center text-nowrap', targets: ['_all'] },
        ],
        ajax: '{{ route("pengawas.indexDTEmployee") }}?pengawas={{ $dt["pengawas_selected_id"] }}',
        columns: [
            { data: null, name: 'id', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    el = '<input class="data-check-nonmember" type="checkbox" value="'+data.id+'">';
                    return el;
                }
            },
            { data: null, name: 'id', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'name', name: 'name', orderable: false, searchable:true },
            { data: 'master_organizations.name', name: 'master_organization_id', orderable: false, searchable:true,
                render: function (data, type, row, meta) {
                    return row.master_organizations ? row.master_organizations.name : '-';
                }
            },
            { data: 'master_positions.name', name: 'master_position_id', orderable: false, searchable:true,
                render: function (data, type, row, meta) {
                    return row.master_positions ? row.master_positions.name : '-';
                }
             },
            { data: 'master_locations.name', name: 'master_location_id', orderable: false, searchable:true,
                render: function (data, type, row, meta) {
                    return row.master_locations ? row.master_locations.name : '-';
                }
             },
            { data: 'master_functions.name', name: 'master_function_id', orderable: false, searchable:true,
                render: function (data, type, row, meta) {
                    return row.master_functions ? row.master_functions.name : '-';
                }
             },

        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter-employee','search-col-dt');
        }
    });

    $(document).on('change', '.check-data-all-nonmember', function () {
        let isChecked = $(this).is(':checked');
        table.rows({ page: 'current' }).nodes().each(function (row) {
            $(row).find('.data-check-nonmember').prop('checked', isChecked);
        });
        $('#btnAddMember').prop('disabled', !isChecked);
    });
    $(document).on('change', '.check-data-all-member', function () {
        let isChecked = $(this).is(':checked');
        table.rows({ page: 'current' }).nodes().each(function (row) {
            $(row).find('.data-check-member').prop('checked', isChecked);
        });
        $('#btnDelMember').prop('disabled', !isChecked);
    });

    $(document).on('change', '.data-check-nonmember', function () {
        let total = $('.data-check-nonmember:visible').length;
        let checked = $('.data-check-nonmember:visible:checked').length;
        $('.check-data-all-nonmember').prop('checked', total === checked);
        $('#btnAddMember').prop('disabled', checked === 0);
    });
    $(document).on('change', '.data-check-member', function () {
        let total = $('.data-check-member:visible').length;
        let checked = $('.data-check-member:visible:checked').length;
        $('.check-data-all-member').prop('checked', total === checked);
        $('#btnDelMember').prop('disabled', checked === 0);
    });

    dtTableMember.on('draw.dt', function () {
        $('.check-data-all-member').prop('checked', false);
    });
    dtTableEmployee.on('draw.dt', function () {
        $('.check-data-all-nonmember').prop('checked', false);
    });

    $(document).on('click', '.btnAddMember', function () {
        let process = $(this).data('process');
        let msg = $(this).data('msg');

        let ids = $('.data-check-nonmember:checked').map(function () {
            return $(this).val();
        }).get();

        if (ids.length === 0) {
            alert('Pilih minimal 1 data untuk dihapus.');
            return;
        }

        Livewire.dispatch('addMember', { ids: ids });
    });

    $(document).on('click', '.btnDelMember', function () {
        let process = $(this).data('process');
        let msg = $(this).data('msg');

        let ids = $('.data-check-member:checked').map(function () {
            return $(this).val();
        }).get();

        if (ids.length === 0) {
            alert('Pilih minimal 1 data untuk dihapus.');
            return;
        }

        Livewire.dispatch('delMember', { ids: ids });
    });
</script>
@endpush
