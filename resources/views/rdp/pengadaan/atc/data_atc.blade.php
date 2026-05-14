@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@push('push-script')
<script>
    var rolePengadaan = @json($role);
    var ajaxUrl = @json($ajaxRoute);
    var detailBaseUrl = @json($detailBase);
    var editBaseUrl = @json($editBase);
    var spkBaseUrl = @json($spkBase);
    var editableStatus = @json(\App\Repositories\RdpPengadaanRepo::EDITABLE_STATUS);
    var adminReviewableStatus = @json(\App\Repositories\RdpPengadaanRepo::ADMIN_REVIEWABLE_STATUS);
    var proposalSubmittedStatus = @json(\App\Repositories\RdpPengadaanRepo::PROPOSAL_SUBMITTED_STATUS);
    var proposalSpvApprovedStatus = @json(\App\Repositories\RdpPengadaanRepo::PROPOSAL_SPV_APPROVED_STATUS);
    var spkReadyStatus = @json(\App\Repositories\RdpPengadaanRepo::SPK_READY_STATUS);
    var workRunningStatus = @json(\App\Repositories\RdpPengadaanRepo::WORK_RUNNING_STATUS);
    var vendorFinishedStatus = @json(\App\Repositories\RdpPengadaanRepo::VENDOR_FINISHED_STATUS);
    var resultSpvApprovedStatus = @json(\App\Repositories\RdpPengadaanRepo::RESULT_SPV_APPROVED_STATUS);
    var finishedStatus = @json(\App\Repositories\RdpPengadaanRepo::FINISHED_STATUS);
    var cancelStatus = @json(\App\Repositories\RdpPengadaanRepo::CANCEL_STATUS);

    function pengadaanStatusBadge(status, type) {
        if (type !== 'display') {
            return status || '-';
        }

        const statusClass = {
            'Diajukan': 'badge-soft-primary',
            'Pengajuan Ditolak SPV, cek catatan': 'badge-soft-danger',
            'Pengajuan Revisi': 'badge-soft-warning',
            'Vendor Ditugaskan, menunggu proposal vendor': 'badge-soft-info',
            'Proposal Vendor Diajukan, menunggu persetujuan Admin/SPV': 'badge-soft-warning',
            'Proposal Disetujui SPV, menunggu Pimpinan': 'badge-soft-primary',
            'Proposal Ditolak Pimpinan': 'badge-soft-danger',
            'Proposal Disetujui Pimpinan, Penerbitan SPK': 'badge-soft-info',
            'SPK Terbit, Pekerjaan Pengadaan Berjalan': 'badge-soft-info',
            'Pengadaan Selesai oleh Vendor, menunggu verifikasi Admin/SPV': 'badge-soft-warning',
            'Pengadaan Disetujui SPV, menunggu Pimpinan': 'badge-soft-primary',
            'Pengadaan Selesai': 'badge-soft-success',
            'Pengadaan Dibatalkan': 'badge-soft-dark',
        };

        return `<span class="badge ${statusClass[status] || 'badge-soft-secondary'} d-inline-block text-wrap px-2 py-1" style="font-size:13px; line-height:1.25; max-width:190px; white-space:normal;">${status || '-'}</span>`;
    }

    function rumahFromRow(data) {
        return data.rdp_karyawan_masuks?.rdp_master_rumahs || {};
    }

    function employeeFromRow(data) {
        return data.rdp_karyawan_masuks?.data_employees || {};
    }

    function renderPengadaanAction(data) {
        const vendor = data.rdp_master_vendors?.nama || '-';
        const employee = employeeFromRow(data);
        const nama = employee.name || '-';
        let detailLabel = 'Detail Data';
        if (rolePengadaan === 'vendor') {
            if (data.status === @json(\App\Repositories\RdpPengadaanRepo::VENDOR_ASSIGNED_STATUS)) {
                detailLabel = 'Ajukan Proposal';
            } else if (data.status === workRunningStatus) {
                detailLabel = 'Kirim Laporan';
            } else {
                detailLabel = 'Detail Pengadaan';
            }
        }

        let actions = `
            <a href="${detailBaseUrl}/${data.id}" class="dropdown-item">
                <i class="fas fa-eye fa-fw"></i> ${detailLabel}
            </a>`;

        if (rolePengadaan === 'admin') {
            actions += `
                <a href="${editBaseUrl}/${data.id}" class="dropdown-item">
                    <i class="fas fa-edit fa-fw"></i> ${adminReviewableStatus.includes(data.status) ? 'Edit & Setujui' : 'Edit Data'}
                </a>`;
            if (adminReviewableStatus.includes(data.status)) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Minta revisi pengajuan ${nama}`, id: data.id})}' class="dropdown-item text-warning review-pengadaan" data-toggle="modal" data-target="#modalReviewPengadaan" href="javascript:void(0);">
                        <i class="fas fa-undo fa-fw"></i> Minta Revisi
                    </a>`;
            }
            if (data.status === proposalSubmittedStatus) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Setujui proposal vendor ${vendor}?`, id: data.id})}' class="dropdown-item text-success delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireApproveProposal()" data-submit-label="Setujui" href="javascript:void(0);">
                        <i class="fas fa-check fa-fw"></i> Setujui Proposal
                    </a>
                    <a data-json='${JSON.stringify({msg: `Kembalikan proposal ke vendor ${vendor}?`, id: data.id})}' class="dropdown-item text-warning delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireRequestProposalRevision()" data-submit-label="Kembalikan" href="javascript:void(0);">
                        <i class="fas fa-undo fa-fw"></i> Kembalikan Proposal
                    </a>`;
            }
            if (data.status === spkReadyStatus) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Terbitkan SPK untuk vendor ${vendor}?`, id: data.id})}' class="dropdown-item text-primary delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wirePublishSpk()" data-submit-label="Terbitkan" href="javascript:void(0);">
                        <i class="fas fa-file-signature fa-fw"></i> Terbitkan SPK
                    </a>`;
            }
            if (data.status === vendorFinishedStatus) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Setujui laporan hasil pengadaan dari ${vendor}?`, id: data.id})}' class="dropdown-item text-success delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireApproveLaporan()" data-submit-label="Setujui" href="javascript:void(0);">
                        <i class="fas fa-check fa-fw"></i> Setujui Laporan Vendor
                    </a>`;
            }
            if (![finishedStatus, cancelStatus].includes(data.status)) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Batalkan pengadaan vendor ${vendor}?`, id: data.id})}' class="dropdown-item text-danger delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireCancel()" data-submit-label="Batalkan" href="javascript:void(0);">
                        <i class="fas fa-ban fa-fw"></i> Batalkan
                    </a>`;
            }
            actions += `
                <a data-json='${JSON.stringify({msg: `Hapus data pengadaan vendor ${vendor}?`, id: data.id})}' class="dropdown-item text-danger delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireDelete()" href="javascript:void(0);">
                    <i class="fas fa-trash-alt fa-fw"></i> Hapus Data
                </a>`;
        }

        if (rolePengadaan === 'karyawan') {
            if (editableStatus.includes(data.status)) {
                actions += `
                    <a href="${editBaseUrl}/${data.id}" class="dropdown-item">
                        <i class="fas fa-edit fa-fw"></i> Edit Data
                    </a>
                    <a data-json='${JSON.stringify({msg: `Batalkan pengajuan pengadaan ${nama}?`, id: data.id})}' class="dropdown-item text-danger delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireCancel()" data-submit-label="Batalkan" href="javascript:void(0);">
                        <i class="fas fa-ban fa-fw"></i> Batalkan
                    </a>`;
            }
        }

        if (rolePengadaan === 'pimpinan') {
            if ([proposalSpvApprovedStatus, resultSpvApprovedStatus].includes(data.status)) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Setujui pengadaan vendor ${vendor}?`, id: data.id})}' class="dropdown-item text-success delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireApprove()" data-submit-label="Setujui" href="javascript:void(0);">
                        <i class="fas fa-check fa-fw"></i> Setujui
                    </a>`;
            }
            if (data.status === proposalSpvApprovedStatus) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Tolak proposal pengadaan vendor ${vendor}?`, id: data.id})}' class="dropdown-item text-danger delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireReject()" data-submit-label="Tolak" href="javascript:void(0);">
                        <i class="fas fa-times fa-fw"></i> Tolak
                    </a>`;
            }
        }

        return `
            <div class="btn-group">
                <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown">
                    <i class="mdi mdi-dots-vertical"></i>
                </a>
                <div class="dropdown-menu">${actions}</div>
            </div>`;
    }

    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: 25,dom: 'lrtip',
        order: [[1, 'desc']],
        columnDefs: [
            { className: 'text-left', targets: [2,3,4,5,6,7,8,10] },
            { className: 'px-0', targets: [0] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: ajaxUrl,
        columns: [
            { data: null, name: 'created_at', orderable: false, searchable: false, render: renderPengadaanAction },
            { data: null, name: 'DT_RowIndex', orderable: false, searchable: false, render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
            { data: null, name: 'rdp_karyawan_masuks.data_employees.name', render: function(data) { return employeeFromRow(data).name || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.data_employees.number', render: function(data) { return employeeFromRow(data).number || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.data_employees.master_positions.name', render: function(data) { return employeeFromRow(data).master_positions?.name || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.rdp_master_rumahs.block', render: function(data) { return rumahFromRow(data).block || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.rdp_master_rumahs.tipe', render: function(data) { return rumahFromRow(data).tipe || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.rdp_master_rumahs.nomor', render: function(data) { return rumahFromRow(data).nomor || '-'; } },
            { data: null, name: 'rdp_master_vendors.nama', render: function(data) { return data.rdp_master_vendors?.nama || '-'; } },
            { data: 'rdp_pengadaan_items_count', name: 'rdp_pengadaan_items_count', searchable: false },
            { data: 'status', name: 'status', render: pengadaanStatusBadge },
            {
                data: null, name: 'nomor_spk_surat', orderable: true, searchable: true,
                render: function(data, type) {
                    const visible = [workRunningStatus, vendorFinishedStatus, resultSpvApprovedStatus, finishedStatus].includes(data.status);
                    const nomorSpk = data.nomor_spk_surat || '-';
                    if (type !== 'display') {
                        return visible ? nomorSpk : '-';
                    }
                    return visible
                        ? `<div class="text-nowrap">
                                <div class="font-weight-semibold mb-1">${nomorSpk}</div>
                                <a href="${spkBaseUrl}/${data.id}" target="_blank" class="btn btn-sm btn-primary">Lihat SPK</a>
                            </div>`
                        : '-';
                }
            },
        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });

    $('#modalReviewPengadaan').on('show.bs.modal', function(e) {
        const data = $(e.relatedTarget).data('json');
        if (data) {
            $(this).find('.msg').text(data.msg);
            Livewire.dispatch('setDeleteId', {id: data.id});
        }
    });
</script>
@endpush
