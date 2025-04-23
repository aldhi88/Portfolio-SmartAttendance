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
        processing: true,serverSide: true,pageLength: 10,sDom: 'lrtip',
        order: [[0, 'asc']],
        columnDefs: [
            { className: 'text-left', targets: [1] },
            { className: 'text-center text-muted', targets: [2] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: '{{ route("perusahaan.indexDT") }}',
        columns: [
            { data: 'action', name: 'created_at', orderable: false, searchable:false },
            { data: 'name', name: 'name', orderable: true, searchable:true },
            { data: 'id', name: 'id', orderable: false, searchable:false },
        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        },
        drawCallback: function () {
            // Bind ulang setiap kali table selesai render

        }
    });
</script>
@endpush
