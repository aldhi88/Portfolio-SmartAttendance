@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@push('push-script')
<script>
    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: 25,dom: 'lrtip',
        order: [[1, 'asc']],
        columnDefs: [
            { className: 'text-left', targets: [2,3,4] },
            { className: 'px-0', targets: [0] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: '{{ route("rdp.master.akun-manager.indexDT") }}',
        columns: [
            {
                data: null, name: 'created_at', orderable: false, searchable: false,
                render: function(data) {
                    return `
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a data-id="${data.id}" data-toggle="modal" data-target="#modalEdit" class="dropdown-item" href="javascript:void(0);">
                                    <i class="fas fa-edit fa-fw"></i> Edit Data
                                </a>
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
                data: null, name: 'print_role_name', orderable: true, searchable:true,
                render: function(data) {
                    return data.print_role_name || data.user_roles?.name || '-';
                }
            },
            { data: 'nickname', name: 'nickname', orderable: true, searchable:true },
            { data: 'username', name: 'username', orderable: true, searchable:true },
            {
                data: 'ttd', name: 'ttd', orderable: false, searchable: false,
                render: function(data, type) {
                    if (!data) {
                        return '-';
                    }
                    if (type !== 'display') {
                        return data;
                    }
                    const src = `{{ asset('storage/' . \App\Repositories\RdpManagerAccountRepo::FILE_DIR_TTD) }}/${data}`;
                    return `<img src="${src}" alt="Tanda tangan manager" class="img-fluid" style="max-height:46px; max-width:120px;">`;
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
