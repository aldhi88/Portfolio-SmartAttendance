@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@push('push-script')
<script>
    var editableStatus = @json(\App\Repositories\RdpKaryawanMasukRepo::EDITABLE_STATUS);
    var pendataanAsetStatus = @json(\App\Repositories\RdpKaryawanMasukRepo::PIMPINAN_APPROVED_STATUS);
    var karyawanQueueStatus = @json(\App\Repositories\RdpKaryawanMasukRepo::KARYAWAN_ACTIONABLE_STATUS);
    var finishedStatus = @json(\App\Repositories\RdpKaryawanMasukRepo::FINISHED_STATUS);
    var sipBaseUrl = @json(url('rdp/penempatan/izin-penempatan/sip'));
    function renderPenempatanStatusBadge(status, type) {
        if (type !== 'display') {
            return status || '-';
        }

        const statusClass = {
            'Diajukan': 'badge-soft-primary',
            'Berkas Disetujui SPV, menuggu Pimpinan': 'badge-soft-info',
            'Berkas Ditolak SPV, cek catatan': 'badge-soft-danger',
            'Pengajuan Revisi': 'badge-soft-warning',
            'Berkas Disetujui Pimpinan, menuggu pendataan aset': 'badge-soft-info',
            'Pengajuan Pendataan Aset': 'badge-soft-warning',
            'Pendataan Disetujui SPV, menuggu Pimpinan': 'badge-soft-primary',
            'Disetujui Pimpinan, menunggu Manager HC Region': 'badge-soft-warning',
            'Ditolak Manager HC Region': 'badge-soft-danger',
            'Penempatan Selesai': 'badge-soft-success',
            'Penempatan Dibatalkan': 'badge-soft-dark',
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

    function renderAntrian(data, type, meta, queueStatuses) {
        const isWaiting = queueStatuses.includes(data.status);
        if (type !== 'display') {
            return isWaiting ? 0 : 1;
        }

        return isWaiting
            ? `<span class="badge badge-soft-warning px-2 py-1">#${meta.row + meta.settings._iDisplayStart + 1}</span>`
            : `<span class="badge badge-soft-secondary px-2 py-1">Sudah diproses</span>`;
    }

    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: 25,dom: 'lrtip',
        order: [],
        columnDefs: [
            { className: 'text-left', targets: [3,6,7,8,9] },
            { className: 'px-0', targets: [0] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: '{{ route("rdp.pengajuan.izin-penempatan.indexDT") }}',
        columns: [
            {
                data: null, name: 'created_at', orderable: false, searchable: false,
                render: function(data) {
                    const canEdit = editableStatus.includes(data.status);
                    const canPendataanAset = data.status === pendataanAsetStatus;
                    const dtJson = {
                        msg: `Apakah anda yakin membatalkan pengajuan ${data.nomor_sk_mutasi || '-'}?`,
                        id: data.id
                    };
                    return `
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="{{ url('rdp/pengajuan/izin-penempatan/detail') }}/${data.id}" class="dropdown-item">
                                    <i class="fas fa-eye fa-fw"></i> Detail Data
                                </a>
                                ${canEdit ? `<a href="{{ url('rdp/pengajuan/izin-penempatan/edit') }}/${data.id}" class="dropdown-item">
                                    <i class="fas fa-edit fa-fw"></i> Edit Data
                                </a>` : ''}
                                ${canPendataanAset ? `<a href="{{ url('rdp/pengajuan/izin-penempatan/pendataan-aset') }}/${data.id}" class="dropdown-item">
                                    <i class="fas fa-clipboard-check fa-fw"></i> Pendataan Aset
                                </a>` : ''}
                                ${canEdit ? `<a data-json='${JSON.stringify(dtJson)}' class="dropdown-item text-danger delete"
                                    data-toggle="modal" data-target="#modalConfirmDelete"
                                    data-dispatch="wireCancel()"
                                    data-submit-label="Batalkan"
                                    href="javascript:void(0);">
                                    <i class="fas fa-ban fa-fw"></i> Batalkan
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
                data: null, name: 'antrian', orderable: false, searchable: false,
                render: function(data, type, row, meta) {
                    return renderAntrian(data, type, meta, karyawanQueueStatus);
                }
            },
            { data: 'nomor_sk_mutasi', name: 'nomor_sk_mutasi', orderable: true, searchable:true },
            {
                data: 'tanggal_sk_mutasi', name: 'tanggal_sk_mutasi', orderable: true, searchable:true,
                render: function(value, type) {
                    return renderTanggalIndo(value, type);
                }
            },
            {
                data: 'tanggal_mulai', name: 'tanggal_mulai', orderable: true, searchable:true,
                render: function(value, type) {
                    return renderTanggalIndo(value, type);
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
                data: 'status', name: 'status', orderable: true, searchable:true,
                render: function(status, type) {
                    return renderPenempatanStatusBadge(status, type);
                }
            },
            {
                data: null, name: 'file_sip', orderable: false, searchable: false,
                render: function(data, type) {
                    if (type !== 'display') {
                        return data.status === finishedStatus ? 'SIP' : '-';
                    }

                    return data.status === finishedStatus
                        ? `<a href="${sipBaseUrl}/${data.id}" target="_blank" class="btn btn-sm btn-primary">Lihat SIP</a>`
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
