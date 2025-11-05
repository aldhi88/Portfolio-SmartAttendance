@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css" />
    <style>
        #myTable tbody td:nth-child(n+3):nth-child(odd) {
            background-color: #ffffff;
        }
        #myTable tbody td:nth-child(n+3):nth-child(even) {
            background-color: #dcdde22f;
        }
        .text-rapat {
            line-height: 16px;
        }

        .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 4px; /* atau 8px kalau mau lebih longgar */
            margin-bottom: 0;
            font-size: 0.875rem;
        }

        .dataTables_length select {
            margin: 0 4px;
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
    const tglCol = @json($dt['tglCol']);

    let pushCols = [
        { data: 'name', name: 'name', orderable: true, searchable: false,
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        { data: null, name: 'name', orderable: false, searchable:true,
            render: function(data, type, row, meta){
                let html = `<h6 class="mb-2">${row.name}</h6>`;
                html += `<div class="text-muted text-rapat">`;
                html += `${row.master_organizations.name}</div>`;
                html += `<div class="text-muted text-rapat">${row.master_positions.name}</div>`;
                return html;
            }
        },
        { data: null, name: 'id', orderable: false, searchable:true,
            render: function(data, type, row, meta){
                return formatAngka(row.akumulasi.time_detail.total_dtg_cpt_read)+'<br><small>jam</small>';
            }
        },
        { data: null, name: 'id', orderable: false, searchable:true,
            render: function(data, type, row, meta){
                return formatAngka(row.akumulasi.time_detail.total_dtg_lama_read)+'<br><small>jam</small>';
            }
        },
        { data: null, name: 'id', orderable: false, searchable:true,
            render: function(data, type, row, meta){
                return formatAngka(row.akumulasi.time_detail.total_plg_cpt_read)+'<br><small>jam</small>';
            }
        },
        { data: null, name: 'id', orderable: false, searchable:true,
            render: function(data, type, row, meta){
                return formatAngka(row.akumulasi.time_detail.total_plg_lama_read)+'<br><small>jam</small>';
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
                        'outdate': 'primary',
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
                    if(abs.type==='Rotasi' || abs.type==='Hybrid'){
                        type = '<br><small>'+abs.shift+'</small>';
                    }

                    return `
                        ${abs.time_in}<br>
                        <div class="text-rapat">
                            <strong class="text-${color}">${abs.label_in}</strong>
                            ${type}
                        </div>
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
                        'outdate': 'primary',
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

                    if(abs.type==='Rotasi' || abs.type==='Hybrid'){
                        type = '<br><small>'+abs.shift+'</small>';
                    }
                    return `
                        ${abs.time_out}<br>
                        <div class="text-rapat">
                            <strong class="text-${color}">${abs.label_out}</strong>
                            ${type}
                        </div>
                    `;
                }
            }
        );
    });

    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,dom: 'rtip', pageLength: -1,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        order: [[0, 'asc']],
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
                d.filter_start = "{{ $filter['start_value'] }}";
                d.filter_end = "{{ $filter['end_value'] }}";
                d.filter_master_organization_id = "{{ $filter['master_organization_id'] }}";
                d.filter_master_position_id = "{{ $filter['master_position_id'] }}";
                d.filter_name = "{{ $filter['name'] }}";
            },
            dataSrc: function(json) {
                let order = "{{ $filter['order'] }}";
                json.data.sort((a, b) => {
                    if(order==1){
                        return b.akumulasi.time_detail.total_dtg_cpt - a.akumulasi.time_detail.total_dtg_cpt;
                    }
                    if(order==2){
                        return b.akumulasi.time_detail.total_dtg_lama - a.akumulasi.time_detail.total_dtg_lama;
                    }
                    if(order==3){
                        return b.akumulasi.time_detail.total_plg_cpt - a.akumulasi.time_detail.total_plg_cpt;
                    }
                    if(order==4){
                        return b.akumulasi.time_detail.total_plg_lama - a.akumulasi.time_detail.total_plg_lama;
                    }
                });
                return json.data;
            }
        },
        columns: pushCols,
        initComplete: function(settings){
            table = settings.oInstance.api();
            $('#lengthContainer').append($('#myTable_length'));
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });

    $('#export-excel').on('click', function () {
        $('.loading').fadeIn(200);
        const data = {
            filter_year: "{{ $filter['thisYear'] }}",
            filter_month: "{{ $filter['thisMonth'] }}",
            filter_start : "{{ $filter['start_value'] }}",
            filter_end : "{{ $filter['end_value'] }}",
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
            $('.loading').fadeOut(500);
        })
        .catch(error => {
            $('.loading').fadeOut(500);
            alert(error.message);
        });
    });

    $('#export-pdf').on('click', function () {
        $('.loading').fadeIn(200);
        const data = {
            filter_year: "{{ $filter['thisYear'] }}",
            filter_month: "{{ $filter['thisMonth'] }}",
            filter_start : "{{ $filter['start_value'] }}",
            filter_end : "{{ $filter['end_value'] }}",
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
            const fileName = `report-absen-${now.toLocaleString('id-ID', { month: 'long' }).toLowerCase()}-${now.getFullYear()}-${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}${now.getMilliseconds()}.pdf`;

            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            a.remove();
            $('.loading').fadeOut(500);
        })
        .catch(error => {
            $('.loading').fadeOut(500);
            alert(error.message);
        });
    });




</script>
@endpush
