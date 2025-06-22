@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css" />
    <style>
        .text-rapat {
            line-height: 16px;
        }
        /* .fit-content {
            width: 1%;
        } */
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
        { data: null, name: 'id', orderable: true, searchable: false,
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
                return formatAngka(row.akumulasi.hari_kerja)+' hari';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return formatAngka(row.akumulasi.alpa)+' hari';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                if(row.akumulasi.time_detail.loyal_time_read >= 0){
                    return '+'+formatAngka(row.akumulasi.time_detail.loyal_time_read)+' jam';
                }else{
                    return formatAngka(row.akumulasi.time_detail.loyal_time_read)+' jam';
                }
                //
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return formatAngka(row.akumulasi.rank.total_poin) + ' poin';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.terlambat+' hari';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.plg_cepat+' hari';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.terlambat_plgcepat+' hari';
            }
        },
        // { name: 'id', orderable: false, searchable:false,
        //     render: function(data, type, row, meta){
        //         return row.akumulasi.tdk_absen+' hari';
        //     }
        // },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return formatAngka(row.akumulasi.rank.keterlambatan) + ' poin';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.izin.izin_sakit+' hari';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.izin.izin_pulang+' hari';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return formatAngka(row.akumulasi.rank.izin) + ' poin';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.izin.izin_keluar_pribadi+' hari';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return formatAngka(row.akumulasi.rank.keluar) + ' poin';
            }
        },
    ];

    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: -1,dom: 'rtip',
        order: [[0, 'desc']],
        columnDefs: [
            { className: 'text-left text-nowrap', targets: [1] },
            { className: 'text-center text-nowrap fit-content', targets: ['_all'] },
        ],
        ajax: {
            url: '{{ route("report.rankDT") }}',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function (d) {
                d.filter_year = "{{ $filter['thisYear'] }}";
                d.filter_month = "{{ $filter['thisMonth'] }}";
                d.filter_start = "{{ $filter['start_value'] }}";
                d.filter_end = "{{ $filter['end_value'] }}";
                d.filter_master_organization_id = "{{ $filter['master_organization_id'] }}";
                d.filter_master_position_id = "{{ $filter['master_position_id'] }}";
                d.filter_name = "{{ $filter['name'] }}";
            },
            dataSrc: function(json) {
                json.data.sort((a, b) => {
                    if (b.akumulasi.rank.total_poin !== a.akumulasi.rank.total_poin) {
                        return b.akumulasi.rank.total_poin - a.akumulasi.rank.total_poin;
                    }
                    return b.akumulasi.time_detail.loyal_time - a.akumulasi.time_detail.loyal_time;
                });

                const rank1 = json.data[0];
                $('#rank1-name').html(rank1.name);
                $('#rank1-point').html(formatAngka(rank1.akumulasi.rank.total_poin));
                $('#rank1-org').html(rank1.master_organizations.name);
                $('#rank1-as').html(rank1.master_positions.name);
                $('#rank1-day-work').html(rank1.akumulasi.hari_kerja);
                $('#rank1-hadir').html(rank1.akumulasi.hadir);
                $('#rank1-noabsen').html(rank1.akumulasi.tdk_absen);
                if(rank1.akumulasi.time_detail.loyal_time_read >= 0){
                    $('#rank1-loyal').html('+'+formatAngka(rank1.akumulasi.time_detail.loyal_time_read));
                }else{
                    $('#rank1-loyal').html(formatAngka(rank1.akumulasi.time_detail.loyal_time_read));
                }


                launchConfetti();
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
