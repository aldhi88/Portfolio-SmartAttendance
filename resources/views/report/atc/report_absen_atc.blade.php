@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css" />
    <style>
        #myTable tbody td:nth-child(n+4):nth-child(odd) {
            background-color: #ffffff;
            line-height: 1;
        }
        #myTable tbody td:nth-child(n+4):nth-child(even) {
            background-color: #dcdde22f;
            line-height: 1;
        }
        #myTable tbody td:nth-child(n+3) {
            line-height: 1;
        }
    </style>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/moment.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
    <!-- Buttons -->
    {{-- <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script> --}}
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
                return row.master_organizations.name+'<br>('+row.master_positions.name+')';
            }
        }

    ];

    tglCol.forEach(function(item) {
        pushCols.push(
            { data: `absensi.${item.col_date}.time_in`, name: null, orderable: false, searchable: false,
                render: function (data, type, row) {
                    const abs = row.absensi?.[item.col_date];
                    const colorMap = {
                        'izin': 'warning',
                        'hadir': 'success',
                        'alpha': 'danger',
                        'tgl merah': 'danger',
                        'off': 'secondary',
                    };

                    let color = colorMap[abs.status] ?? 'info';
                    if(
                        abs.label_in=='tdk absen' ||
                        abs.label_in=='terlambat'
                    ){
                        color = 'dark';
                    }

                    if(
                        abs.label_in=='Sakit' ||
                        abs.label_in=='Keluar' ||
                        abs.label_in=='Pulang' ||
                        abs.label_in=='Dinas'
                    ){
                        color = 'warning';
                    }

                    var type = '';
                    if(abs.type==='Rotasi'){
                        type = '<br><small>'+abs.shift+'</small>';
                    }

                    return `
                        ${abs.time_in}<br>
                        <strong class="text-${color}">${abs.label_in}</strong>
                        ${type}
                    `;
                }
            }
        );
        pushCols.push(
            { data: `absensi.${item.col_date}.time_out`, name: null, orderable: false, searchable: false,
                render: function (data, type, row) {
                    const abs = row.absensi?.[item.col_date];
                    const colorMap = {
                        'izin': 'warning',
                        'hadir': 'success',
                        'alpha': 'danger',
                        'tgl merah': 'danger',
                        'off': 'secondary',
                    };
                    let color = colorMap[abs.status] ?? 'info';
                    if(
                        abs.label_out=='tdk absen' ||
                        abs.label_out=='plg cepat'
                    ){
                        color = 'dark';
                    }
                    if(
                        abs.label_out=='Sakit' ||
                        abs.label_out=='Keluar' ||
                        abs.label_out=='Pulang' ||
                        abs.label_out=='Dinas'
                    ){
                        color = 'warning';
                    }
                    var type = '';

                    if(abs.type==='Rotasi'){
                        type = '<br><small>'+abs.shift+'</small>';
                    }
                    return `
                        ${abs.time_out}<br>
                        <strong class="text-${color}">${abs.label_out}</strong>
                        ${type}
                    `;
                }
            }
        );
    });

    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: -1,dom: 'rtp',
        order: [[1, 'asc']],
        fixedColumns: {
            leftColumns: 2 // <- jumlah kolom dari kiri yang ingin fix
        },
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

    $('#export-excel').on('click', function () {
        const data = {
            filter_year: "{{ $filter['thisYear'] }}",
            filter_month: "{{ $filter['thisMonth'] }}",
            filter_master_organization_id: "{{ $filter['master_organization_id'] }}",
            filter_master_position_id: "{{ $filter['master_position_id'] }}",
            filter_name: "{{ $filter['name'] }}"
        };
        fetch("{{ route('report.exportExcel') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) throw new Error("Gagal export file.");
            return response.blob();
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const now = new Date();
            const pad = (n) => n.toString().padStart(2, '0');
            const fileName = `report-absen-${now.toLocaleString('id-ID', { month: 'long' }).toLowerCase()}-${now.getFullYear()}-${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}${now.getMilliseconds()}.xlsx`;

            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            a.remove();
        })
        .catch(error => {
            alert(error.message);
        });
    });

    $('#export-pdf').on('click', function () {
        const data = {
            filter_year: "{{ $filter['thisYear'] }}",
            filter_month: "{{ $filter['thisMonth'] }}",
            filter_master_organization_id: "{{ $filter['master_organization_id'] }}",
            filter_master_position_id: "{{ $filter['master_position_id'] }}",
            filter_name: "{{ $filter['name'] }}"
        };

        fetch("{{ route('report.exportPdf') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) throw new Error("Gagal export file.");
            return response.blob();
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const now = new Date();
            const pad = (n) => n.toString().padStart(2, '0');
            const fileName = `report-absen-${now.toLocaleString('id-ID', { month: 'long' }).toLowerCase()}-${now.getFullYear()}-${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}${now.getMilliseconds()}.xlsx`;

            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            a.remove();
        })
        .catch(error => {
            alert(error.message);
        });
    });




</script>
@endpush
