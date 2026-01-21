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
        // order: [[4, 'desc']],
        columnDefs: [
            { className: 'text-left', targets: [1] },
            // { className: 'text-center text-muted', targets: [2] },
            // { className: 'px-0', targets: [0] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: {
            url: '{{ route("lembur.passingGradeLemburDT") }}',
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
            },
            dataSrc: function (json) {
                // ⬇️ sort client-side berdasarkan total_jam_lembur_aktual (DESC)
                json.data.sort(function (a, b) {
                    const va = parseFloat(a.total_jam_lembur_aktual) || 0;
                    const vb = parseFloat(b.total_jam_lembur_aktual) || 0;
                    return vb - va; // terbesar di atas
                });

                return json.data;
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
            { data: 'total_hari_lembur', name: 'total_hari_lembur', orderable: false, searchable:false },
            { data: 'total_jam_lembur_aktual', name: 'total_jam_lembur_aktual', orderable: true, searchable: false},

        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });
</script>
@endpush


