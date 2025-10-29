@section('style')
        <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
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
        ajax: '{{ route("laporan.indexLogGpsDt") }}',
        columns: [
            { data: 'action', name: 'action', orderable: true, searchable:true },
            { data: 'data_employees.name', name: 'data_employees.name', orderable: true, searchable:true },
            { data: 'name', name: 'name', orderable: true, searchable:true },
            { data: 'latitude', name: 'latitude', orderable: true, searchable:true },
            { data: 'longitude', name: 'longitude', orderable: true, searchable:true },

            // ✅ Koma yang tadi hilang sudah ditambahkan
            { data: 'google_map', orderable:false, searchable:false },

            { data: 'created_at', name: 'created_at', orderable: true, searchable:true,
                render: function(data){
                    return moment.utc(data).local().format('DD-MM-YYYY HH:mm:ss');
                }
            }
        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });



</script>
@endpush
