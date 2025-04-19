@section('style')
        <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@push('push-script')
<script>
    window.addEventListener('reloadDt', param => {
        dtTable.ajax.reload();
    });

    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: 25,sDom: 'lrtip',
        order: [[6, 'desc']],
        columnDefs: [
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: '{{ route("laporan.indexLogAbsenDt") }}',
        columns: [
            { data: 'action', name: 'action', orderable: true, searchable:true },
            { data: 'data_employee_id', name: 'data_employee_id', orderable: true, searchable:true },
            { data: 'name', name: 'name', orderable: true, searchable:true },
            { data: 'master_machines.name', name: 'master_machines.name', orderable: true, searchable:true },
            { data: 'master_machines.master_locations.name', name: 'master_machines.master_locations.id', orderable: true, searchable:true },
            { data: 'master_minors.type', name: 'master_minors.id', orderable: true, searchable:true },
            { data: 'time', name: 'time', orderable: true, searchable:true },
        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });



</script>
@endpush
