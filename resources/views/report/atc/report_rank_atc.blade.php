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
        { data: null, name: 'id', orderable: false, searchable:false,
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
                return row.akumulasi.tdk_absen+' kali';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.loyal_time_read;
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.hari_kerja;
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.hadir;
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.alpa;
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.izin;
            }
        },
        { name: 'id', orderable: true, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.total_poin;
            }
        },
    ];

    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: 100,dom: 'rtp',
        order: [[6, 'desc']],
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
                    // Urutkan berdasarkan total_poin (desc)
                    if (b.akumulasi.total_poin !== a.akumulasi.total_poin) {
                        return b.akumulasi.total_poin - a.akumulasi.total_poin;
                    }

                    // Jika total_poin sama, urutkan berdasarkan tdk_absen (asc)
                    if (a.akumulasi.tdk_absen !== b.akumulasi.tdk_absen) {
                        return a.akumulasi.tdk_absen - b.akumulasi.tdk_absen;
                    }

                    // Jika masih sama, urutkan berdasarkan loyal_time (desc)
                    return b.akumulasi.loyal_time - a.akumulasi.loyal_time;
                });

                return json.data;
            }
        },

        columns: pushCols,
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });

    // $('#export-excel').on('click', function () {
    //     const data = {
    //         filter_year: "{{ $filter['thisYear'] }}",
    //         filter_month: "{{ $filter['thisMonth'] }}",
    //         filter_master_organization_id: "{{ $filter['master_organization_id'] }}",
    //         filter_master_position_id: "{{ $filter['master_position_id'] }}",
    //         filter_name: "{{ $filter['name'] }}"
    //     };
    //     fetch("{{ route('report.exportExcel') }}", {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //         },
    //         body: JSON.stringify(data)
    //     })
    //     .then(response => {
    //         if (!response.ok) throw new Error("Gagal export file.");
    //         return response.blob();
    //     })
    //     .then(blob => {
    //         const url = window.URL.createObjectURL(blob);
    //         const now = new Date();
    //         const pad = (n) => n.toString().padStart(2, '0');
    //         const fileName = `report-absen-${now.toLocaleString('id-ID', { month: 'long' }).toLowerCase()}-${now.getFullYear()}-${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}${now.getMilliseconds()}.xlsx`;

    //         const a = document.createElement('a');
    //         a.href = url;
    //         a.download = fileName;
    //         document.body.appendChild(a);
    //         a.click();
    //         a.remove();
    //     })
    //     .catch(error => {
    //         alert(error.message);
    //     });
    // });

    // $('#export-pdf').on('click', function () {
    //     const data = {
    //         filter_year: "{{ $filter['thisYear'] }}",
    //         filter_month: "{{ $filter['thisMonth'] }}",
    //         filter_master_organization_id: "{{ $filter['master_organization_id'] }}",
    //         filter_master_position_id: "{{ $filter['master_position_id'] }}",
    //         filter_name: "{{ $filter['name'] }}"
    //     };

    //     fetch("{{ route('report.exportPdf') }}", {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //         },
    //         body: JSON.stringify(data)
    //     })
    //     .then(response => {
    //         if (!response.ok) throw new Error("Gagal export file.");
    //         return response.blob();
    //     })
    //     .then(blob => {
    //         const url = window.URL.createObjectURL(blob);
    //         const now = new Date();
    //         const pad = (n) => n.toString().padStart(2, '0');
    //         const fileName = `report-absen-${now.toLocaleString('id-ID', { month: 'long' }).toLowerCase()}-${now.getFullYear()}-${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}${now.getMilliseconds()}.xlsx`;

    //         const a = document.createElement('a');
    //         a.href = url;
    //         a.download = fileName;
    //         document.body.appendChild(a);
    //         a.click();
    //         a.remove();
    //     })
    //     .catch(error => {
    //         alert(error.message);
    //     });
    // });




</script>
@endpush
