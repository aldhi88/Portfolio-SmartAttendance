@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@push('push-script')
<script>
    var rolePermintaan = @json($role);
    var ajaxUrl = @json($ajaxRoute);
    var detailBaseUrl = @json($detailBase);
    var pendingStatus = @json(\App\Repositories\RdpPermintaanRepo::DEFAULT_STATUS);
    var finishedStatus = @json(\App\Repositories\RdpPermintaanRepo::FINISHED_STATUS);

    function permintaanStatusBadge(status, type) {
        if (type !== 'display') {
            return status || '-';
        }

        const css = status === finishedStatus ? 'badge-soft-success' : 'badge-soft-primary';
        return `<span class="badge ${css} d-inline-block text-wrap px-2 py-1" style="font-size:13px; line-height:1.25; max-width:160px; white-space:normal;">${status || '-'}</span>`;
    }

    function rumahFromRow(data) {
        return data.rdp_karyawan_masuks?.rdp_master_rumahs || {};
    }

    function employeeFromRow(data) {
        return data.rdp_karyawan_masuks?.data_employees || {};
    }

    function renderPermintaanAction(data) {
        const employee = employeeFromRow(data);
        const nama = employee.name || '-';
        let actions = `
            <a href="${detailBaseUrl}/${data.id}" class="dropdown-item">
                <i class="fas fa-eye fa-fw"></i> Detail Data
            </a>`;

        if (rolePermintaan === 'admin' && data.status === pendingStatus) {
            actions += `
                <a data-json='${JSON.stringify({msg: `Tandai permintaan ${nama} sebagai selesai?`, id: data.id})}' class="dropdown-item text-success delete" data-toggle="modal" data-target="#modalConfirmDelete" data-dispatch="wireComplete()" data-submit-label="Selesai" href="javascript:void(0);">
                    <i class="fas fa-check fa-fw"></i> Tandai Selesai
                </a>`;
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
        order: [[10, 'asc'], [2, 'asc']],
        columnDefs: [
            { className: 'text-left', targets: [3,4,5,6,7,8,10] },
            { className: 'px-0', targets: [0] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: ajaxUrl,
        columns: [
            { data: null, name: 'created_at', orderable: false, searchable: false, render: renderPermintaanAction },
            { data: null, name: 'DT_RowIndex', orderable: false, searchable: false, render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
            { data: 'created_at', name: 'created_at', render: function(data, type) {
                if (!data) return '-';
                if (type !== 'display') return data;
                const date = new Date(data);
                return date.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
            } },
            { data: null, name: 'rdp_karyawan_masuks.data_employees.name', render: function(data) { return employeeFromRow(data).name || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.data_employees.number', render: function(data) { return employeeFromRow(data).number || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.data_employees.master_positions.name', render: function(data) { return employeeFromRow(data).master_positions?.name || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.rdp_master_rumahs.block', render: function(data) { return rumahFromRow(data).block || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.rdp_master_rumahs.tipe', render: function(data) { return rumahFromRow(data).tipe || '-'; } },
            { data: null, name: 'rdp_karyawan_masuks.rdp_master_rumahs.nomor', render: function(data) { return rumahFromRow(data).nomor || '-'; } },
            { data: 'rdp_permintaan_items_count', name: 'rdp_permintaan_items_count', searchable: false },
            { data: 'status', name: 'status', render: permintaanStatusBadge },
        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });
</script>
@endpush
