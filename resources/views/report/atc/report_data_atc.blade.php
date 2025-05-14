@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        #myTable tbody td:nth-child(n+5):nth-child(even) {
            background-color: #ffced72b;
        }
        #myTable tbody td:nth-child(n+5):nth-child(odd) {
            background-color: #c6eee227;
        }
    </style>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/moment.js') }}"></script>
@endsection

@push('push-script')
<script>
    const tglCol = @json($dt['tglCol']);

    let pushCols = [
        { data: 'name', name: 'name', orderable: true, searchable: false,
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
        }
    ];

    tglCol.forEach(function(tgl) {
        pushCols.push(
            {
                data: null, name: 'name', orderable: false, searchable: false,
                render: function (data, type, row) {
                    return getAbsenWaktu(row, tgl.col_date, 'in');
                }
            }
        );
        pushCols.push(
            {
                data: null, name: 'name', orderable: false, searchable: false,
                render: function (data, type, row) {
                    return getAbsenWaktu(row, tgl.col_date, 'out');
                }
            }
        );
    });

    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: 25,dom: 'lrtip',
        order: [[0, 'asc']],
        columnDefs: [
            { className: 'text-left text-nowrap', targets: [1] },
            { className: 'text-center text-nowrap', targets: ['_all'] },
        ],
        ajax: {
            url: '{{ route("report.absenDT") }}',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function (d) {
                console.log($('#filter-year').val());

                d.filter_year = $('#filter-year').val();
                d.filter_month = $('#filter-month').val();
            }
        },
        columns: pushCols,
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });


</script>
@endpush
