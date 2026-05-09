@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@push('push-script')
<script>
    var rolePerbaikan = @json($role);
    var ajaxUrl = @json($ajaxRoute);
    var detailBaseUrl = @json($detailBase);
    var editBaseUrl = @json($editBase);
    var spkBaseUrl = @json($spkBase);
    var editableStatus = @json(\App\Repositories\RdpPerbaikanRepo::EDITABLE_STATUS);
    var adminReviewableStatus = @json(\App\Repositories\RdpPerbaikanRepo::ADMIN_REVIEWABLE_STATUS);
    var proposalSubmittedStatus = @json(\App\Repositories\RdpPerbaikanRepo::PROPOSAL_SUBMITTED_STATUS);
    var proposalSpvApprovedStatus = @json(\App\Repositories\RdpPerbaikanRepo::PROPOSAL_SPV_APPROVED_STATUS);
    var spkReadyStatus = @json(\App\Repositories\RdpPerbaikanRepo::SPK_READY_STATUS);
    var workRunningStatus = @json(\App\Repositories\RdpPerbaikanRepo::WORK_RUNNING_STATUS);
    var vendorFinishedStatus = @json(\App\Repositories\RdpPerbaikanRepo::VENDOR_FINISHED_STATUS);
    var resultSpvApprovedStatus = @json(\App\Repositories\RdpPerbaikanRepo::RESULT_SPV_APPROVED_STATUS);
    var finishedStatus = @json(\App\Repositories\RdpPerbaikanRepo::FINISHED_STATUS);
    var cancelStatus = @json(\App\Repositories\RdpPerbaikanRepo::CANCEL_STATUS);

    function perbaikanStatusBadge(status, type) {
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
            'SPK Terbit, Pekerjaan Perbaikan Berjalan': 'badge-soft-info',
            'Perbaikan Selesai oleh Vendor, menunggu verifikasi Admin/SPV': 'badge-soft-warning',
            'Perbaikan Disetujui SPV, menunggu Pimpinan': 'badge-soft-primary',
            'Perbaikan Selesai': 'badge-soft-success',
            'Perbaikan Dibatalkan': 'badge-soft-dark',
        };

        return `<span class="badge ${statusClass[status] || 'badge-soft-secondary'} d-inline-block text-wrap px-2 py-1" style="font-size:13px; line-height:1.25; max-width:190px; white-space:normal;">${status || '-'}</span>`;
    }

    function rumahFromRow(data) {
        return data.rdp_karyawan_masuks?.rdp_master_rumahs || {};
    }

    function employeeFromRow(data) {
        return data.rdp_karyawan_masuks?.data_employees || {};
    }

    function renderPerbaikanAction(data) {
        const employee = employeeFromRow(data);
        const nama = employee.name || '-';
        let detailLabel = 'Detail Data';
        if (rolePerbaikan === 'vendor') {
            if (data.status === @json(\App\Repositories\RdpPerbaikanRepo::VENDOR_ASSIGNED_STATUS)) {
                detailLabel = 'Ajukan Proposal';
            } else if (data.status === workRunningStatus) {
                detailLabel = 'Kirim Laporan';
            } else {
                detailLabel = 'Detail Perbaikan';
            }
        }

        const baseActions = `
            <a href="${detailBaseUrl}/${data.id}" class="dropdown-item">
                <i class="fas fa-eye fa-fw"></i> ${detailLabel}
            </a>`;

        let actions = baseActions;

        if (rolePerbaikan === 'admin') {
            actions += `
                <a href="${editBaseUrl}/${data.id}" class="dropdown-item">
                    <i class="fas fa-edit fa-fw"></i> ${adminReviewableStatus.includes(data.status) ? 'Edit & Setujui' : 'Edit Data'}
                </a>`;
            if (adminReviewableStatus.includes(data.status)) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Minta revisi pengajuan ${nama}`, id: data.id})}' class="dropdown-item text-warning review-perbaikan" data-toggle="modal" data-target="#modalReviewPerbaikan" href="javascript:void(0);">
                        <i class="fas fa-undo fa-fw"></i> Minta Revisi
                    </a>`;
            }
            if (data.status === proposalSubmittedStatus) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Setujui proposal vendor untuk ${nama}?`, id: data.id})}' class="dropdown-item text-success delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireApproveProposal()" data-submit-label="Setujui" href="javascript:void(0);">
                        <i class="fas fa-check fa-fw"></i> Setujui Proposal
                    </a>
                    <a data-json='${JSON.stringify({msg: `Kembalikan proposal ke vendor untuk ${nama}?`, id: data.id})}' class="dropdown-item text-warning delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireRequestProposalRevision()" data-submit-label="Kembalikan" href="javascript:void(0);">
                        <i class="fas fa-undo fa-fw"></i> Kembalikan Proposal
                    </a>`;
            }
            if (data.status === spkReadyStatus) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Terbitkan SPK untuk ${nama}?`, id: data.id})}' class="dropdown-item text-primary delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wirePublishSpk()" data-submit-label="Terbitkan" href="javascript:void(0);">
                        <i class="fas fa-file-signature fa-fw"></i> Terbitkan SPK
                    </a>`;
            }
            if (data.status === vendorFinishedStatus) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Setujui laporan hasil perbaikan ${nama}?`, id: data.id})}' class="dropdown-item text-success delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireApproveLaporan()" data-submit-label="Setujui" href="javascript:void(0);">
                        <i class="fas fa-check fa-fw"></i> Setujui Laporan Vendor
                    </a>`;
            }
            if (![finishedStatus, cancelStatus].includes(data.status)) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Batalkan perbaikan ${nama}?`, id: data.id})}' class="dropdown-item text-danger delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireCancel()" data-submit-label="Batalkan" href="javascript:void(0);">
                        <i class="fas fa-ban fa-fw"></i> Batalkan
                    </a>`;
            }
            actions += `
                <a data-json='${JSON.stringify({msg: `Hapus data perbaikan ${nama}?`, id: data.id})}' class="dropdown-item text-danger delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireDelete()" href="javascript:void(0);">
                    <i class="fas fa-trash-alt fa-fw"></i> Hapus Data
                </a>`;
        }

        if (rolePerbaikan === 'karyawan') {
            if (editableStatus.includes(data.status)) {
                actions += `
                    <a href="${editBaseUrl}/${data.id}" class="dropdown-item">
                        <i class="fas fa-edit fa-fw"></i> Edit Data
                    </a>
                    <a data-json='${JSON.stringify({msg: `Batalkan pengajuan perbaikan ${nama}?`, id: data.id})}' class="dropdown-item text-danger delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireCancel()" data-submit-label="Batalkan" href="javascript:void(0);">
                        <i class="fas fa-ban fa-fw"></i> Batalkan
                    </a>`;
            }
        }

        if (rolePerbaikan === 'pimpinan') {
            if ([proposalSpvApprovedStatus, resultSpvApprovedStatus].includes(data.status)) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Setujui perbaikan ${nama}?`, id: data.id})}' class="dropdown-item text-success delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireApprove()" data-submit-label="Setujui" href="javascript:void(0);">
                        <i class="fas fa-check fa-fw"></i> Setujui
                    </a>`;
            }
            if (data.status === proposalSpvApprovedStatus) {
                actions += `
                    <a data-json='${JSON.stringify({msg: `Tolak proposal perbaikan ${nama}?`, id: data.id})}' class="dropdown-item text-danger delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireReject()" data-submit-label="Tolak" href="javascript:void(0);">
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
            { data: null, name: 'created_at', orderable: false, searchable: false, render: renderPerbaikanAction },
            { data: null, name: 'DT_RowIndex', orderable: false, searchable: false, render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
            { data: null, name: 'rdp_karyawan_masuks.data_employees.name', render: function(data) { return employeeFromRow(data).name || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.data_employees.number', render: function(data) { return employeeFromRow(data).number || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.data_employees.master_positions.name', render: function(data) { return employeeFromRow(data).master_positions?.name || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.rdp_master_rumahs.block', render: function(data) { return rumahFromRow(data).block || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.rdp_master_rumahs.tipe', render: function(data) { return rumahFromRow(data).tipe || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.rdp_master_rumahs.nomor', render: function(data) { return rumahFromRow(data).nomor || '-'; } },
            { data: null, name: 'rdp_master_vendors.nama', render: function(data) { return data.rdp_master_vendors?.nama || '-'; } },
            { data: 'rdp_perbaikan_items_count', name: 'rdp_perbaikan_items_count', searchable: false },
            { data: 'status', name: 'status', render: perbaikanStatusBadge },
            {
                data: null, name: 'file_spk', orderable: false, searchable: false,
                render: function(data, type) {
                    const visible = [workRunningStatus, vendorFinishedStatus, resultSpvApprovedStatus, finishedStatus].includes(data.status);
                    if (type !== 'display') {
                        return visible ? 'SPK' : '-';
                    }
                    return visible ? `<a href="${spkBaseUrl}/${data.id}" target="_blank" class="btn btn-sm btn-primary">Lihat SPK</a>` : '-';
                }
            },
        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });

    $('#modalReviewPerbaikan').on('show.bs.modal', function(e) {
        const data = $(e.relatedTarget).data('json');
        if (data) {
            $(this).find('.msg').text(data.msg);
            Livewire.dispatch('setDeleteId', {id: data.id});
        }
    });
</script>
@endpush
