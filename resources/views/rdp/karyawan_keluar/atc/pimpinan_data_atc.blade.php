@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@push('push-script')
<script>
    var pendingPimpinanStatus = @json([
        \App\Repositories\RdpKaryawanKeluarRepo::SPV_APPROVED_STATUS,
        \App\Repositories\RdpKaryawanKeluarRepo::ASSET_SPV_APPROVED_STATUS,
    ]);
    var finishedStatus = @json(\App\Repositories\RdpKaryawanKeluarRepo::FINISHED_STATUS);
    var sikBaseUrl = @json(url('rdp/keluar-rdp/izin-keluar/sik'));

    function renderKeluarRdpStatusBadge(status, type) {
        if (type !== 'display') {
            return status || '-';
        }

        const statusClass = {
            'Diajukan': 'badge-soft-primary',
            'Berkas Disetujui SPV, menuggu Pimpinan': 'badge-soft-info',
            'Berkas Ditolak SPV, cek catatan': 'badge-soft-danger',
            'Pengajuan Revisi': 'badge-soft-warning',
            'Berkas Disetujui Pimpinan, menuggu pendataan aset keluar': 'badge-soft-info',
            'Pengajuan Pendataan Aset Keluar': 'badge-soft-warning',
            'Pendataan Disetujui SPV, menuggu Pimpinan': 'badge-soft-primary',
            'Keluar RDP Selesai': 'badge-soft-success',
            'Keluar RDP Dibatalkan': 'badge-soft-dark',
        };

        const css = statusClass[status] || 'badge-soft-secondary';
        return `<span class="badge ${css} d-inline-block text-wrap px-2 py-1" style="font-size:13px; line-height:1.25; max-width:180px; white-space:normal;">${status || '-'}</span>`;
    }

    function renderTanggalIndo(value, type) {
        if (type !== 'display') {
            return value || '';
        }

        return value ? new Intl.DateTimeFormat('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric',
        }).format(new Date(`${value}T00:00:00`)) : '-';
    }

    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: 25,dom: 'lrtip',
        order: [[1, 'desc']],
        columnDefs: [
            { className: 'text-left', targets: [2,3,4,5,6,7,9] },
            { className: 'px-0', targets: [0] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: '{{ route("rdp.persetujuan.izin-keluar.indexDT") }}',
        columns: [
            {
                data: null, name: 'created_at', orderable: false, searchable: false,
                render: function(data) {
                    const nama = data.data_employees?.name || '-';
                    const canProcess = pendingPimpinanStatus.includes(data.status);
                    const approveJson = {
                        msg: `Apakah anda yakin menyetujui izin keluar ${nama}?`,
                        id: data.id
                    };
                    const rejectJson = {
                        msg: `Apakah anda yakin menolak izin keluar ${nama}?`,
                        id: data.id
                    };

                    return `
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="{{ url('rdp/persetujuan/izin-keluar/detail') }}/${data.id}" class="dropdown-item">
                                    <i class="fas fa-eye fa-fw"></i> Detail Data
                                </a>
                                ${canProcess ? `<a data-json='${JSON.stringify(approveJson)}' class="dropdown-item text-success delete"
                                    data-toggle="modal" data-target="#modalConfirmDelete"
                                    data-dispatch="wireApprove()"
                                    data-submit-label="Setujui"
                                    href="javascript:void(0);">
                                    <i class="fas fa-check fa-fw"></i> Setujui
                                </a>` : ''}
                                ${canProcess ? `<a data-json='${JSON.stringify(rejectJson)}' class="dropdown-item text-danger delete"
                                    data-toggle="modal" data-target="#modalConfirmDelete"
                                    data-dispatch="wireReject()"
                                    data-submit-label="Tolak"
                                    href="javascript:void(0);">
                                    <i class="fas fa-ban fa-fw"></i> Tolak
                                </a>` : ''}
                            </div>
                        </div>
                    `;
                }
            },
            {
                data: null, name: 'DT_RowIndex', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: null, name: 'data_employees.name', orderable: true, searchable:true,
                render: function(data) {
                    return data.data_employees?.name || '-';
                }
            },
            {
                data: null, name: 'data_employees.number', orderable: true, searchable:true,
                render: function(data) {
                    return data.data_employees?.number || '-';
                }
            },
            {
                data: null, name: 'data_employees.master_positions.name', orderable: true, searchable:true,
                render: function(data) {
                    return data.data_employees?.master_positions?.name || '-';
                }
            },
            {
                data: null, name: 'rdp_master_rumahs.block', orderable: true, searchable:true,
                render: function(data) {
                    const rumah = data.rdp_master_rumahs;
                    return rumah?.block || '-';
                }
            },
            {
                data: null, name: 'rdp_master_rumahs.tipe', orderable: true, searchable:true,
                render: function(data) {
                    return data.rdp_master_rumahs?.tipe || '-';
                }
            },
            {
                data: null, name: 'rdp_master_rumahs.nomor', orderable: true, searchable:true,
                render: function(data) {
                    return data.rdp_master_rumahs?.nomor || '-';
                }
            },
            {
                data: 'tanggal_keluar', name: 'tanggal_keluar', orderable: true, searchable:true,
                render: function(value, type) {
                    return renderTanggalIndo(value, type);
                }
            },
            {
                data: 'status', name: 'status', orderable: true, searchable:true,
                render: function(status, type) {
                    return renderKeluarRdpStatusBadge(status, type);
                }
            },
            {
                data: null, name: 'file_sik', orderable: false, searchable: false,
                render: function(data, type) {
                    if (type !== 'display') {
                        return data.status === finishedStatus ? 'SIK' : '-';
                    }

                    return data.status === finishedStatus
                        ? `<a href="${sikBaseUrl}/${data.id}" target="_blank" class="btn btn-sm btn-primary">Lihat SIK</a>`
                        : '-';
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
