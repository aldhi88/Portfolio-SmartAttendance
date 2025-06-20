@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css" />
    <style>
        .text-rapat {
            line-height: 16px;
        }
    </style>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/moment.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
@endsection

@push('push-script')
<script>

    let pushCols = [
        { data: null, name: 'id', orderable: false, searchable: false,
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        { data: null, name: 'name', orderable: false, searchable:true,
            render: function(data, type, row, meta){
                let html = `<h6 class="">${row.name}</h6>`;
                html += `<div class="text-muted text-rapat">`;
                html += `${row.master_organizations.name}</div>`;
                html += `<div class="text-muted text-rapat">${row.master_positions.name}</div>`;

                return html;
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.hari_kerja+' hari';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.tdk_absen+' kali';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.loyal_time_read+' jam';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.hadir+' hari';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.izin+' hari';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.alpa+' hari';
            }
        },
        { name: 'id', orderable: true, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.total_poin;
            }
        },
    ];

    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: -1,dom: 'rtip',
        order: [[8, 'desc']],
        columnDefs: [
            { className: 'text-left text-nowrap', targets: [1] },
            { className: 'text-center text-nowrap', targets: ['_all'] },
        ],
        ajax: {
            url: '{{ route("report.rankDT") }}',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function (d) {
                d.filter_year = "{{ $filter['thisYear'] }}";
                d.filter_month = "{{ $filter['thisMonth'] }}";
                d.filter_master_organization_id = "{{ $filter['master_organization_id'] }}";
                d.filter_master_position_id = "{{ $filter['master_position_id'] }}";
                d.filter_name = "{{ $filter['name'] }}";
            },
            dataSrc: function(json) {
                json.data.sort((a, b) => {
                    if (b.akumulasi.total_poin !== a.akumulasi.total_poin) {
                        return b.akumulasi.total_poin - a.akumulasi.total_poin;
                    }
                    return b.akumulasi.loyal_time - a.akumulasi.loyal_time;
                });

                const rank1 = json.data[0];
                $('#rank1-name').html(rank1.name);
                $('#rank1-point').html(rank1.akumulasi.total_poin);
                $('#rank1-org').html(rank1.master_organizations.name);
                $('#rank1-as').html(rank1.master_positions.name);
                $('#rank1-day-work').html(rank1.akumulasi.hari_kerja);
                $('#rank1-hadir').html(rank1.akumulasi.hadir);
                $('#rank1-noabsen').html(rank1.akumulasi.tdk_absen);
                $('#rank1-loyal').html(rank1.akumulasi.loyal_time_read);

                // launchConfetti();
                return json.data;
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
