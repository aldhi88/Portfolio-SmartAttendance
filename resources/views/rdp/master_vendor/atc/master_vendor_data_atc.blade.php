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
        order: [[3, 'asc']],
        columnDefs: [
            { className: 'text-left', targets: [3,4,5,7] },
            { className: 'px-0', targets: [1] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: '{{ route("rdp.master.vendor.indexDT") }}',
        columns: [
            {
                data: null, name: 'created_at', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    return `<input class="data-check" type="checkbox" value="${data.id}">`;
                }
            },
            {
                data: null, name: 'created_at', orderable: false, searchable: false,
                render: function(data, type, row) {
                    const dtJson = {
                        msg: `Apakah anda yakin menghapus data ${data.nama}?`,
                        info: 'User login vendor RDP terkait juga akan ikut dihapus.',
                        id: data.id
                    };

                    return `
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a data-id="${data.id}" data-toggle="modal" data-target="#modalEdit" class="dropdown-item" href="javascript:void(0);">
                                    <i class="fas fa-edit fa-fw"></i> Edit Data
                                </a>
                                <a data-json='${JSON.stringify(dtJson)}' class="dropdown-item text-danger delete"
                                data-toggle="modal" data-target="#modalConfirmDelete"
                                data-dispatch="wireDelete()"
                                href="javascript:void(0);">
                                <i class="fas fa-trash-alt fa-fw"></i> Hapus Data
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
            { data: 'nama', name: 'nama', orderable: true, searchable:true },
            { data: 'telp', name: 'telp', orderable: true, searchable:true },
            { data: 'alamat', name: 'alamat', orderable: true, searchable:true },
            { data: 'status', name: 'status', orderable: true, searchable:true },
            { data: 'user_logins.username', name: 'user_logins.username', orderable: true, searchable:true },
        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });

    $(document).on('change', '.check-data-all', function () {
        let isChecked = $(this).is(':checked');
        table.rows({ page: 'current' }).nodes().each(function (row) {
            $(row).find('.data-check').prop('checked', isChecked);
        });
        $('#btnDeleteSelected').prop('disabled', !isChecked);
    });

    $(document).on('change', '.data-check', function () {
        let total = $('.data-check:visible').length;
        let checked = $('.data-check:visible:checked').length;
        $('.check-data-all').prop('checked', total === checked);
        $('#btnDeleteSelected').prop('disabled', checked === 0);
    });

    dtTable.on('draw.dt', function () {
        $('.check-data-all').prop('checked', false);
        $('#btnDeleteSelected').prop('disabled', true);
    });

    $(document).on('click', '#btnDeleteSelected', function () {
        let ids = $('.data-check:checked').map(function () {
            return $(this).val();
        }).get();

        if (ids.length === 0) {
            alert('Pilih minimal 1 data untuk dihapus.');
            return;
        }

        Livewire.dispatch('setDeleteMultipleId', { ids: ids });

        $('#modalConfirmDeleteMultiple')
            .find('#submitModalConfirmDeleteMultiple')
            .attr('wire:click', 'deleteMultiple()')
            .prop('disabled', false)
            .text('Hapus Data');
        $('#modalConfirmDeleteMultiple')
            .find('.msg')
            .text(`Apakah anda yakin menghapus ${ids.length} data vendor RDP yang dipilih?`);
        $('#modalConfirmDeleteMultiple')
            .find('.delete-info')
            .text('User login vendor RDP terkait juga akan ikut dihapus.');
        $('#modalConfirmDeleteMultiple').modal('show');

    });
</script>
@endpush
