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
        processing: true,serverSide: true,dom: 'lrtip',
        pageLength: -1,
        paging: false,
        lengthChange: false,
        order: [[3, 'desc']],
        columnDefs: [
            { className: 'text-left', targets: [1] },
            // { className: 'text-center text-muted', targets: [2] },
            // { className: 'px-0', targets: [0] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: {
            url: '{{ route("izin.passingGradeCutiDT") }}',
            data: function (d) {
                const params = new URLSearchParams(window.location.search);
                const getParam = (key, selector) => {
                    return params.has(key)
                        ? params.get(key)
                        : $(selector).val();
                };
                d.month = getParam('month', '[name="month"]');
                d.year  = getParam('year',  '[name="year"]');
                d.org   = getParam('master_organization_id', '[name="master_organization_id"]');
            }
        },
        columns: [
            { data: 'id', name: 'DT_RowIndex', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'name', name: 'name', orderable: false, searchable:true },
            { data: 'master_organizations.name', name: 'master_organizations.name', orderable: false, searchable:false },
            { data: 'total_hari_cuti', name: 'total_hari_cuti', orderable: true, searchable:false },

        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });
</script>
@endpush


