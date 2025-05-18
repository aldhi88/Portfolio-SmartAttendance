@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        #myTable tbody td:nth-child(n+5):nth-child(even) {
            background-color: #ffffff;
        }
        #myTable tbody td:nth-child(n+5):nth-child(odd) {
            background-color: #dcdde22f;
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
        { data: 'name', name: 'name', orderable: false, searchable: false,
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        { data: 'name', name: 'name', orderable: true, searchable:false },
        { data: 'master_organizations.name', name: 'master_organization_id', orderable: false, searchable:true,
            render: function (data, type, row, meta) {
                return row.master_organizations ? row.master_organizations.name : '-';
            }
        },
        { data: 'master_positions.name', name: 'master_position_id', orderable: false, searchable:false,
            render: function (data, type, row, meta) {
                return row.master_positions ? row.master_positions.name : '-';
            }
        }
    ];

    tglCol.forEach(function(item) {
        pushCols.push(
            { data: `absensi.${item.col_date}.in`, name: null, orderable: false, searchable: false,
                render: function (data, type, row) {
                    const abs = row.absensi?.[item.col_date];

                    if(abs.label_in==='(terlambat)'){
                        color = 'warning';
                        return `
                            ${abs.in}<br>
                            <strong class="text-${color}">${abs.label_in}</strong>
                        `;
                    }

                    if(abs.label_in==='alpha'){
                        color = 'danger';
                        return `
                            <strong class="text-${color}">${abs.label_in}</strong>
                        `;
                    }
                    if(abs.label_in==='off'){
                        color = 'dark';
                        return `
                            <strong class="text-${color}">${abs.label_in}</strong>
                        `;
                    }

                    if(abs.label_in==='(ontime)'){
                        color = 'success';
                        return `
                            ${abs.in}<br>
                            <strong class="text-${color}">${abs.label_in}</strong>
                        `;
                    }

                    return "";
                }
            }
        );
        pushCols.push(
            { data: `absensi.${item.col_date}.out`, name: null, orderable: false, searchable: false,
                render: function (data, type, row) {
                    const abs = row.absensi?.[item.col_date];
                    color = 'success';

                    if(abs.label_out==='(plg cepat)'){
                        color = 'warning';
                        return `
                            ${abs.out}<br>
                            <strong class="text-${color}">${abs.label_out}</strong>
                        `;
                    }

                    if(abs.label_out==='alpha'){
                        color = 'danger';
                        return `
                            <strong class="text-${color}">${abs.label_out}</strong>
                        `;
                    }
                    if(abs.label_out==='off'){
                        color = 'dark';
                        return `
                            <strong class="text-${color}">${abs.label_out}</strong>
                        `;
                    }

                    if(abs.label_out==='(ontime)'){
                        color = 'success';
                        return `
                            ${abs.out}<br>
                            <strong class="text-${color}">${abs.label_out}</strong>
                        `;
                    }

                    return "";
                }
            }
        );
    });

    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: -1,dom: 'rt',
        order: [[1, 'asc']],
        columnDefs: [
            { className: 'text-left text-nowrap', targets: [1] },
            { className: 'text-center text-nowrap', targets: ['_all'] },
        ],
        ajax: {
            url: '{{ route("report.absenDT") }}',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function (d) {
                d.filter_year = "{{ $filter['thisYear'] }}";
                d.filter_month = "{{ $filter['thisMonth'] }}";
                d.filter_master_organization_id = "{{ $filter['master_organization_id'] }}";
                d.filter_master_position_id = "{{ $filter['master_position_id'] }}";
                d.filter_name = "{{ $filter['name'] }}";
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
