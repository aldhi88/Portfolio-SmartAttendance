@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpicker/flatpickr.min.css') }}">

@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/locale/id.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script src="{{ asset('assets/libs/flatpicker/flatpickr.js') }}"></script>
@endsection

@push('push-script')
<script>
    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: 25,dom: 'lrtip',
        order: [[2, 'desc'],[1, 'desc']],
        columnDefs: [
            // { className: 'text-left', targets: [3] },
            { className: 'px-0', targets: [1] },
            { className: 'text-left', targets: [11] },
            { className: 'text-left text-nowrap', targets: [3] },
            { className: 'text-center text-nowrap', targets: ['_all'] },
        ],
        ajax: {
            url: '{{ route("lembur.indexLemburDT") }}',
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
                d.master_organization_id = getParam(
                    'master_organization_id',
                    '[name="master_organization_id"]'
                );
            }
        },
        columns: [
            { data: null, name: 'created_at', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    el = '<input class="data-check" type="checkbox" value="'+row.id+'">';
                    return el;
                }
            },
            { data: null, name: 'created_at', orderable: true, searchable: false,
                render: function(data, type, row) {
                    let editUrl = "edit/"+row.id;
                    let html = `
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu">
                    `;

                    html += `
                                <a class="dropdown-item" href="${editUrl}">
                                    <i class="fas fa-edit fa-fw"></i> Edit Data
                                </a>
                    `;

                    const hasLemburTime =
                        row.laporan_lembur_checkin !== '-' &&
                        row.laporan_lembur_checkout !== '-';

                    const pengawas1Ok =
                        row.pengawas1 === null || row.status_pengawas1 === 'Disetujui';

                    const pengawas2Ok =
                        row.pengawas2 === null || row.status_pengawas2 === 'Disetujui';

                    if (hasLemburTime && pengawas1Ok && pengawas2Ok) {
                        let printPdfUrl = "print-pdf/" + row.id;
                        if(
                            row.format == 'format_patra_niaga' ||
                            row.format=='format_ptc'
                        ){
                            html += `
                                <a class="dropdown-item" href="${printPdfUrl}" target="_blank">
                                    <i class="fas fa-print fa-fw"></i> Print Surat Lembur
                                </a>
                            `;
                        }
                    }

                    const hasPengawas1 = row.pengawas1 !== null;
                    const hasPengawas2 = row.pengawas2 !== null;

                    const pengawas1Approved = !hasPengawas1 || row.status_pengawas1 === 'Disetujui';
                    const pengawas2Approved = !hasPengawas2 || row.status_pengawas2 === 'Disetujui';

                    const allApproved = pengawas1Approved && pengawas2Approved;
                    const anyPending =
                        (hasPengawas1 && row.status_pengawas1 !== 'Disetujui') ||
                        (hasPengawas2 && row.status_pengawas2 !== 'Disetujui');


                    if (anyPending) {
                        const dtJson = {
                            msg: `Anda yakin menyetujui data izin ${data.data_employees.name}?`,
                            id: data.id,
                            proses: 'Setujui'
                        };

                        html += `
                            <a data-json='${JSON.stringify(dtJson)}' class="dropdown-item"
                            data-toggle="modal" data-target="#modalConfirmSetuju"
                            data-dispatch="wireProses('Disetujui')"
                            href="javascript:void(0);">
                            <i class="fas fa-check fa-fw"></i> Setujui Data Izin
                            </a>
                        `;
                    }
                    if (allApproved) {
                        const dtJson = {
                            msg: `Anda yakin menolak data izin ${data.data_employees.name}?`,
                            id: data.id,
                            proses: 'Ditolak'
                        };

                        html += `
                            <a data-json='${JSON.stringify(dtJson)}' class="dropdown-item text-danger"
                            data-toggle="modal" data-target="#modalConfirmSetuju"
                            data-dispatch="wireLogGps(${data.id})"
                            href="javascript:void(0);">
                            <i class="fas fa-times fa-fw"></i> Tolak Izin lembur
                            </a>
                        `;
                    }

                    // Klaim absen manual
                    if (allApproved) {
                        const dtJsonKlaim = {
                            msg: `Anda yakin menyetujui data izin ${data.data_employees.name}?`,
                            proses: 'Setujui',
                            log_gps: row.log_gps
                        };
                        html += `
                            <a data-json='${JSON.stringify(dtJsonKlaim)}' class="dropdown-item text-danger"
                            data-toggle="modal" data-target="#modalConfirmClaim"
                            data-dispatch="wireSubmitClaim(${data.data_employee_id})"
                            href="javascript:void(0);">
                            <i class="fas fa-exclamation-circle fa-fw"></i> Klaim Presensi Manual
                            </a>
                        `;

                    }
                    // Delete
                    const dtJson = {
                        msg: `Apakah anda yakin menghapus data izin ${row.data_employees.name}?`,
                        id: data.id
                    };

                    html += `
                        <a data-json='${JSON.stringify(dtJson)}' class="dropdown-item text-danger delete"
                        data-toggle="modal" data-target="#modalConfirmDelete"
                        data-dispatch="wireDelete()"
                        href="javascript:void(0);">
                        <i class="fas fa-trash-alt fa-fw"></i> Hapus Data
                        </a>
                    `;

                    html += `</div></div>`;
                    return html;
                }
            },
            { data: 'tanggal', name: 'tanggal', orderable: true, searchable:false,
                render: function (data, type, row, meta) {
                    return `
                        ${moment(row.tanggal).locale('id').format('DD/MM/YYYY')} <br>
                        <strong>${row.nomor}</strong>
                    `;
                }
            },
            { data: 'data_employees.name', name: 'data_employees.name', orderable: true, searchable:false,
                render: function (data, type, row, meta) {
                    return `
                        ${data} <br>
                        (${row.data_employees.master_organizations.name})
                    `;
                }
            },
            { data: null, name: null, orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    if (!row.pengawas1) {
                        return '';
                    }

                    let color;
                    switch (row.status_pengawas1) {
                        case "Proses":
                            color = "secondary";
                            break;
                        case "Disetujui":
                            color = "success";
                            break;
                        default:
                            color = "danger";
                    }

                    return `
                        ${row.pengawas1.name} <br>
                        <span class="badge badge-${color} px-3" style="font-size:13px">
                            ${row.status_pengawas1}
                        </span>
                    `;
                }
            },
            { data: null, name: null, orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    if (!row.pengawas2) {
                        return '';
                    }

                    let color;
                    switch (row.status_pengawas2) {
                        case "Proses":
                            color = "secondary";
                            break;
                        case "Disetujui":
                            color = "success";
                            break;
                        default:
                            color = "danger";
                    }

                    return `
                        ${row.pengawas2.name} <br>
                        <span class="badge badge-${color} px-3" style="font-size:13px">
                            ${row.status_pengawas2}
                        </span>
                    `;
                }
            },
            { data: 'id', orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    return row.security ? row.security.name : '-';
                }
            },
            { data: 'korlap', name: 'korlap', orderable: false, searchable:false},
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
            { data: 'pekerjaan', name: 'pekerjaan', orderable: false, searchable:false},
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


