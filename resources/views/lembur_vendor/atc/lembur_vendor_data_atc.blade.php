@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/locale/id.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
@endsection

@push('push-script')
<script>
    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: 25,dom: 'lrtip',
        order: [[0, 'asc']],
        columnDefs: [
            // { className: 'text-left', targets: [3] },
            { className: 'px-0', targets: [1] },
            { className: 'text-left text-nowrap', targets: [2,3,6] },
            { className: 'text-center text-nowrap', targets: ['_all'] },
        ],
        ajax: {
            url: '{{ route("lembur-vendor.indexLemburDT") }}',
            data: function (d) {

                const params = new URLSearchParams(window.location.search);

                // helper: ambil dari URL, kalau tidak ada ambil dari select
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
            { data: null, name: 'created_at', orderable: false, searchable: false,
                render: function(data, type, row) {
                    let editUrl = "edit/"+row.id;
                    let html = `
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu">
                    `;

                    if(
                        // row.laporan_lembur_checkin != '-' &&
                        // row.laporan_lembur_checkout != '-'
                        row.status === 'Disetujui'
                    ){
                        let printPdfUrl = "print-pdf/"+row.id;
                        html += `
                                <a class="dropdown-item" href="${printPdfUrl}" target="_blank">
                                    <i class="fas fa-print fa-fw"></i> Print Surat Lembur
                                </a>
                        `;
                    }


                    html += `</div></div>`;
                    return html;
                }
            },
            { data: null, name: 'DT_RowIndex', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'data_employees.name', name: 'data_employees.name', orderable: false, searchable:false },
            { data: 'data_employees.master_organizations.name', name: 'data_employees.master_organizations.name', orderable: false, searchable:false },
            { data: 'tanggal', name: 'tanggal', orderable: true, searchable:false,
                render: function (data, type, row, meta) {
                    return moment(row.tanggal).locale('id').format('DD MMMM YYYY');
                }
            },
            { data: 'status', name: 'status', orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    let color;
                    let status = row.status;
                    switch (status) {
                        case "Proses":
                            color = "secondary";
                            break;
                        case "Disetujui":
                            color = "success";
                            break;
                        default:
                            color = "danger";
                        }
                    return `<span class="badge badge-${color} w-100" style="font-size:13px">${status}</span>`;
                }
            },
            { data: 'pengawas', name: null, orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    const items = [
                        { name: row.pengawas1?.name },
                        { name: row.pengawas2?.name },
                        { name: row.security?.name },
                        { name: row.korlap },
                    ];

                    const html = items
                        .filter(item => item.name)
                        .map(item => `
                            <div class="small">
                                <i class="fas fa-user fa-fw me-1"></i>
                                ${item.name}
                            </div>
                        `)
                        .join('');

                    return html
                        ? `<div class="d-flex flex-column gap-1">${html}</div>`
                        : '';
                }
            },

            { data: 'laporan_lembur_checkin', name: 'laporan_lembur_checkin', orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    if (data === '-') {
                        return '-';
                    }

                    const m = moment(data);
                    return `
                        <div class="d-flex flex-column">
                            <span>${m.format('DD-MM-YYYY')}</span>
                            <span>${m.format('HH:mm:ss')} WIB</span>
                        </div>
                    `;
                }
            },
            { data: 'laporan_lembur_checkout', name: 'laporan_lembur_checkout', orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    if (data === '-') {
                        return '-';
                    }

                    const m = moment(data);
                    return `
                        <div class="d-flex flex-column">
                            <span>${m.format('DD-MM-YYYY')}</span>
                            <span>${m.format('HH:mm:ss')} WIB</span>
                        </div>
                    `;

                }
            },

            { data: 'total_jam_lembuar', name: 'total_jam_lembuar', orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    const inVal  = row.laporan_lembur_checkin;
                    const outVal = row.laporan_lembur_checkout;

                    // kontrak data: kalau salah satu "-" → 0
                    if (inVal === '-' || outVal === '-') {
                        return '-';
                    }

                    const duration = moment.duration(
                        moment(outVal).diff(moment(inVal))
                    );

                    const hours   = Math.floor(duration.asHours());
                    const minutes = duration.minutes();

                    return minutes === 0
                        ? `${hours}<span class="small">jam</span>`
                        : `${hours}<span class="small">jam</span> <br>${minutes}<span class="small">menit</span>`;
                }
            },

            { data: 'waktu', name: 'waktu', orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    moment.locale('id');
                    const checkinTime = moment(row.checkin_time_lembur)
                        .format('DD/MM/YYYY, HH:mm');
                    const workTime = moment(row.work_time_lembur)
                        .format('DD/MM/YYYY, HH:mm');
                    const checkinDeadline = moment(row.checkin_deadline_time_lembur)
                        .format('DD/MM/YYYY, HH:mm');
                    return `
                        <div class="d-flex flex-column gap-1">
                            <div class="small">
                                <i class="fas fa-angle-double-down me-1 text-success"></i>
                                ${checkinTime} WIB
                            </div>
                            <div class="small">
                                <i class="fas fa-angle-double-down me-1 text-success"></i>
                                ${workTime} WIB
                            </div>
                            <div class="small">
                                <i class="fas fa-angle-double-down me-1 text-danger"></i>
                                ${checkinDeadline} WIB
                            </div>
                        </div>
                    `;
                }
            },
            { data: 'waktu', name: 'waktu', orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    moment.locale('id');
                    const checkoutTime = moment(row.checkout_time_lembur)
                        .format('DD/MM/YYYY, HH:mm');
                    const checkoutDeadline = moment(row.checkout_deadline_time_lembur)
                        .format('DD/MM/YYYY, HH:mm');
                    return `
                        <div class="d-flex flex-column gap-1">
                            <div class="small">
                                <i class="fas fa-angle-double-up me-1 text-success"></i>
                                ${checkoutTime} WIB
                            </div>
                            <div class="small">
                                <i class="fas fa-angle-double-up me-1 text-danger"></i>
                                ${checkoutDeadline} WIB
                            </div>
                        </div>
                    `;
                }
            },


        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });

</script>
@endpush


